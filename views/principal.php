<?php
// views/principal.php
// A variável $produtos e $tituloPagina vêm diretamente do Controller
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : "GRID&GOL - Home"; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/alerta.css">
</head>
<body>

<header>
    <div class="logo">GRID&GOL</div>
    <nav>
        <a href="index.php">Início</a>
        <a href="index.php?url=futebol">Futebol</a>
        <a href="index.php?url=formula1">Fórmula 1</a>
        <a href="index.php?url=contato">Contato</a>
    </nav>
    <div class="user-actions">
        <?php if (isset($_SESSION['usuario_nome'])): ?>
            <a href="index.php?url=painel" class="icon"><i class="fas fa-user"></i> Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></a>
            <a href="index.php?url=logout" class="icon"><i class="fas fa-sign-out-alt"></i> Sair</a>
        <?php else: ?>
            <a href="index.php?url=login" class="icon"><i class="fas fa-user"></i> Login</a>
        <?php endif; ?>
        <a href="index.php?url=carrinho" class="icon"><i class="fas fa-shopping-cart"></i>
            <span><?php echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0; ?></span>
        </a>
    </div>
</header>

<section class="hero-slider">
    <div class="slide active" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)), url('assets/img/hero-bg.jpg');">
        <div class="hero-content">
            <h1>GRID & GOL</h1>
            <p>A velocidade das pistas e a paixão dos relvados na melhor loja de artigos desportivos premium.</p>
            <a href="#vitrine" class="btn-hero">Ver Coleção</a>
        </div>
    </div>
</section>

<?php if (isset($_SESSION['sucesso_compra'])): ?>
    <div class="alert-success-checkout">
        <i class="fas fa-check-circle"></i>
        <span><?php echo htmlspecialchars($_SESSION['sucesso_compra']); ?></span>
    </div>
    <?php unset($_SESSION['sucesso_compra']); // Destrói para sumir ao recarregar a página ?>
<?php endif; ?>

<main class="container" id="vitrine">
    <h2 class="section-title">Produtos em Destaque</h2>
    
    <div class="product-grid">
        <?php if(!empty($produtos)): ?>
            <?php foreach($produtos as $produto): ?>
                <div class="product-card">
                    
                    <div class="product-image-wrapper">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="assets/img/produtos/<?php echo $produto['imagem']; ?>" class="product-image" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        <?php else: ?>
                            <img src="assets/img/produtos/sem-foto.jpg" class="product-image" alt="Imagem indisponível">
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <span class="product-category">
                            <?php echo htmlspecialchars($produto['nome_marca'] ?? ($produto['nome_categoria'] ?? 'Oficial')); ?>
                        </span>
                        
                        <h3 class="product-title"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        
                        <p class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        
                        <div class="product-price-action">
                            <span class="price">
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </span>
                            
                            <a href="index.php?url=adicionar-ao-carrinho&id=<?php echo $produto['id_produto']; ?>" class="btn-add-cart" title="Adicionar ao Carrinho">
                                <i class="fas fa-cart-plus"></i>
                            </a>
                        </div>
                        
                        <span class="stock-info">
                            <?php echo $produto['estoque'] > 0 ? "Em estoque: " . $produto['estoque'] : "<span style='color:#ff120a;'>Esgotado</span>"; ?>
                        </span>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #888888;">
                <i class="fas fa-box-open" style="font-size: 54px; margin-bottom: 20px; color: var(--primary);"></i>
                <p style="font-size: 1.1rem;\">Nenhum produto cadastrado no momento. Novidades em breve!</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
</footer>

</body>
</html>