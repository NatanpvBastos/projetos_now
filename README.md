<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto Grid Gol</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body{
            background:#f4f4f4;
            color:#333;
            line-height:1.6;
        }

        header{
            background:#0d6efd;
            color:white;
            text-align:center;
            padding:40px 20px;
        }

        header h1{
            font-size:3rem;
        }

        header p{
            margin-top:10px;
            font-size:1.2rem;
        }

        nav{
            background:#084298;
            padding:15px;
            text-align:center;
        }

        nav a{
            color:white;
            text-decoration:none;
            margin:0 15px;
            font-weight:bold;
        }

        nav a:hover{
            text-decoration:underline;
        }

        .container{
            width:90%;
            max-width:1200px;
            margin:30px auto;
        }

        section{
            background:white;
            padding:25px;
            margin-bottom:20px;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        h2{
            color:#0d6efd;
            margin-bottom:15px;
        }

        h3{
            margin-top:15px;
            color:#084298;
        }

        ul{
            margin-left:20px;
        }

        .mvc{
            display:grid;
            grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
            margin-top:20px;
        }

        .card{
            background:#f8f9fa;
            border-left:5px solid #0d6efd;
            padding:20px;
            border-radius:8px;
        }

        .estrutura{
            background:#212529;
            color:#ffffff;
            padding:20px;
            border-radius:8px;
            overflow:auto;
            font-family: Consolas, monospace;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        table th,
        table td{
            border:1px solid #ddd;
            padding:12px;
            text-align:left;
        }

        table th{
            background:#0d6efd;
            color:white;
        }

        footer{
            background:#212529;
            color:white;
            text-align:center;
            padding:20px;
            margin-top:30px;
        }

        .badge{
            display:inline-block;
            background:#198754;
            color:white;
            padding:5px 12px;
            border-radius:20px;
            margin-top:10px;
        }
    </style>
</head>
<body>

<header>
    <h1>⚽ Projeto Grid Gol</h1>
    <p>Sua paixão pelo futebol em um só lugar.</p>
    <span class="badge">Arquitetura MVC</span>
</header>

<nav>
    <a href="#sobre">Sobre</a>
    <a href="#objetivos">Objetivos</a>
    <a href="#mvc">MVC</a>
    <a href="#estrutura">Estrutura</a>
    <a href="#funcionalidades">Funcionalidades</a>
    <a href="#tecnologias">Tecnologias</a>
    <a href="#equipe">Equipe</a>
</nav>

<div class="container">

    <section id="sobre">
        <h2>📖 Sobre o Projeto</h2>

        <p>
            O Projeto Grid Gol é uma aplicação web desenvolvida para fins acadêmicos,
            simulando uma loja virtual especializada em produtos relacionados ao futebol.
        </p>

        <p>
            O sistema utiliza a arquitetura MVC (Model-View-Controller),
            promovendo organização, manutenção e escalabilidade do código.
        </p>
    </section>

    <section id="objetivos">
        <h2>🎯 Objetivos</h2>

        <ul>
            <li>Desenvolver uma loja virtual funcional.</li>
            <li>Aplicar os conceitos da arquitetura MVC.</li>
            <li>Utilizar boas práticas de programação.</li>
            <li>Trabalhar operações CRUD.</li>
            <li>Desenvolver habilidades em desenvolvimento web.</li>
        </ul>
    </section>

    <section id="mvc">
        <h2>🏗️ Arquitetura MVC</h2>

        <div class="mvc">

            <div class="card">
                <h3>📦 Model</h3>
                <p>
                    Responsável pelo gerenciamento dos dados e comunicação com o banco de dados.
                </p>

                <ul>
                    <li>Produtos</li>
                    <li>Usuários</li>
                    <li>Pedidos</li>
                    <li>Categorias</li>
                </ul>
            </div>

            <div class="card">
                <h3>🎨 View</h3>
                <p>
                    Responsável pela interface gráfica apresentada ao usuário.
                </p>

                <ul>
                    <li>Página Inicial</li>
                    <li>Catálogo</li>
                    <li>Login</li>
                    <li>Carrinho</li>
                </ul>
            </div>

            <div class="card">
                <h3>⚙️ Controller</h3>
                <p>
                    Responsável por processar requisições e intermediar Model e View.
                </p>

                <ul>
                    <li>ProdutoController</li>
                    <li>UsuarioController</li>
                    <li>PedidoController</li>
                </ul>
            </div>

        </div>
    </section>

    <section id="estrutura">
        <h2>📂 Estrutura do Projeto</h2>

        <div class="estrutura">
<pre>
Projeto_grid_gol/
│
├── app/
│   ├── controllers/
│   │   ├── ProdutoController.php
│   │   ├── UsuarioController.php
│   │   └── PedidoController.php
│   │
│   ├── models/
│   │   ├── Produto.php
│   │   ├── Usuario.php
│   │   └── Pedido.php
│   │
│   └── views/
│       ├── home/
│       ├── produtos/
│       ├── usuarios/
│       └── pedidos/
│
├── public/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── index.php
│
├── config/
│   └── database.php
│
├── routes/
│   └── web.php
│
└── README.md
</pre>
        </div>

    </section>

    <section id="funcionalidades">
        <h2>🛒 Funcionalidades</h2>

        <h3>Produtos</h3>
        <ul>
            <li>Cadastro de produtos</li>
            <li>Listagem de produtos</li>
            <li>Edição de produtos</li>
            <li>Exclusão de produtos</li>
        </ul>

        <h3>Usuários</h3>
        <ul>
            <li>Cadastro</li>
            <li>Login</li>
            <li>Controle de acesso</li>
        </ul>

        <h3>Pedidos</h3>
        <ul>
            <li>Carrinho de compras</li>
            <li>Finalização de pedidos</li>
            <li>Histórico de compras</li>
        </ul>

    </section>

    <section id="tecnologias">
        <h2>💻 Tecnologias Utilizadas</h2>

        <table>
            <tr>
                <th>Tecnologia</th>
                <th>Finalidade</th>
            </tr>

            <tr>
                <td>HTML5</td>
                <td>Estrutura da aplicação</td>
            </tr>

            <tr>
                <td>CSS3</td>
                <td>Estilização e responsividade</td>
            </tr>

            <tr>
                <td>JavaScript</td>
                <td>Interatividade</td>
            </tr>

            <tr>
                <td>PHP</td>
                <td>Back-end</td>
            </tr>

            <tr>
                <td>MySQL</td>
                <td>Banco de Dados</td>
            </tr>

            <tr>
                <td>Git</td>
                <td>Controle de versão</td>
            </tr>

            <tr>
                <td>GitHub</td>
                <td>Hospedagem do projeto</td>
            </tr>
        </table>
    </section>

    <section>
        <h2>🗄️ Banco de Dados</h2>

        <p>Principais tabelas do sistema:</p>

        <ul>
            <li>usuarios</li>
            <li>produtos</li>
            <li>categorias</li>
            <li>pedidos</li>
            <li>itens_pedido</li>
        </ul>
    </section>

    <section id="equipe">
        <h2>👥 Equipe</h2>

        <table>
            <tr>
                <th>Nome</th>
                <th>Função</th>
            </tr>

            <tr>
                <td>Aluno 1</td>
                <td>Desenvolvedor</td>
            </tr>

            <tr>
                <td>Aluno 2</td>
                <td>Banco de Dados</td>
            </tr>

            <tr>
                <td>Aluno 3</td>
                <td>Front-end</td>
            </tr>
        </table>
    </section>

</div>

<footer>
    <p>Projeto Grid Gol © 2026</p>
    <p>Projeto Acadêmico - Desenvolvimento Web com MVC</p>
</footer>

</body>
</html>
