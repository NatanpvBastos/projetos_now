<?php
// views/minhas-compras.php

// 1. Extrai com segurança as variáveis a partir do array $usuarioLogado injetado pelo Controller
$nomeUsuario = isset($usuarioLogado['nome']) ? htmlspecialchars($usuarioLogado['nome']) : 'Cliente GRID&GOL';
$dataCadastro = isset($usuarioLogado['data_cadastro']) ? date('d/m/Y', strtotime($usuarioLogado['data_cadastro'])) : date('d/m/Y');
// 2. Garante que a variável $compras seja sempre um array iterável para evitar erros no foreach
$compras = isset($compras) && is_array($compras) ? $compras : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Compras - GRID&GOL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/compras.css">
</head>

<body>

    <header>
        <div class="logo">GRID<span>&</span>GOL</div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="avatar-container">
                <?php if (!empty($usuarioLogado['foto']) && file_exists(__DIR__ . '/../' . $usuarioLogado['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($usuarioLogado['foto']); ?>" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    <i class="fas fa-user-ninja"></i>
                <?php endif; ?>
            </div>
            <div class="upload-box" style="margin-top: 1rem; background: #fff; padding: 1rem; border-radius: 6px; border: 1px solid var(--border);">
                <h4 style="margin-bottom: 0.5rem;">Foto de Perfil</h4>
                <form action="index.php?url=alterar-avatar" method="POST" enctype="multipart/form-data">
                    <input type="file" name="avatar" accept="image/*" required style="margin-bottom: 0.5rem; display: block;">
                    <button type="submit" style="background: var(--primary); color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        <i class="fas fa-upload"></i> Atualizar Foto
                    </button>
                </form>
            </div>
            <h3><?php echo $nomeUsuario; ?></h3>
            <p>Cliente desde <?php echo $dataCadastro; ?></p>
            <ul class="menu-lateral">
                <li><a href="index.php?url=painel"><i class="fas fa-tachometer-alt"></i> Meu Painel</a></li>
                <li><a href="index.php?url=minhas-compras" class="active"><i class="fas fa-shopping-bag"></i> Minhas Compras</a></li>
                <li><a href="index.php?url=editar-perfil"><i class="fas fa-user-edit"></i> Editar Perfil</a></li>
                <li><a href="index.php?url=logout" style="color: var(--primary);"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
        </aside>

        <main class="content-area">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.6rem; border-left: 4px solid var(--primary); padding-left: 0.5rem; text-transform: uppercase;">Histórico de Pedidos</h3>

            <?php if (empty($compras)): ?>
                <div class="no-orders">
                    <i class="fas fa-box-open"></i>
                    <p>Você ainda não realizou nenhuma compra no GRID&GOL.</p>
                </div>
            <?php else: ?>
                <div class="orders-table-container">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th># ID Pedido</th>
                                <th>Data da Compra</th>
                                <th>Forma de Pagamento</th>
                                <th>Valor Total</th>
                                <th>Status do Pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($compras as $compra):
                                // Captura a coluna 'status' vinda do banco de dados (pedido)
                                $status = $compra['status'] ?? 'Pendente';
                                $badgeClass = 'status-padrao';

                                // Normalização do status para atribuição da classe CSS correspondente
                                $statusUpper = strtoupper($status);
                                if ($statusUpper === 'APROVADO' || $statusUpper === 'PAGO' || $statusUpper === 'CONCLUIDO') {
                                    $badgeClass = 'status-aprovado';
                                } elseif ($statusUpper === 'AGUARDANDO PAGAMENTO' || $statusUpper === 'PENDENTE') {
                                    $badgeClass = 'status-aguardando';
                                }
                            ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($compra['id_pedido']); ?></strong></td>

                                    <td><?php echo isset($compra['data_pedido']) ? date('d/m/Y H:i', strtotime($compra['data_pedido'])) : '---'; ?></td>

                                    <td>
                                        <?php
                                        // Utiliza uma coluna de forma de pagamento alternativa se existir, ou define o padrão estrutural
                                        $forma = strtoupper($compra['forma_pagamento'] ?? 'CARTAO');
                                        if ($forma === 'CARTAO' || $forma === 'CREDITO'): ?>
                                            <i class="fas fa-credit-card" style="color: #495057;"></i> Cartão de Crédito
                                        <?php elseif ($forma === 'PIX'): ?>
                                            <i class="fas fa-qrcode" style="color: #00bfa5;"></i> PIX Instantâneo
                                        <?php else: ?>
                                            <i class="fas fa-barcode" style="color: #ff5252;"></i> Boleto Bancário
                                        <?php endif; ?>
                                    </td>

                                    <td><strong>R$ <?php echo number_format($compra['valor_total'] ?? 0.00, 2, ',', '.'); ?></strong></td>

                                    <td>
                                        <span class="status-badge <?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <footer>
        <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
    </footer>
</body>

</html>