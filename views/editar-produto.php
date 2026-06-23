<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : "GRID&GOL - Editar Produto"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/produto.css">
</head>
<body>

<header style="background: #050505; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div class="logo" style="font-size: 1.5rem; font-weight: bold; letter-spacing: 1px;">GRID<span style="color: #ff120a;">&</span>GOL (ADMIN)</div>
    <nav>
        <a href="index.php?url=cadastro-produto" style="background: #ff120a; color: #fff; padding: 8px 16px; border-radius: 4px; font-weight: bold; text-decoration: none;"><i class="fas fa-arrow-left"></i> Voltar ao Gerenciamento</a>
    </nav>
</header>

<main class="form-wrapper">
    <div class="form-card" style="max-width: 600px; margin: 2rem auto;">
        <h2>Editar Produto</h2>
        <p style="margin-bottom: 1.5rem; color: #666; font-size: 0.9rem;">Atualize as informações desejadas do produto abaixo.</p>

        <form action="index.php?url=atualizar-produto" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produto" value="<?php echo (int)$produto['id_produto']; ?>">
            <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($produto['imagem']); ?>">
            
            <div class="form-group">
                <label for="nome">Nome do Produto</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
            </div>

            <div class="form-group">
                <label for="id_categoria">Categoria</label>
                <select id="id_categoria" name="id_categoria" class="form-control" required>
                    <?php if (!empty($categorias) && is_array($categorias)): ?>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($cat['id_categoria'] == $produto['id_categoria']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="preco">Preço (R$)</label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0" class="form-control" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
                </div>
                <div class="form-group col">
                    <label for="estoque">Estoque Atual</label>
                    <input type="number" id="estoque" name="estoque" min="0" class="form-control" value="<?php echo (int)$produto['estoque']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição Completa</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="4" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Imagem Cadastrada Atualmente</label><br>
                <img src="assets/img/produtos/<?php echo !empty($produto['imagem']) ? htmlspecialchars($produto['imagem']) : 'default.jpg'; ?>" style="width: 85px; height: 85px; object-fit: cover; margin-top: 0.5rem; margin-bottom: 1rem; border-radius: 4px; border: 1px solid #ddd;">
                <br>
                <label for="imagem">Substituir Foto do Produto (Opcional)</label>
                <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn-salvar" style="background: #ff120a; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;"><i class="fas fa-save"></i> Salvar Alterações</button>
            <a href="index.php?url=cadastro-produto" style="display: block; text-align: center; margin-top: 1.2rem; color: #666; text-decoration: none; font-size: 0.9rem;">Cancelar e Voltar</a>
        </form>
    </div>
</main>

<footer>
    <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
</footer>

</body>
</html>