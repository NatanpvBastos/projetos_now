<?php
// model/ProdutoModel.php

if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
} else {
    die("Erro crítico: O arquivo 'config/database.php' não foi encontrado.");
}

class ProdutoModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConexao();
    }

    /**
     * Lista todos os produtos trazendo o nome_categoria via JOIN
     */
    public function listarTodos() {
        try {
            $query = "SELECT p.*, c.nome_categoria 
                      FROM produto p
                      LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                      ORDER BY p.id_produto DESC";
                      
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar todos os produtos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lista os produtos filtrados por categoria na vitrine pública
     */
    public function listarPorCategoria($categoria) {
        try {
            $query = "SELECT p.*, c.nome_categoria 
                      FROM produto p
                      LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                      WHERE p.id_categoria = :categoria_id 
                         OR c.nome_categoria LIKE :categoria_nome";
                         
            $stmt = $this->db->prepare($query);
            
            $paramNome = "%" . $categoria . "%";
            $paramId = is_numeric($categoria) ? (int)$categoria : 0;
            
            $stmt->bindParam(':categoria_id', $paramId, PDO::PARAM_INT);
            $stmt->bindParam(':categoria_nome', $paramNome, PDO::PARAM_STR);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao filtrar produtos por categoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lista as categorias para preencher o <select> (Incluso apelido 'nome' para o editar)
     */
    public function buscarCategorias() {
        try {
            $query = "SELECT id_categoria, nome_categoria, nome_categoria AS nome FROM categoria ORDER BY nome_categoria ASC"; 
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar categorias: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Procura os dados de um único produto pelo seu id_produto
     */
    public function buscarPorId($id) {
        try {
            $query = "SELECT * FROM produto WHERE id_produto = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar produto por ID ($id): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Insere calculando manualmente o próximo id_produto (evitando a falta de AUTO_INCREMENT)
     * e tratando o campo imagem como string limpa caso não venha anexo (evitando erro de NOT NULL).
     */
    public function inserir($nome, $descricao, $preco, $estoque, $imagem, $id_categoria) {
        try {
            // Descobre dinamicamente o próximo ID disponível na tabela
            $sqlId = "SELECT COALESCE(MAX(id_produto), 0) + 1 AS proximo_id FROM produto";
            $stmtId = $this->db->query($sqlId);
            $regId = $stmtId->fetch(PDO::FETCH_ASSOC);
            $proximoId = $regId['proximo_id'];

            // Evita a quebra da restrição NOT NULL caso o upload esteja vazio
            $imagemFinal = ($imagem === null) ? '' : $imagem;

            $query = "INSERT INTO produto (id_produto, nome, descricao, preco, estoque, imagem, id_categoria) 
                      VALUES (:id_produto, :nome, :descricao, :preco, :estoque, :imagem, :id_categoria)";
                      
            $stmt = $this->db->prepare($query);
            
            $stmt->bindValue(':id_produto', $proximoId, PDO::PARAM_INT);
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->bindValue(':preco', $preco);
            $stmt->bindValue(':estoque', $estoque, PDO::PARAM_INT);
            $stmt->bindValue(':imagem', $imagemFinal, PDO::PARAM_STR);
            $stmt->bindValue(':id_categoria', $id_categoria, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro crítico ao inserir produto no banco: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza os dados do produto exatamente sob a estrutura fornecida
     */
    public function atualizar($id, $nome, $descricao, $preco, $estoque, $imagem, $id_categoria) {
        try {
            $imagemFinal = ($imagem === null) ? '' : $imagem;

            $query = "UPDATE produto 
                      SET nome = :nome, 
                          descricao = :descricao, 
                          preco = :preco, 
                          estoque = :estoque, 
                          imagem = :imagem, 
                          id_categoria = :id_categoria 
                      WHERE id_produto = :id";
                      
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->bindValue(':preco', $preco);
            $stmt->bindValue(':estoque', $estoque, PDO::PARAM_INT);
            $stmt->bindValue(':imagem', $imagemFinal, PDO::PARAM_STR);
            $stmt->bindValue(':id_categoria', $id_categoria, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar produto ($id): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui o produto baseado na chave id_produto
     */
    public function deletar($id) {
        try {
            $query = "DELETE FROM produto WHERE id_produto = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar produto ($id): " . $e->getMessage());
            return false;
        }
    }

    // =========================================================================
    // NOVOS MÉTODOS DE NEGÓCIO IMPLEMENTADOS PARA INTEGRAÇÃO DO SISTEMA
    // =========================================================================

    /**
     * Deduz a quantidade vendida diretamente do stock atual do produto
     */
    public function atualizarEstoque($idProduto, $quantidadeVendida) {
        try {
            $query = "UPDATE produto 
                      SET estoque = GREATEST(estoque - :quantidade, 0) 
                      WHERE id_produto = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $idProduto, PDO::PARAM_INT);
            $stmt->bindValue(':quantidade', $quantidadeVendida, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar estoque do produto ($idProduto): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Procura uma lista dinâmica de produtos baseando-se num array de IDs.
     * Útil para recuperar as informações atualizadas de itens que estão no carrinho.
     */
    public function buscarProdutosPorListaId(array $listaIds) {
        if (empty($listaIds)) {
            return [];
        }

        try {
            // Cria uma string segura de placeholders baseada na quantidade de IDs informados (ex: ?, ?, ?)
            $placeholders = implode(',', array_fill(0, count($listaIds), '?'));
            
            $query = "SELECT * FROM produto WHERE id_produto IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            
            // Executa passando diretamente o array indexado
            $stmt->execute(array_values($listaIds));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar lista de produtos por IDs: " . $e->getMessage());
            return [];
        }
    }
}