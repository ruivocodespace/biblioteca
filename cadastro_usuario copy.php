<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    // Redireciona para a página inicial (ou login) e encerra a execução
    header("Location: index.php");
    exit;
}
require_once "config/conexao.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = mysqli_real_escape_string($conexao, $_POST["nome"]);
    $username = mysqli_real_escape_string($conexao, $_POST["username"]);
    $email = mysqli_real_escape_string($conexao, $_POST["email"]);
    $telefone = mysqli_real_escape_string($conexao, $_POST["telefone"]);
    $senha = $_POST["senha"];
    $confirma_senha = $_POST["confirma_senha"];
    $tipo = 'user';

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conexao, $sql);
    
    if (mysqli_num_rows($resultado) > 0) {
        header("Location: cadastro_usuario.php?erro=email_existe"); exit;
    } else if ($confirma_senha !== $senha) {
        header("Location: cadastro_usuario.php?erro=senhas_diferentes"); exit;
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sqlInsert = "INSERT INTO usuarios (nome, username, email, telefone, senha, tipo) VALUES ('$nome', '$username', '$email', '$telefone', '$senhaHash', '$tipo')";
        
        if (mysqli_query($conexao, $sqlInsert)) {
            header("Location: index.php?sucesso=1"); exit;
        } else {
            header("Location: cadastro_usuario.php?erro=geral"); exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cadastro de Usuário</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="pag-cadastro">

    <div class="cadastro-layout">
        
        <!-- BOX ESQUERDA: Lista de Usuários -->
        <aside class="cadastro-sidebar">
            <h2 style="text-align:center; margin-bottom:20px;">Usuários Recentes</h2>
            <div class="sidebar-list">
                <?php
                $sqlList = "SELECT id, nome, username, email, telefone FROM usuarios ORDER BY id DESC LIMIT 10";
                $resList = mysqli_query($conexao, $sqlList);
                if ($resList && mysqli_num_rows($resList) > 0):
                    while ($user = mysqli_fetch_assoc($resList)):
                ?>
                <div class="list-item">
                    <div class="list-info" style="flex:1;">
                        <h3 class="book-title"><?php echo htmlspecialchars($user['nome']); ?></h3>
                        <p class="book-author">@<?php echo htmlspecialchars($user['username']); ?></p>
                        <p class="book-meta"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
                <?php endwhile; else: ?>
                <p style="text-align:center;color:#666;">Nenhum usuário cadastrado ainda.</p>
                <?php endif; ?>
            </div>
        </aside>

        <!-- BOX DIREITA: Formulário -->
        <main class="cadastro-form-wrapper">
            <h1>Cadastrar Usuário</h1>

            <?php if (isset($_GET['erro'])): ?>
                <div class="alert alert-erro">
                    <?php
                    $errType = $_GET['erro'];
                    if ($errType === 'email_existe') echo 'Este e-mail já está cadastrado.';
                    elseif ($errType === 'senhas_diferentes') echo 'As senhas não conferem.';
                    else echo 'Erro ao cadastrar.';
                    ?>
                </div>
            <?php endif; ?>

            <form class="cadastro-form" action="cadastro_usuario.php" method="POST">
                <div class="form-group">
                    <label class="label-form">Nome Completo:</label>
                    <input type="text" name="nome" class="input-form" required placeholder="Ex: João Silva">
                </div>
                <div class="form-group">
                    <label class="label-form">Nome de Usuário:</label>
                    <input type="text" name="username" class="input-form" required placeholder="Ex: joaosilva">
                </div>
                <div class="form-group">
                    <label class="label-form">E-mail:</label>
                    <input type="email" name="email" class="input-form" required placeholder="exemplo@email.com">
                </div>
                <div class="form-group">
                    <label class="label-form">Telefone:</label>
                    <input type="tel" name="telefone" class="input-form" required placeholder="(00) 00000-0000">
                </div>
                <div class="form-group">
                    <label class="label-form">Senha:</label>
                    <input type="password" name="senha" class="input-form" required minlength="6" placeholder="Mínimo 6 caracteres">
                </div>
                <div class="form-group">
                    <label class="label-form">Confirmar Senha:</label>
                    <input type="password" name="confirma_senha" class="input-form" required minlength="6" placeholder="Digite a senha novamente">
                </div>
                <button type="submit" class="btn btn-primario">Salvar</button>
                <button type="reset" class="btn btn-secundario">Excluir</button>
            </form>
            <p style="text-align:center; margin-top:15px; font-size:14px; color:#666;">
                <a href="homepage_admin.php" style="color:rgba(98,102,122,0.808);">Voltar</a>
            </p>
        </main>

    </div>
</body>
</html>