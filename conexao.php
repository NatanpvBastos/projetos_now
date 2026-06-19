<?php
$host = "localhost";
$usuario = "root";
$senha = ""; 
$banco = "projeto_grid_go";
$mysqli = new mysqli($host, $usuario, $senha, $banco);

if ($mysqli->connect_errno) {
    die("Falha na conexão: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
?>