<?php
// model/ClienteModel.php

// Carrega de forma segura a classe de conexão global da aplicação
require_once('config/database.php');

class ClienteModel {
    private $db;

    /**
     * Construtor da classe - Obtém automaticamente a conexão PDO configurada via padrão Singleton
     */
    public function __construct() {
        // Inicializa a instância de conexão ativa do banco de dados
        $this->db = Database::getConexao();
    }

    /**
     * SOLUÇÃO DO ERRO INTERNO: Método salvar() mapeado com a estrutura real do banco.
     * Alinha os dados do LoginController preenchendo as restrições NOT NULL.
     * @param array $dados Contém nome, email e senha
     * @return bool
     */
    public function salvar($dados) {
        try {
            // Captura os dados obrigatórios enviados pelo formulário
            $nome  = isset($dados['nome']) ? trim($dados['nome']) : '';
            $email = isset($dados['email']) ? trim($dados['email']) : '';
            $senha = isset($dados['senha']) ? trim($dados['senha']) : '';
            
            // SUPORTE AO NOT NULL: Define um caractere seguro para evitar que o 
            // MySQL rejeite por falta de dados nas colunas obrigatórias.
            // O cliente poderá atualizar o telefone e endereço reais no painel depois.
            $telefone      = ' '; 
            $endereco      = ' ';
            $data_cadastro = date('Y-m-d H:i:s'); // Gera a data/hora atual automaticamente

            // QUERY INTEGRADA: Segue rigorosamente as colunas reais da sua tabela 'cliente'
            $query = "INSERT INTO cliente (nome, email, senha, telefone, endereco, data_cadastro) 
                      VALUES (:nome, :email, :senha, :telefone, :endereco, :data_cadastro)";
                      
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindParam(':data_cadastro', $data_cadastro, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Se houver qualquer falha estrutural, ela será salva no log de erros do Apache
            error_log("Erro crítico no método salvar() do ClienteModel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Insere um novo cliente no banco de dados (Método Legado mantido por segurança)
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param string $telefone
     * @param string $endereco
     * @param string $data_cadastro
     * @return bool Retorna true em caso de sucesso ou false em caso de falha
     */
    public function inserir($nome, $email, $senha, $telefone, $endereco, $data_cadastro) {
        try {
            $query = "INSERT INTO cliente (nome, email, senha, telefone, endereco, data_cadastro) 
                      VALUES (:nome, :email, :senha, :telefone, :endereco, :data_cadastro)";
                      
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindParam(':data_cadastro', $data_cadastro, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao inserir cliente de forma isolada: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Realiza uma busca refinada filtrando pelo e-mail do usuário
     * @param string $email
     * @return array|false Retorna o registro do banco de dados ou falso caso não localizado
     */
    public function buscarPorEmail($email) {
        try {
            $query = "SELECT * FROM cliente WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar cliente por e-mail: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna em definitivo os dados armazenados de um determinado cliente baseado no ID primário
     * @param int $id
     * @return array|false
     */
    public function buscarPorId($id) {
        try {
            $query = "SELECT * FROM cliente WHERE id_cliente = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar cliente por ID ($id): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza as informações básicas cadastrais do perfil do cliente (Módulo Interno)
     * @param int $id
     * @param string $nome
     * @param string $telefone
     * @param string $endereco
     * @return bool
     */
    public function atualizarPerfil($id, $nome, $telefone, $endereco) {
        try {
            $query = "UPDATE cliente 
                      SET nome = :nome, telefone = :telefone, endereco = :endereco 
                      WHERE id_cliente = :id";
                      
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':endereco', $endereco, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar perfil do cliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Altera apenas a credencial de senha do usuário
     * @param int $id
     * @param string $novaSenhaCriptografada
     * @return bool
     */
    public function atualizarSenha($id, $novaSenhaCriptografada) {
        try {
            $query = "UPDATE cliente SET senha = :senha WHERE id_cliente = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':senha', $novaSenhaCriptografada, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar senha do cliente: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarFoto($id, $caminhoFoto) {
        try {
            // Garante o uso da conexão PDO estabelecida no seu Model (usando $this->db)
            $query = "UPDATE cliente SET foto = :foto WHERE id_cliente = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':foto', $caminhoFoto, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar foto do cliente no Model: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove permanentemente a conta de um cliente do sistema GRID&GOL
     * @param int $id
     * @return bool
     */
    public function deletar($id) {
        try {
            $query = "DELETE FROM cliente WHERE id_cliente = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar cliente: " . $e->getMessage());
            return false;
        }
    }
}