<?php
// views/cadastro-admin.php

// Trava de segurança para impedir que a View seja aberta diretamente pela URL sem passar pelo AdminController
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
    <link rel="stylesheet" href="assets/css/adm.css">
   
</head>
<body>

<div class="admin-container">
    <aside class="sidebar">
        <a href="index.php?url=painel-admin" class="brand">GRID<span>&</span>GOL</a>
        
        <p class="menu-label">Navegação Principal</p>
        <a href="index.php?url=painel-admin"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="index.php?url=cadastro-produto"><i class="fas fa-boxes"></i> Produtos (CRUD)</a>
        <a href="index.php?url=cadastro-admin" class="active"><i class="fas fa-user-shield"></i> Adicionar Admin</a>
        
        <p class="menu-label">Sessão</p>
        <a href="index.php" target="_blank"><i class="fas fa-eye"></i> Ver Loja Pública</a>
        <a href="index.php?url=logout" class="logout"><i class="fas fa-sign-out-alt"></i> Terminar Sessão</a>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h2>Gestão de Administradores</h2>
        </div>

        <div class="form-card">
            <h3><i class="fas fa-user-plus" style="color: #ff120a; margin-right: 8px;"></i> Cadastrar Novo Administrador</h3>

            <?php if (!empty($mensagemErro)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($mensagemErro); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensagemSucesso)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($mensagemSucesso); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?url=salvar-admin" method="POST">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" class="form-control" placeholder="Ex: João Silva" required>
                </div>

                <div class="form-group">
                    <label for="email">Endereço de E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Ex: administrador@gridgol.com" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha de Acesso</label>
                    <input type="password" id="senha" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>

                <button type="submit" class="btn-submit">Gravar Administrador</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>