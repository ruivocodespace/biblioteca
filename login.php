<?php
// Iniciar sessão
session_start();

// Verificar conexão com o banco
require_once "config/conexao.php";

// Verificar se o formulário foi enviado (method POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //Receber dados do formulário de login
    $email = mysqli_real_escape_string($conexao, $_POST["email"]); // Proteção contra sql injection
    $senha = $_POST["senha"];

    // Buscar o usuário no banco pelo email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conexao, $sql);

    // Verificar se encontrou o usuário
    if ($usuario = mysqli_fetch_assoc($resultado)) {

        // Verificar se a senha está correta
        if (password_verify($senha, $usuario["senha"])) {

            // Guardar dados do usuário na sessão
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["usuario_email"] = $usuario["email"];
            $_SESSION["usuario_tipo"] = $usuario["tipo"];

            if ($_SESSION["usuario_tipo"] == 'admin') {

                // Redirecionar para o dashboard
                header("Location: dashboard.php");
                exit;
            } elseif ($_SESSION["usuario_tipo"] == 'user') {

                // Redirecionar para o dashboard
                header("Location: dashboard.php");
                exit;
            }
            // Se a senha estiver incorreta
        } else {
            header("Location: index.php?erro=1");
            exit;
        }
        // Se o email não for encontrado no banco
    } else {
        header("Location: index.php?erro=1");
        exit;
    }
} else {
    // Se tentar acessar login.php direto pela barra de endereços
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home page Biblioteca</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <style>
    body {
        background-image: url('img/fundo_login.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
  </style></p>
  <body>
    <div class="container">
      <h1>Bem-Vindo! Acesse sua conta.</h1>
      <form action="home.html" method="post">
        <label class="user" for="username">Username:</label>
          <input type="text" id="username" name="username" required /><br>
            <br />
        <label class="password" for="password">Password:</label>
          <input type="password" id="password" name="password" required />
            <br />
        <button class="btt_login" type="submit">Login</button>
          <button class="back" type="button" onclick="window.location.href='index.html'">Esqueceu a senha?</button>
        <h3 class="font_tela_login">Não tem uma conta?</h3>
          <button class="btt_register"type="button" onclick="window.location.href='register.html'">Registrar</button>
            <button class="back" type="button" onclick="window.location.href='index.html'">Página Inicial</button>
      </form> 
    </div>  
  </body>
</html>
