<?php
// index.php - Roteador Central (Front Controller)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (file_exists(__DIR__ . '/config/database.php')) {
    require_once __DIR__ . '/config/database.php';
}

$url = isset($_GET['url']) ? $_GET['url'] : 'home';

switch ($url) {
    
    // ==========================================
    // --- 1. PÁGINAS PÚBLICAS / VITRINE --------
    // ==========================================
    case 'home':
        if (file_exists(__DIR__ . '/controller/HomeController.php')) {
            require_once __DIR__ . '/controller/HomeController.php';
            $controller = new HomeController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/HomeController.php' não foi encontrado.");
        }
        break;

    case 'futebol':
        if (file_exists(__DIR__ . '/controller/CategoriaController.php')) {
            require_once __DIR__ . '/controller/CategoriaController.php';
            $controller = new CategoriaController();
            $controller->futebol();
        } else {
            die("Erro crítico: O arquivo 'controller/CategoriaController.php' não foi encontrado.");
        }
        break;

    case 'formula1':
        if (file_exists(__DIR__ . '/controller/CategoriaController.php')) {
            require_once __DIR__ . '/controller/CategoriaController.php';
            $controller = new CategoriaController();
            $controller->formula1();
        } else {
            die("Erro crítico: O arquivo 'controller/CategoriaController.php' não foi encontrado.");
        }
        break;

    case 'contato':
        if (file_exists(__DIR__ . '/controller/ContatoController.php')) {
            require_once __DIR__ . '/controller/ContatoController.php';
            $controller = new ContatoController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/ContatoController.php' não foi encontrado.");
        }
        break;

    // ==========================================
    // --- 2. AUTENTICAÇÃO (LOGIN / LOGOUT / REGISTRO) -----
    // ==========================================
    case 'login':
        if (file_exists(__DIR__ . '/controller/LoginController.php')) {
            require_once __DIR__ . '/controller/LoginController.php';
            $controller = new LoginController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/LoginController.php' não foi encontrado.");
        }
        break;

    case 'cadastre-se':
        if (file_exists(__DIR__ . '/controller/LoginController.php')) {
            require_once __DIR__ . '/controller/LoginController.php';
            $controller = new LoginController();
            if (method_exists($controller, 'cadastro')) {
                $controller->cadastro();
            } else {
                $controller->index();
            }
        } else {
            die("Erro crítico: O arquivo 'controller/LoginController.php' não foi encontrado.");
        }
        break;

    case 'salvar-cadastro':
        if (file_exists(__DIR__ . '/controller/LoginController.php')) {
            require_once __DIR__ . '/controller/LoginController.php';
            $controller = new LoginController();
            $controller->salvarCadastro();
        } else {
            die("Erro crítico: O arquivo 'controller/LoginController.php' não foi encontrado.");
        }
        break;

    case 'autenticar':
        if (file_exists(__DIR__ . '/controller/LoginController.php')) {
            require_once __DIR__ . '/controller/LoginController.php';
            $controller = new LoginController();
            $controller->autenticar();
        } else {
            die("Erro crítico: O arquivo 'controller/LoginController.php' não foi encontrado.");
        }
        break;

    case 'logout':
        if (file_exists(__DIR__ . '/controller/LoginController.php')) {
            require_once __DIR__ . '/controller/LoginController.php';
            $controller = new LoginController();
            $controller->logout();
        } else {
            session_unset();
            session_destroy();
            header('Location: index.php?url=home');
            exit();
        }
        break;

    // ==========================================
    // --- 3. ÁREA EXCLUSIVA DO CLIENTE ---------
    // ==========================================
    case 'painel':
        if (file_exists(__DIR__ . '/controller/PainelController.php')) {
            require_once __DIR__ . '/controller/PainelController.php';
            $controller = new PainelController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/PainelController.php' não foi encontrado.");
        }
        break;

    case 'editar-perfil':
        if (file_exists(__DIR__ . '/controller/PainelController.php')) {
            require_once __DIR__ . '/controller/PainelController.php';
            $controller = new PainelController();
            $controller->editar();
        } else {
            die("Erro crítico: O arquivo 'controller/PainelController.php' não foi encontrado.");
        }
        break;

    case 'minhas-compras':
        if (file_exists(__DIR__ . '/controller/UsuarioController.php')) {
            require_once __DIR__ . '/controller/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->minhasCompras();
        } else {
            die("Erro crítico: O arquivo 'controller/UsuarioController.php' não foi encontrado.");
        }
        break;
    
    case 'alterar-avatar':
        if (file_exists(__DIR__ . '/controller/PainelController.php')) {
            require_once __DIR__ . '/controller/PainelController.php';
            $controller = new PainelController();
            $controller->alterarAvatar();
        } else {
            die("Erro crítico: O arquivo 'controller/PainelController.php' não foi encontrado.");
        }
        break;
        
    // ==========================================
    // --- 4. PAINEL DO ADMINISTRADOR (DASHBOARD)
    // ==========================================
    case 'painel-admin':
        if (file_exists(__DIR__ . '/controller/AdminController.php')) {
            require_once __DIR__ . '/controller/AdminController.php';
            $controller = new AdminController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/AdminController.php' não foi encontrado.");
        }
        break;

    case 'cadastro-admin':
        if (file_exists(__DIR__ . '/controller/AdminController.php')) {
            require_once __DIR__ . '/controller/AdminController.php';
            $controller = new AdminController();
            $controller->novoAdmin();
        }
        break;

    case 'salvar-admin':
        if (file_exists(__DIR__ . '/controller/AdminController.php')) {
            require_once __DIR__ . '/controller/AdminController.php';
            $controller = new AdminController();
            $controller->salvarAdmin();
        }
        break;

    case 'editar-perfil-admin':
        if (file_exists(__DIR__ . '/controller/AdminController.php')) {
            require_once __DIR__ . '/controller/AdminController.php';
            $controller = new AdminController();
            $controller->editarPerfil();
        } else {
            die("Erro crítico: O arquivo 'controller/AdminController.php' não foi encontrado.");
        }
        break;

    // ==========================================
    // --- 5. CRUD DE PRODUTOS (RESTRITO ADMIN) -
    // ==========================================
    case 'cadastro-produto':
        if (file_exists(__DIR__ . '/controller/ProdutoController.php')) {
            require_once __DIR__ . '/controller/ProdutoController.php';
            $controller = new ProdutoController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/ProdutoController.php' não foi encontrado.");
        }
        break;

    case 'salvar-produto':
        if (file_exists(__DIR__ . '/controller/ProdutoController.php')) {
            require_once __DIR__ . '/controller/ProdutoController.php';
            $controller = new ProdutoController();
            $controller->salvar();
        } else {
            die("Erro crítico: O arquivo 'controller/ProdutoController.php' não foi encontrado.");
        }
        break;

    case 'editar-produto':
        if (file_exists(__DIR__ . '/controller/ProdutoController.php')) {
            require_once __DIR__ . '/controller/ProdutoController.php';
            $controller = new ProdutoController();
            $controller->editar();
        } else {
            die("Erro crítico: O arquivo 'controller/ProdutoController.php' não foi encontrado.");
        }
        break;

    case 'atualizar-produto':
        if (file_exists(__DIR__ . '/controller/ProdutoController.php')) {
            require_once __DIR__ . '/controller/ProdutoController.php';
            $controller = new ProdutoController();
            $controller->atualizar();
        } else {
            die("Erro crítico: O arquivo 'controller/ProdutoController.php' não foi encontrado.");
        }
        break;

    case 'deletar-produto':
        if (file_exists(__DIR__ . '/controller/ProdutoController.php')) {
            require_once __DIR__ . '/controller/ProdutoController.php';
            $controller = new ProdutoController();
            $controller->deletar();
        } else {
            die("Erro crítico: O arquivo 'controller/ProdutoController.php' não foi encontrado.");
        }
        break;

    // ==========================================
    // --- 6. ROTAS DO CARRINHO DE COMPRAS ------
    // ==========================================
    case 'carrinho':
        if (file_exists(__DIR__ . '/controller/CarrinhoController.php')) {
            require_once __DIR__ . '/controller/CarrinhoController.php';
            $controller = new CarrinhoController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/CarrinhoController.php' não foi encontrado.");
        }
        break;

    case 'adicionar-ao-carrinho':
        if (file_exists(__DIR__ . '/controller/CarrinhoController.php')) {
            require_once __DIR__ . '/controller/CarrinhoController.php';
            $controller = new CarrinhoController();
            $controller->adicionar();
        } else {
            die("Erro crítico: O arquivo 'controller/CarrinhoController.php' não foi encontrado.");
        }
        break;

    case 'atualizar-carrinho':
        if (file_exists(__DIR__ . '/controller/CarrinhoController.php')) {
            require_once __DIR__ . '/controller/CarrinhoController.php';
            $controller = new CarrinhoController();
            $controller->atualizar();
        } else {
            die("Erro crítico: O arquivo 'controller/CarrinhoController.php' não foi encontrado.");
        }
        break;

    case 'remover-do-carrinho':
        if (file_exists(__DIR__ . '/controller/CarrinhoController.php')) {
            require_once __DIR__ . '/controller/CarrinhoController.php';
            $controller = new CarrinhoController();
            $controller->remover();
        } else {
            die("Erro crítico: O arquivo 'controller/CarrinhoController.php' não foi encontrado.");
        }
        break;

    // ==========================================
    // --- 6.1 ROTAS DE PAGAMENTO / CHECKOUT ----
    // ==========================================
    case 'pagamento':
    case 'finalizar-pedido':
        if (file_exists(__DIR__ . '/controller/PagamentoController.php')) {
            require_once __DIR__ . '/controller/PagamentoController.php';
            $controller = new PagamentoController();
            $controller->index();
        } else {
            die("Erro crítico: O arquivo 'controller/PagamentoController.php' não foi encontrado.");
        }
        break;

    case 'processar-pagamento':
        if (file_exists(__DIR__ . '/controller/PagamentoController.php')) {
            require_once __DIR__ . '/controller/PagamentoController.php';
            $controller = new PagamentoController();
            $controller->finalizar();
        } else {
            die("Erro crítico: O arquivo 'controller/PagamentoController.php' não foi encontrado.");
        }
        break;

    case 'pedido-concluido':
        // [NOVA ROTA MVC]: Intercepta a finalização e direciona o fluxo lógico para processamento do recibo
        if (file_exists(__DIR__ . '/controller/PagamentoController.php')) {
            require_once __DIR__ . '/controller/PagamentoController.php';
            $controller = new PagamentoController();
            $controller->pedidoConcluido();
        } else {
            die("Erro crítico: O arquivo 'controller/PagamentoController.php' não foi encontrado.");
        }
        break;

    // ==========================================
    // --- 7. ROTA PADRÃO - ERRO 404 ------------
    // ==========================================
    default:
        http_response_code(404);
        echo "<div style='text-align: center; margin-top: 100px; font-family: Arial, sans-serif;'>";
        echo "<h1 style='color: #ff120a; font-size: 64px; margin-bottom: 10px;'>404</h1>";
        echo "<h2 style='color: #050505;'>Página Não Encontrada!</h2>";
        echo "<p style='color: #555;'>A rota '<strong>" . htmlspecialchars($url) . "</strong>' não está mapeada no sistema GRID&GOL.</p>";
        echo "<br><a href='index.php' style='display: inline-block; padding: 10px 20px; background: #050505; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;'>Voltar para a Home</a>";
        echo "</div>";
        break;
}