# 📚 Sistema de Biblioteca (Versão Power Duo)

Este projeto é um CRUD completo com sistema de login e upload de imagens, desenvolvido para gerenciar o acervo de uma biblioteca de forma simples e eficiente.

---

## 🛠️ Tecnologias

- **Linguagem:** PHP 8+
- **Banco de Dados:** MySQL
- **Estilo:** CSS3 (Flexbox/Grid)
- **Estrutura:** HTML5

---

## 👥 Divisão de Responsabilidades (Sprint Dupla)

### 🧑‍💻 Desenvolvedor 1: Back-end e Segurança

- [ ] **DB:** Criar tabelas `livros` (id, titulo, autor, genero, ano, capa) e `usuarios` (id, usuario, senha).
- [ ] **Login:** Criar `login.php` que valida usuário e inicia a `$_SESSION`.
- [ ] **Lógica CRUD:** Criar os processos de INSERT, UPDATE e DELETE.
- [ ] **Upload:** Criar a função de validação e movimentação da imagem para `/uploads`.

### 🎨 Desenvolvedor 2: Front-end e Integração

- [ ] **Telas:** Criar `index.php` (Login), `dashboard.php` (Listagem), `form_livro.php`.
- [ ] **Estilo:** Criar o `style.css` global (Layout Responsivo).
- [ ] **Integração:** Inserir os comandos PHP do Dev 1 dentro do HTML.
- [ ] **Segurança de Tela:** Adicionar o verificador de sessão no topo das páginas privadas.

---

## 📂 Estrutura de Pastas

    /biblioteca
    │
    ├── /banco
    │   └── script.sql        (Scripts de criação das tabelas)
    │
    ├── /config
    │   └── conexao.php       (Conexão PDO/MySQLi)
    │
    ├── /assets
    │   └── style.css         (Design responsivo)
    │
    ├── /uploads              (Pasta para as capas dos livros)
    │
    ├── index.php             (Tela de Login - Porta de entrada)
    ├── dashboard.php         (Vitrine de livros após o login)
    ├── cadastrar.php         (Lógica + Form de novo livro)
    ├── editar.php            (Lógica + Form de edição)
    ├── excluir.php           (Lógica de deleção)
    └── logout.php            (Encerra a sessão)

---

## 🚀 Como Iniciar

1.  Importe o `script.sql` no seu MySQL.
2.  Insira um usuário manualmente na tabela `usuarios` para conseguir logar.
3.  Configure o `conexao.php` com seus dados locais (localhost, root, senha).
4.  Crie a pasta `/uploads` com permissão de escrita.

---

## 💡 Dica de Velocidade para nós

Não tente fazer arquivos separados para "processar_cadastro" e "formulario_cadastro". Façam o código PHP de processamento no **topo** do próprio arquivo do formulário. Isso diminui a quantidade de arquivos e facilita a correção de erros!
