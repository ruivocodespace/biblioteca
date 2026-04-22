<?php
session_start();
require_once "config/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = mysqli_real_escape_string($conexao, trim($_POST["username"]));
    $senha    = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE username = '$username' LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        // ✅ CORREÇÃO: Usar password_verify() para comparar com hash
        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["usuario_id"]       = $usuario["id"];
            $_SESSION["usuario_nome"]     = $usuario["nome"];
            $_SESSION["usuario_username"] = $usuario["username"];
            $_SESSION["usuario_email"]    = $usuario["email"];
            $_SESSION["usuario_tipo"]     = $usuario["tipo"];

            // Redireciona conforme o tipo
            if ($usuario["tipo"] === "admin") {
                header("Location: homepage_admin.php");
            } else {
                header("Location: user_pages/dashboard_user.php");
            }
            exit;
        } else {
            // Senha incorreta
            header("Location: login.php?erro=senha_invalida");
            exit;
        }
    } else {
        // Usuário não encontrado
        header("Location: login.php?erro=usuario_nao_encontrado");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Biblioteca On-line</title>
    <link rel="stylesheet" href="assets/style.css" />
</head>
<body class="pag-login">
    <div class="login-wrapper">
        <h1>Bem-Vindo! Acesse sua conta.</h1>

        <!-- ✅ Exibe mensagens de erro baseadas na URL -->
        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-erro">
                <?php
                switch ($_GET['erro']) {
                    case 'senha_invalida':          echo "Senha incorreta. Tente novamente."; break;
                    case 'usuario_nao_encontrado':  echo "Usuário não encontrado."; break;
                    case 'campos_vazios':           echo "Preencha todos os campos."; break;
                    default:                        echo "Erro ao fazer login. Tente novamente.";
                }
                ?>
            </div>
        <?php endif; ?>

        <form class="login-form" action="" method="post">
            <div class="form-group">
                <label class="label-form" for="username">Username:</label>
                <input type="text" id="username" name="username" class="input-form" required placeholder="Seu nome de usuário">
            </div>
            <div class="form-group">
                <label class="label-form" for="senha">Password:</label>
                <input type="password" id="senha" name="senha" class="input-form" required placeholder="Sua senha">
            </div>
            <button type="submit" class="btn btn-primario">Entrar</button>
            <button type="button" class="btn btn-secundario" onclick="window.location.href='cadastro_usuario.php'">Criar Conta</button>
        </form>
    </div>
</body>
</html>