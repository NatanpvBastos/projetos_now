<?php
// helpers/PainelHelper.php
// Este arquivo serve estritamente para higienizar e preparar as variáveis do painel.

$nomeUsuario     = isset($usuarioLogado['nome']) ? htmlspecialchars($usuarioLogado['nome']) : 'Utilizador';
$emailUsuario    = isset($usuarioLogado['email']) ? htmlspecialchars($usuarioLogado['email']) : '';
$telefoneUsuario = isset($usuarioLogado['telefone']) ? htmlspecialchars($usuarioLogado['telefone']) : 'Não informado';
$enderecoUsuario = isset($usuarioLogado['endereco']) ? htmlspecialchars($usuarioLogado['endereco']) : 'Não informado';
$dataCadastro    = isset($usuarioLogado['data_cadastro']) ? date('d/m/Y', strtotime($usuarioLogado['data_cadastro'])) : '';

$totalPedidos       = isset($totalPedidos) ? (int)$totalPedidos : 0;
$quantidadeCarrinho = isset($quantidadeCarrinho) ? (int)$quantidadeCarrinho : 0;