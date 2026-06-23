<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina ?? 'GRID&GOL - Editar Perfil'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/painel-admin.css">
    <link rel="stylesheet" href="assets/css/adm-perfil.css">
    
    
</head>
<body>

    <div class="profile-wrapper">
        <a href="index.php?url=painel-admin" class="back-nav">
            <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
        </a>

        <div class="profile-card">
            <div class="profile-card-header">
                <h2>Meus Dados<span>.</span></h2>
                <p>Gerencie as credenciais administrativas do sistema corporativo GRID&GOL</p>
            </div>

            <div class="profile-card-body">
                
                <?php if (!empty($mensagemSucesso)): ?>
                    <div class="grid-alert grid-alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($mensagemSucesso); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensagemErro)): ?>
                    <div class="grid-alert grid-alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($mensagemErro); ?></span>
                    </div>
                <?php endif; ?>

                <form action="index.php?url=editar-perfil-admin" method="POST" autocomplete="off">
                    
                    <div class="grid-form-group">
                        <label for="nome">Nome Completo</label>
                        <div class="input-with-icon">
                            <input type="text" id="nome" name="nome" class="grid-input" 
                                   value="<?php echo htmlspecialchars($adminLogado['nome'] ?? ''); ?>" required>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="grid-form-group">
                        <label for="email">E-mail Institucional</label>
                        <div class="input-with-icon">
                            <input type="email" id="email" name="email" class="grid-input" 
                                   value="<?php echo htmlspecialchars($adminLogado['email'] ?? ''); ?>" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="grid-form-group">
                        <label for="nova_senha">Nova Senha de Acesso</label>
                        <div class="input-with-icon">
                            <input type="password" id="nova_senha" name="nova_senha" class="grid-input" 
                                   placeholder="••••••••">
                            <i class="fas fa-lock"></i>
                        </div>
                        <span class="input-help">
                            <i class="fas fa-info-circle"></i> Deixe este campo completamente vazio caso queira manter a sua senha atual. Mínimo de 6 caracteres.
                        </span>
                    </div>

                    <button type="submit" class="btn-grid-submit">
                        <i class="fas fa-save"></i> Atualizar Registro
                    </button>
                </form>

            </div>
        </div>
    </div>

</body>
</html>