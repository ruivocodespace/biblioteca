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