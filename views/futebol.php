<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : "GRID&GOL - Futebol"; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <div class="logo">GRID&GOL</div>
    <nav>
        <a href="index.php">Início</a>
        <a href="index.php?url=futebol" style="color: var(--primary);">Futebol</a>
        <a href="index.php?url=formula1">Fórmula 1</a>
        <a href="index.php?url=contato">Contato</a>
    </nav>
    <div class="user-actions">
        <?php if (isset($_SESSION['usuario_nome'])): ?>
            <a href="index.php?url=painel" class="icon"><i class=\"fas fa-user\"></i> Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></a>
            <a href="index.php?url=logout" class="icon"><i class="fas fa-sign-out-alt"></i> Sair</a>
        <?php else: ?>
            <a href="index.php?url=login" class="icon"><i class="fas fa-user"></i></a>
        <?php endif; ?>
        <a href="#" class="icon"><i class="fas fa-shopping-cart"></i></a>
    </div>
</header>

<section class="hero-slider">
    <div class="slide active" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('assets/img/banner-futebol.jpg');">
        <div class="hero-content">
            <h1>Mantos Sagrados</h1>
            <p>As camisolas oficiais das maiores equipas e seleções do mundo estão aqui.</p>
        </div>
    </div>
</section>

<main class="container">
    <h2 class="section-title">Coleção de Futebol</h2>
    
    <div class="product-grid">
        <?php if(!empty($produtos)): ?>
            <?php foreach($produtos as $produto): ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="assets/img/produtos/<?php echo $produto['imagem']; ?>" class="product-image" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    </div>
                    
                    <div class="product-info">
                        <span class="product-category">
                            <?php echo htmlspecialchars($produto['nome_marca'] ?? 'Oficial'); ?>
                        </span>
                        <h3 class="product-title"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        
                        <div class="product-price-action">
                            <span class="price">
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </span>
                            <button class="btn-add-cart" title="Adicionar ao Carrinho">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                        
                        <span class="stock-info">
                            <?php echo $produto['estoque'] > 0 ? "Em estoque: " . $produto['estoque'] : "<span style='color:red;'>Esgotado</span>"; ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--gray);">
                <i class="fas fa-volleyball-ball" style="font-size: 48px; margin-bottom: 15px;"></i>
                <p>Nenhum produto de futebol disponível no momento.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
</footer>

</body>
</html>