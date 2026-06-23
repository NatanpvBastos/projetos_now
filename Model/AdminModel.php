<?php
// model/AdminModel.php

// Garante o carregamento correto do banco com base no caminho do sistema
if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
} else {
    require_once 'config/database.php';
}

class AdminModel {
    private $db;

    /**
     * Construtor da classe - Inicializa a conexão ativa via PDO
     */
    public function __construct() {
        $this->db = Database::getConexao();
    }

    /**
     * Procura um administrador pelo e-mail (Usado no fluxo de Login, Validação e Duplicidade)
     */
    public function buscarPorEmail($email) {
        try {
            $query = "SELECT * FROM administrador WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado : null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar administrador por email: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Procura um administrador pelo ID (Usado para validar a sessão e carregar dados no formulário)
     */
    public function buscarPorId($id) {
        try {
            $query = "SELECT id_admin, nome, email, senha, data_cadastro 
                      FROM administrador WHERE id_admin = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado : null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar administrador por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Realiza a gravação de um novo Administrador no Banco de Dados
     */
    public function inserir($nome, $email, $senhaHash) {
        try {
            $data_cadastro = date('Y-m-d H:i:s'); // Adicionado para preencher o campo NOT NULL data_cadastro
            
            $query = "INSERT INTO administrador (nome, email, senha, data_cadastro) 
                      VALUES (:nome, :email, :senha, :data_cadastro)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senhaHash, PDO::PARAM_STR);
            $stmt->bindParam(':data_cadastro', $data_cadastro, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao inserir novo administrador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * NOVO MÉTODO: Atualiza as informações cadastrais básicas do administrador
     * @param int $id
     * @param string $nome
     * @param string $email
     * @return bool
     */
    public function atualizarPerfil($id, $nome, $email) {
        try {
            $query = "UPDATE administrador 
                      SET nome = :nome, email = :email 
                      WHERE id_admin = :id";
                      
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar perfil do administrador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * NOVO MÉTODO: Atualiza isoladamente a credencial de senha criptografada do administrador
     * @param int $id
     * @param string $novaSenhaCriptografada
     * @return bool
     */
    public function atualizarSenha($id, $novaSenhaCriptografada) {
        try {
            $query = "UPDATE administrador 
                      SET senha = :senha 
                      WHERE id_admin = :id";
                      
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':senha', $novaSenhaCriptografada, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar senha do administrador: " . $e->getMessage());
            return false;
        }
    }
}