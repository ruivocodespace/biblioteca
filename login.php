<?php
// Iniciar sessão
session_start();

// Verificar conexão com o banco
require_once "config/conexao.php";

// Verificar se o formulário foi enviado (method POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  //Receber dados do formulário de login
  $username = mysqli_real_escape_string($conexao, $_POST["username"]); // Proteção contra sql injection
  $senha = $_POST["senha"];

  // Buscar o usuário no banco pelo email
  $sql = "SELECT * FROM usuarios WHERE username = '$username'";
  $resultado = mysqli_query($conexao, $sql);

  // Verificar se encontrou o usuário
  if ($usuario = mysqli_fetch_assoc($resultado)) {

    // Verificar se a senha está correta
    if ($senha === $usuario["senha"]) {

      // echo "<pre>";
      // print_r($usuario);
      // die;

      // Guardar dados do usuário na sessão
      $_SESSION["usuario_id"] = $usuario["id"];
      $_SESSION["usuario_nome"] = $usuario["nome"];
      $_SESSION["usuario_username"] = $usuario["username"];
      $_SESSION["usuario_email"] = $usuario["email"];
      $_SESSION["usuario_tipo"] = $usuario["tipo"];

      if ($_SESSION) {
        // Redirecionar para o dashboard
        header("Location: dashboard.php");
        exit;
        // Se a senha estiver incorreta
      } else {
        header("Location: login.php?erro=1");
        exit;
      }
      // Se o email não for encontrado no banco
    } else {
      header("Location: index.php?erro=1");
      exit;
    }
  }
}


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home page Biblioteca</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    body {
      background-image: url('uploads/fundo_login.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Bem-Vindo! Acesse sua conta.</h1>
    <form action="" method="post">
      <label class="user" for="username">Username:</label>
      <input
        type="text"
        id="username"
        name="username"
        required />
      <br>

      <br />
      <label class="password" for="password">Password:</label>
      <input
        type="password"
        id="senha"
        name="senha"
        required />
      <br />
      <button type="submit" class="btt_login">Login</button>
      <button class="btt_register" type="button" onclick="window.location.href='register.html'">Registrar</button>
    </form>
  </div>
</body>

</html>