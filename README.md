# ⚽ Projeto Grid Gol

> Sistema de loja virtual desenvolvido para fins acadêmicos utilizando a arquitetura MVC (Model-View-Controller).

---

## 📖 Sobre o Projeto

O **Projeto Grid Gol** é uma aplicação web desenvolvida para simular uma loja virtual especializada em produtos relacionados ao futebol.

O sistema foi construído seguindo o padrão **MVC**, promovendo uma melhor organização do código, separação de responsabilidades e facilidade de manutenção.

---

## 🎯 Objetivos

- Desenvolver uma loja virtual funcional.
- Aplicar conceitos da arquitetura MVC.
- Implementar operações CRUD.
- Utilizar banco de dados relacional.
- Praticar versionamento com Git e GitHub.
- Aplicar boas práticas de desenvolvimento web.

---

## 🏗️ Arquitetura MVC

### 📦 Model

Responsável pelo gerenciamento e manipulação dos dados.

**Exemplos:**

- Produto
- Usuário
- Pedido
- Categoria

---

### 🎨 View

Responsável pela interface gráfica apresentada ao usuário.

**Exemplos:**

- Página inicial
- Catálogo de produtos
- Carrinho de compras
- Tela de login

---

### ⚙️ Controller

Responsável por receber as requisições e realizar a comunicação entre Model e View.

**Exemplos:**

- ProdutoController
- UsuarioController
- PedidoController

---

## 📂 Estrutura do Projeto

```text
Projeto_grid_gol/
│
│   ├── Controllers/
│   │   ├── ProdutoController.php
│   │   ├── UsuarioController.php
│   │   └── PedidoController.php
│   │
│   ├── Model/
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
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
├── help/
│   ├── PainelHelper.php
│   
│   
│
├── config/
│   └── database.php
│── Index.php
│    
│
└── README.md
```

---

## 🛒 Funcionalidades

### Produtos

- Cadastro de produtos
- Edição de produtos
- Exclusão de produtos
- Listagem de produtos

### Usuários

- Cadastro
- Login
- Controle de acesso

### Pedidos

- Carrinho de compras
- Finalização de pedidos
- Histórico de compras

---

## 💻 Tecnologias Utilizadas

| Tecnologia | Descrição |
|------------|------------|
| HTML5 | Estrutura das páginas |
| CSS3 | Estilização |
| JavaScript | Interatividade |
| PHP | Back-end |
| MySQL | Banco de Dados |
| Git | Controle de versão |
| GitHub | Hospedagem do código |

---

## 🗄️ Banco de Dados

### Principais Tabelas

- usuarios
- produtos
- categorias
- pedidos
- itens_pedido

---

## 🚀 Instalação

### Clonar o Repositório

```bash
git clone https://github.com/seu-usuario/Projeto_grid_gol.git
```

### Acessar o Projeto

```bash
cd Projeto_grid_gol
```

### Configurar Banco de Dados

1. Criar um banco MySQL.
2. Importar o arquivo SQL.
3. Configurar as credenciais em:

```php
config/database.php
```

### Executar o Projeto

```bash
php -S localhost:8000
```

Acesse:

```text
http://localhost:8000
```

---

## 📚 Conceitos Aplicados

- Arquitetura MVC
- Programação Orientada a Objetos (POO)
- CRUD
- Banco de Dados Relacional
- Controle de Rotas
- Responsividade
- Versionamento Git

---

## 👨‍🎓 Projeto Acadêmico

Projeto desenvolvido para fins educacionais visando a aplicação prática dos conceitos de desenvolvimento web e arquitetura de software.

---

## 👥 Equipe

| Integrante | Função |
|------------|---------|
| Natanael | Desenvolvedor |
| Gustavo | Aquilles | Desenvolvedor | 
| Juan Willian | Banco de Dados |
| Natanael | Front-end |
| Tamiris | Design|
| Kauan | Design |
| Juan William | Documentação |

---

## 📄 Licença GPL 3.0

Este projeto foi desenvolvido exclusivamente para fins acadêmicos.

---

<div align="center">

### ⚽ Grid Gol

**Sua paixão pelo futebol em um só lugar.**

</div>
