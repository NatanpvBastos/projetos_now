<?php
// views/painel-admin.php

// Proteção para garantir que o arquivo só seja carregado através do Roteador Central (index.php)
if (!isset($tituloPagina)) {
    header('Location: index.php?url=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/painel-admin.css">
    <link rel="stylesheet" href="assets/css/gestao-pedidos.css">
</head>
<body>

<div class="admin-container">
    
    <aside class="sidebar">
        <div class="brand">GRID<span>&</span>GOL</div>
        
        <p class="menu-label">Navegação Principal</p>
        <a href="index.php?url=painel-admin" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="index.php?url=cadastro-produto"><i class="fas fa-boxes"></i> Produtos (CRUD)</a>
        
        <p class="menu-label">Controle de Segurança</p>
        <a href="index.php?url=cadastro-admin"><i class="fas fa-user-plus"></i> Novo Administrador</a>
        <a href="index.php?url=editar-perfil-admin"><i class="fas fa-user-cog"></i> Configurar Perfil</a>
        
        <div class="sidebar-footer">
            <a href="index.php?url=logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Encerrar Sessão</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="welcome-header">
            <h2>Olá, <span><?php echo htmlspecialchars($nomeAdmin); ?></span></h2>
        </div>
        <div class="stats-grid">
            <div class="stat-card" style="border-top-color: #1976d2;">
                <div>
                    <h3>Produtos Cadastrados</h3>
                    <div class="number"><?php echo (int)$totalProdutos; ?></div>
                </div>
                <i class="fas fa-box" style="color: rgba(25, 118, 210, 0.1);"></i>
            </div>

            <div class="stat-card" style="border-top-color: #d32f2f;">
                <div>
                    <h3>Clientes</h3>
                    <div class="number"><?php echo (int)$totalClientes; ?></div>
                </div>
                <i class="fas fa-users" style="color: rgba(211, 47, 47, 0.1);"></i>
            </div>
            
            <div class="stat-card" style="border-top-color: #2e7d32;">
                <div>
                    <h3>Categorias</h3>
                    <div class="number"><?php echo (int)$totalCategorias; ?></div>
                </div>
                <i class="fas fa-folder" style="color: rgba(46, 125, 50, 0.1);"></i>
            </div>

            <div class="stat-card" style="border-top-color: #f57c00;">
                <div>
                    <h3>Vendas (Mês)</h3>
                    <div class="number">R$ <?php echo number_format($vendasMes, 2, ',', '.'); ?></div>
                </div>
                <i class="fas fa-shopping-cart" style="color: rgba(245, 124, 0, 0.1);"></i>
            </div>
        </div>

        <div class="quick-actions">
            <h3>Ações Rápidas do Sistema</h3>
            <div style="display: flex; gap: 15px; margin-top: 15px;">
                <a href="index.php?url=cadastro-produto" class="btn-action" style="background: #1976d2; color: white; padding: 12px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px;"><i class="fas fa-plus"></i> Novo Produto</a>
                <a href="index.php?url=editar-perfil-admin" class="btn-action" style="background: #333; color: white; padding: 12px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px;"><i class="fas fa-user-cog"></i> Configurar Perfil</a>
            </div>
        </div>

        <div class="management-section">
            <h3>Controle e Fluxo de Pedidos</h3>
            <div class="orders-table-wrapper">
                <?php if (!empty($pedidosLista) && is_array($pedidosLista)): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Data da Compra</th>
                                <th>ID Cliente</th>
                                <th>Valor Total</th>
                                <th>Estado</th>
                                <th style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidosLista as $pedido): ?>
                                <?php 
                                    // Sanitização básica dos dados vindos do banco
                                    $idPed = htmlspecialchars($pedido['id_pedido']);
                                    $dataPed = date('d/m/Y H:i', strtotime($pedido['data_pedido']));
                                    $idCli = htmlspecialchars($pedido['id_cliente']);
                                    $valTotal = number_format((float)$pedido['valor_total'], 2, ',', '.');
                                    $statusOriginal = $pedido['status'];
                                    
                                    // Determinação da classe visual baseada no estado
                                    $statusLower = mb_strtolower($statusOriginal);
                                    if ($statusLower === 'pago' || $statusLower === 'aprovado' || $statusLower === 'concluido') {
                                        $badgeClass = 'badge-pago';
                                    } elseif ($statusLower === 'pendente' || $statusLower === 'aguardando') {
                                        $badgeClass = 'badge-pendente';
                                    } else {
                                        $badgeClass = 'badge-outros';
                                    }
                                ?>
                                <tr>
                                    <td>#<?php echo $idPed; ?></td>
                                    <td><?php echo $dataPed; ?></td>
                                    <td>Cliente #<?php echo $idCli; ?></td>
                                    <td><strong>R$ <?php echo $valTotal; ?></strong></td>
                                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($statusOriginal); ?></span></td>
                                    <td style="text-align: center;">
                                        <?php if ($statusLower !== 'pago' && $statusLower !== 'aprovado' && $statusLower !== 'concluido'): ?>
                                            <a href="index.php?url=aprovar-pedido&id=<?php echo $idPed; ?>" 
                                               class="btn-approve" 
                                               onclick="return confirm('Deseja realmente confirmar o pagamento do pedido #<?php echo $idPed; ?>?');">
                                                <i class="fas fa-check"></i> Aprovar Pagamento
                                            </a>
                                        <?php else: ?>
                                            <span style="color: #2e7d32; font-size: 13px;"><i class="fas fa-check-double"></i> Processado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-orders">
                        <i class="fas fa-info-circle"></i> Nenhum registro de pedido foi localizado no banco de dados até ao momento.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

</div>

</body>
</html>