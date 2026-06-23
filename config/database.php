<?php
// config/database.php

class Database {
    private static $conexao = null;

    public static function getConexao() {
        if (self::$conexao === null) {
            try {
                $host = '127.0.0.1';
                $db_name = 'projeto_grid_go';
                $username = 'root';
                $password = ''; // Coloque a senha do seu ambiente se houver
                
                self::$conexao = new PDO(
                    "mysql:host=$host;dbname=$db_name;charset=utf8mb4", 
                    $username, 
                    $password
                );
                
                // Configura o PDO para disparar exceções em caso de erros
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch (PDOException $exception) {
                die("Erro de conexão com o banco de dados: " . $exception->getMessage());
            }
        }
        return self::$conexao;
    }
}