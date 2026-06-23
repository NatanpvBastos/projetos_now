<?php
// views/painel.php
// ATENÇÃO: Nenhuma lógica de banco ou sessão entra aqui. Apenas exibição.
require_once __DIR__ . '/../help/PainelHelper.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Cliente - GRID&GOL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/painel.css">
</head>

<body>

    <header>
        <div class="logo">GRID<span>&</span>GOL</div>
        <nav>
            <a href="index.php">Início</a>
            <a href="index.php?url=futebol">Futebol</a>
            <a href="index.php?url=formula1">Fórmula 1</a>

            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin'): ?>
                <a href="index.php?url=cadastro-produto"><i class="fas fa-plus-circle"></i> Novo Produto</a>
            <?php endif; ?>
        </nav>
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
                <li><a href="index.php?url=painel" class="active"><i class="fas fa-tachometer-alt"></i> Meu Painel</a></li>
                <li><a href="index.php?url=minhas-compras"><i class="fas fa-shopping-bag"></i> Minhas Compras</a></li>
                <li><a href="index.php?url=editar-perfil"><i class="fas fa-user-edit"></i> Editar Perfil</a></li>
                <li><a href="index.php?url=logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Sair da Conta</a></li>
            </ul>
        </aside>

        <main class="content-area">
            <div class="welcome-box">
                <h2>Olá, <?php echo $nomeUsuario; ?>!</h2>
                <p style="color: var(--gray); margin-top: 0.2rem;">Gerencie suas informações e acompanhe seus pedidos da GRID&GOL.</p>
            </div>

            <div class="quick-stats">
                <div class="stat-card active-card">
                    <div class="stat-info">
                        <h4>Meus Pedidos</h4>
                        <?php
                        if (isset($totalPedidos) && $totalPedidos > 0) {
                            echo $totalPedidos . ($totalPedidos === 1 ? ' pedido' : ' pedidos');
                        } else {
                            echo 'Nenhum pedido';
                        }
                        ?>
                    </div>
                    <i class="fas fa-box-open"></i>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Carrinho</h4>
                        <p>
                            <?php
                            if ($quantidadeCarrinho > 0) {
                                echo $quantidadeCarrinho . ($quantidadeCarrinho === 1 ? ' item' : ' itens');
                            } else {
                                echo 'Vazio';
                            }
                            ?>
                        </p>
                    </div>
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>

            <h3 style="margin-bottom: 1rem; font-size: 1.3rem; border-left: 4px solid var(--primary); padding-left: 0.5rem;">Meus Dados Cadastrais</h3>
            <div class="info-grid">
                <div class="info-group">
                    <label>Nome Completo</label>
                    <p><?php echo $nomeUsuario; ?></p>
                </div>
                <div class="info-group">
                    <label>E-mail</label>
                    <p><?php echo $emailUsuario; ?></p>
                </div>
                <div class="info-group">
                    <label>Telefone / WhatsApp</label>
                    <p><?php echo $telefoneUsuario; ?></p>
                </div>
                <div class="info-group">
                    <label>Endereço de Entrega</label>
                    <p><?php echo $enderecoUsuario; ?></p>
                </div>
            </div>
        </main>
    </div>

    <footer>
        <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
    </footer>

</body>

</html>