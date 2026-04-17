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

    // Verifica se a senha está correta
    if ($senha === $usuario["senha"]) {

      // Guarda dados do usuário na sessão
      $_SESSION["usuario_id"] = $usuario["id"];
      $_SESSION["usuario_nome"] = $usuario["nome"];
      $_SESSION["usuario_username"] = $usuario["username"];
      $_SESSION["usuario_email"] = $usuario["email"];
      $_SESSION["usuario_tipo"] = $usuario["tipo"];

      // Redirecionar para o dashboard
      header("Location: dashboard.php");
      exit;
    } else {
      // Se a senha estiver incorreta
      header("Location: login.php?erro=senha");
      exit;
    }
  } else {
    // Se o usuário não for encontrado no banco
    header("Location: login.php?erro=user");
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home page Biblioteca</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body {
      background-image: url('uploads/fundo_login.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      display: flex;
      margin: 0;
      align-items: center;
      justify-content: center;
      height: 100vh;

    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Acessar biblioteca</h1>
    <p style="margin-top: 2px;">Faça login com seus dados.</p>

    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'user'): ?>
      <p style="color: #ff3333; font-weight: bold; text-align: center; margin-bottom: 10px;">Usuário não encontrado!</p>
    <?php endif; ?>

    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'senha'): ?>
      <p style="color: #ff3333; font-weight: bold; text-align: center; margin-bottom: 10px;">Senha incorreta!</p>
    <?php endif; ?>

    <div class="div-form">
      <form action="" method="post">

        <div class="lb_login">
          <label class="user" for="username">
            USERNAME
          </label>
          <input
            type="text"
            id="username"
            name="username"
            required
            placeholder="Digite seu nome de usuario">
        </div>
        <div class="lb_login">
          <label class="password" for="senha" class="block text-gray-700 font-medium mb-2">
            SENHA
          </label>
          <input
            type="password"
            id="senha"
            name="senha"
            required
            placeholder="Digite sua senha">
        </div>

        <button type="submit" class="btt_login">
          Entrar
        </button>
      </form>
    </div>
  </div>
</body>

</html>