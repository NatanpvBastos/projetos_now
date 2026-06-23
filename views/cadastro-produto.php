<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : "GRID&GOL - Gerenciar Produtos"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/produto.css">
    <link rel="stylesheet" href="assets/css/tabela.css">
    <link rel="stylesheet" href="assets/css/preventivo.css">
</head>
<body>

<header style="background: #050505; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div class="logo" style="font-size: 1.5rem; font-weight: bold; letter-spacing: 1px;">GRID<span style="color: #ff120a;">&</span>GOL (ADMIN)</div>
    <nav>
        <a href="index.php?url=painel-admin" style="background: #ff120a; color: #fff; padding: 8px 16px; border-radius: 4px; font-weight: bold; text-decoration: none;"><i class="fas fa-arrow-left"></i> Painel Admin</a>
    </nav>
</header>

<div class="crud-container">
    
    <main class="form-wrapper" style="margin: 0; max-width: 100%;">
        <div class="form-card" style="max-width: 600px; margin: 0 auto;">
            <h2 style="margin-top: 0; margin-bottom: 1.5rem;"><i class="fas fa-box-open" style="color: #ff120a;"></i> Cadastrar Novo Produto</h2>

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

            <form action="index.php?url=salvar-produto" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome do Produto</label>
                    <input type="text" id="nome" name="nome" class="form-control" placeholder="Ex: Camisola Oficial Benfica 2026" required>
                </div>

                <div class="form-group">
                    <label for="id_categoria">Categoria</label>
                    <select id="id_categoria" name="id_categoria" class="form-control" required>
                        <option value="">-- Selecione uma Categoria --</option>
                        <?php if (!empty($categorias) && is_array($categorias)): ?>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>">
                                    <?php echo htmlspecialchars($cat['nome'] ?? $cat['nome_categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhuma categoria encontrada no sistema</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="preco">Preço (R$)</label>
                    <input type="number" id="preco" name="preco" class="form-control" step="0.01" placeholder="Ex: 89.90" required>
                </div>

                <div class="form-group">
                    <label for="estoque">Quantidade em Stock</label>
                    <input type="number" id="estoque" name="estoque" class="form-control" placeholder="Ex: 15" required>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição do Produto</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="4" placeholder="Detalhes sobre o produto, tamanho, tecido, etc."></textarea>
                </div>

                <div class="form-group">
                    <label for="imagem">Imagem do Produto</label>
                    <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn-submit"><i class="fas fa-plus"></i> Inserir Produto</button>
            </form>
        </div>
    </main>

    <section class="table-section">
        <h2 style="margin-top: 0;"><i class="fas fa-list" style="color: #ff120a;"></i> Produtos Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Stock</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($produtos) && is_array($produtos)): ?>
                    <?php foreach ($produtos as $prod): 
                        // Descobre dinamicamente qual índice de ID está populado no array
                        $idAtual = $prod['id_produto'] ?? ($prod['id'] ?? 0);
                    ?>
                        <tr>
                            <td>
                                <img src="assets/img/produtos/<?php echo !empty($prod['imagem']) ? htmlspecialchars($prod['imagem']) : 'sem-foto.jpg'; ?>" class="img-tabela" alt="Produto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            </td>
                            <td><strong><?php echo htmlspecialchars($prod['nome']); ?></strong></td>
                            <td><?php echo htmlspecialchars($prod['nome_categoria'] ?? 'Sem Categoria'); ?></td>
                            <td>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo (int)$prod['estoque']; ?> un</td>
                            <td>
                                <a href="index.php?url=editar-produto&id=<?php echo $idAtual; ?>" class="btn-action btn-edit" title="Editar"><i class="fas fa-edit"></i></a>
                                <a href="index.php?url=deletar-produto&id=<?php echo $idAtual; ?>" class="btn-action btn-delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #777; padding: 2rem;">Nenhum produto cadastrado até ao momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<footer>
    <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
</footer>

</body>
</html>