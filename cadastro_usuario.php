<?php
// Iniciar a sessão
session_start();

// Verificar conexão com o banco
require_once "config/conexao.php";

// Verificar se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome     = mysqli_real_escape_string($conexao, $_POST["nome"]);
    $username = mysqli_real_escape_string($conexao, $_POST["username"]);
    $email    = mysqli_real_escape_string($conexao, $_POST["email"]);
    $telefone = mysqli_real_escape_string($conexao, $_POST["telefone"]);
    $senha = $_POST["senha"];
    $confirma_senha = $_POST["confirma_senha"];
    $tipo = 'user';

    // Verificar se o email já existe
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        header("Location: cadastro.php?erro=email_existe");
        exit;
    } else {
        if ($confirma_senha === $senha) {
            // Criptografar a senha
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            // Inserir o novo usuário
            $sql = "INSERT INTO usuarios (nome, username, email, telefone, senha, tipo) VALUES ('$nome', '$username', '$email', '$telefone', '$senhaHash', '$tipo')";
        } else {
            header("Location: cadastro.php?erro=senhas_diferentes");
            exit;
        }
    }
    if (mysqli_query($conexao, $sql)) {
        header("Location: cadastro.php?sucesso=1");
        exit;
    } else {
        header("Location: cadastro.php?erro=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Mensagens de Feedback -->
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-error">
            <?php
            $erro = $_GET['erro'];
            if ($erro === 'email_existe') echo 'Este e-mail já está cadastrado.';
            elseif ($erro === 'senhas_diferentes') echo 'As senhas não conferem.';
            else echo 'Erro ao cadastrar usuário. Tente novamente.';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Usuário cadastrado com sucesso!</div>
    <?php endif; ?>

    <div class="main-layout">
        
        <!-- BOX ESQUERDA: INFORMAÇÕES / USUÁRIOS RECENTES -->
        <aside class="livros-box">
            <h2 class="box-title">Usuários Recentes</h2>
            <div class="books-grid">
                <?php
                // Buscar últimos usuários cadastrados (opcional)
                $sql = "SELECT id, nome, username, email, telefone FROM usuarios ORDER BY id DESC LIMIT 10";
                $res = mysqli_query($conexao, $sql);
                if ($res && mysqli_num_rows($res) > 0):
                    while ($user = mysqli_fetch_assoc($res)):
                ?>
                <div class="book-card">
                    <div class="book-info" style="flex:1;">
                        <h3 class="book-title"><?php echo htmlspecialchars($user['nome']); ?></h3>
                        <p class="book-author">@<?php echo htmlspecialchars($user['username']); ?></p>
                        <p class="book-meta"><?php echo htmlspecialchars($user['email']); ?></p>
                        <p class="book-meta"><?php echo htmlspecialchars($user['telefone']); ?></p>
                    </div>
                </div>
                <?php 
                    endwhile;
                else:
                ?>
                <p style="text-align:center;color:#666;font-size:14px;">Nenhum usuário cadastrado ainda.</p>
                <?php endif; ?>
            </div>
        </aside>

        <!-- FORMULÁRIO DE CADASTRO (DIREITA) -->
        <main class="form-container">
            <h1 class="cadastro-book">Cadastrar Usuário</h1>
            
            <form class="login-form" action="cadastro_usuario.php" method="POST">
                
                <div class="form-group">
                    <label class="font_tela_login">Nome Completo:</label>
                    <input type="text" name="nome" class="input-form" required maxlength="100" placeholder="Ex: João Silva">
                </div>

                <div class="form-group">
                    <label class="font_tela_login">Nome de Usuário:</label>
                    <input type="text" name="username" class="input-form" required maxlength="50" placeholder="Ex: joaosilva">
                </div>

                <div class="form-group">
                    <label class="font_tela_login">E-mail:</label>
                    <input type="email" name="email" class="input-form" required maxlength="100" placeholder="exemplo@email.com">
                </div>

                <div class="form-group">
                    <label class="font_tela_login">Telefone:</label>
                    <input type="tel" name="telefone" class="input-form" required maxlength="20" placeholder="(00) 00000-0000">
                </div>

                <div class="form-group">
                    <label class="font_tela_login">Senha:</label>
                    <input type="password" name="senha" class="input-form" required minlength="6" placeholder="Mínimo 6 caracteres">
                </div>

                <div class="form-group">
                    <label class="font_tela_login">Confirmar Senha:</label>
                    <input type="password" name="confirma_senha" class="input-form" required minlength="6" placeholder="Digite a senha novamente">
                </div>

                <button type="submit" class="btt-cadastro">Cadastrar Usuário</button>
            </form>
            
            <p style="text-align:center;margin-top:15px;font-size:14px;color:#666;">
                Já tem conta? <a href="login.php" style="color:rgba(98,102,122,0.808);text-decoration:none;">Fazer login</a>
            </p>
        </main>

    </div>
    // <script>
    //     // Máscara simples para telefone (opcional)
    //     document.querySelector('input[name="telefone"]').addEventListener('input', function(e) {
    //         let value = e.target.value.replace(/\D/g, '');
    //         if (value.length <= 11) {
    //             value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
    //             value = value.replace(/(\d{5})(\d)/, '$1-$2');
    //             e.target.value = value;
    //         }
    //     });
    // </script>

</body>
</html>
