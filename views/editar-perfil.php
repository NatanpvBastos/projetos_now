<?php
$nomeUsuario = isset($usuarioLogado['nome']) ? htmlspecialchars($usuarioLogado['nome']) : '';
$emailUsuario = isset($usuarioLogado['email']) ? htmlspecialchars($usuarioLogado['email']) : '';
$telefoneUsuario = isset($usuarioLogado['telefone']) ? htmlspecialchars($usuarioLogado['telefone']) : '';
$enderecoUsuario = isset($usuarioLogado['endereco']) ? htmlspecialchars($usuarioLogado['endereco']) : '';
$dataCadastro = isset($usuarioLogado['data_cadastro']) ? date('d/m/Y', strtotime($usuarioLogado['data_cadastro'])) : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - GRID&GOL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/perfil.css">
</head>

<body>
    <header>
        <div class="logo">GRID<span>&</span>GOL</div>
        <nav><a href="index.php">Início</a></nav>
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
                <li><a href="index.php?url=minhas-compras"><i class="fas fa-shopping-bag"></i> Minhas Compras</a></li>
                <li><a href="index.php?url=editar-perfil" class="active"><i class="fas fa-user-edit"></i> Editar Perfil</a></li>
                <li><a href="index.php?url=logout" style="color: var(--primary);"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
        </aside>
        <main class="content-area">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.6rem; border-left: 4px solid var(--primary); padding-left: 0.5rem; text-transform: uppercase;">Editar Meus Dados</h3>

            <?php if (!empty($mensagemErro)): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $mensagemErro; ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagemSucesso)): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $mensagemSucesso; ?></div>
            <?php endif; ?>

            <form action="index.php?url=editar-perfil" method="POST">
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" value="<?php echo $emailUsuario; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" class="form-control" value="<?php echo $nomeUsuario; ?>" required>
                </div>
                <div class="form-group">
                    <label>Telefone / WhatsApp</label>
                    <input type="text" name="telefone" class="form-control" value="<?php echo $telefoneUsuario; ?>" required>
                </div>
                <div class="form-group">
                    <label>Endereço de Entrega</label>
                    <input type="text" name="endereco" class="form-control" value="<?php echo $enderecoUsuario; ?>" required>
                </div>
                <button type="submit" class="btn-atualizar">Salvar Alterações</button>
            </form>
        </main>
    </div>
    <footer>
        <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
    </footer>
</body>

</html>