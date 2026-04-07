# 📚 Sistema de Gerenciamento de Biblioteca (CRUD)

Bem-vindos ao repositório do nosso projeto! Este é um sistema CRUD (Create, Read, Update, Delete) desenvolvido em PHP e MySQL para o gerenciamento de livros em uma biblioteca.

Nosso objetivo é construir um sistema funcional, responsivo e que permita o upload de capas de livros, dividindo o trabalho de forma inteligente entre a equipe para otimizar o tempo.

---

## 🛠️ Tecnologias Utilizadas

- **Back-end:** PHP
- **Banco de Dados:** MySQL
- **Front-end:** HTML5, CSS3
- **Servidor Local:** XAMPP (Apache + MySQL)

---

## 🚀 Como rodar o projeto localmente

Para que o sistema funcione no computador de cada um, sigam estes passos rigorosamente:

1. **Inicie o Servidor:** Abra o XAMPP/WAMP e inicie os módulos **Apache** e **MySQL**.
2. **Posicione a Pasta:** Coloque a pasta `biblioteca` dentro do diretório do seu servidor local (ex: `C:\xampp\htdocs\biblioteca`).
3. **Configure o Banco de Dados:**
   - Acesse `http://localhost/phpmyadmin` no navegador.
   - Crie um banco de dados chamado `biblioteca_db`.
   - Importe o arquivo `banco/script.sql` (ou cole o código SQL diretamente na aba "SQL") para criar a tabela `livros`.
4. **Crie a pasta de Imagens:** Certifique-se de que a pasta `/uploads` existe na raiz do projeto (se não existir, crie-a manualmente).
5. **Acesse o Sistema:** Abra o navegador e digite `http://localhost/biblioteca`.

---

## 📂 Estrutura de Diretórios

Respeitem esta estrutura ao criar ou editar arquivos:

    /biblioteca
    │
    ├── /banco                -> Script SQL para criar o banco de dados
    │   └── script.sql
    │
    ├── /config               -> Configurações essenciais
    │   └── conexao.php       (Ponte de acesso ao banco usando PDO ou MySQLi)
    │
    ├── /includes             -> Partes do layout que se repetem
    │   ├── header.php        (Head do HTML, CSS linkado e Menu superior)
    │   └── footer.php        (Fechamento de tags e rodapé)
    │
    ├── /assets               -> Arquivos estáticos
    │   └── style.css         (Estilo do projeto inteiro)
    │
    ├── /uploads              -> Imagens salvas (Apenas a pasta vazia)
    │
    ├── index.php             -> (READ) Lista todos os livros em Cards
    ├── cadastrar.php         -> (CREATE) Formulário + Processamento + Upload
    ├── editar.php            -> (UPDATE) Formulário preenchido + Processamento
    └── excluir.php           -> (DELETE) Lógica para deletar e redirecionar

---

## 👥 Divisão de Tarefas (O Roteiro da Equipe)

### 🧑‍💻 Desenvolvedor 1: Back-end e Banco de Dados

**Foco:** Lógica pura e conexão com o banco.

- [ ] Escrever o SQL de criação no `/banco/script.sql`.
- [ ] Criar o script de conexão no `/config/conexao.php`.
- [ ] Criar a lógica de INSERT no arquivo `cadastrar.php` (receber o `$_POST`).
- [ ] Criar o script de upload da imagem recebendo o `$_FILES` e movendo para `/uploads`.
- [ ] Criar a query de DELETE no arquivo `excluir.php`.
- [ ] Criar a lógica de UPDATE no arquivo `editar.php`.

### 🎨 Desenvolvedor 2: Front-end (UI/UX)

**Foco:** Deixar bonito e responsivo (sem tocar na lógica do banco por enquanto).

- [ ] Criar o HTML base e separar no `/includes/header.php` e `footer.php`.
- [ ] Criar o design da vitrine no `index.php` usando layout de Cards (usar imagem, título e botões de editar/excluir).
- [ ] Criar o formulário bonito para `cadastrar.php` e `editar.php` (Lembrar dos inputs de text, number e file).
- [ ] Escrever o `/assets/style.css` usando Flexbox/Grid para garantir que funcione bem no celular.

### 🔧 Desenvolvedor 3: Integração e Testes

**Foco:** Unir o visual com o motor e garantir que o sistema não tenha bugs.

- [ ] Inserir o `enctype="multipart/form-data"` no formulário feito pelo Dev 2.
- [ ] Pegar o HTML dos Cards do Dev 2 e colocar dentro de um laço de repetição (`while`) no `index.php` para imprimir os dados do banco que o Dev 1 preparou.
- [ ] Garantir que o atributo `src` da tag `<img src="...">` aponte corretamente para a pasta `/uploads`.
- [ ] Adicionar confirmação no botão de excluir (`onclick="return confirm('Tem certeza?')"`).
- [ ] Fazer o cadastro de 5 livros reais para testar o sistema de ponta a ponta.
