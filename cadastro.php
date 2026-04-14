<?
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home page Biblioteca</title>
    <link rel="stylesheet" href="assets/style.css" />
  </head>  
  <body>
    <div class="container_b">
        <h2 class="cadastro">Cadastre-se</h2>    
            <form action="/register" method="post">
                <label class="nickname" for="new-username">Nickname:</label><br />
                    <input class="input-form" type="text" id="new-username" name="new-username" required /><br />
                <label class="pass" for="new-password">Senha:</label><br />
                    <input class="input-form" type="password" id="new-password" name="new-password" required /><br />      
                <label class="confirm-pass" for="confirm-password">Confirmar Senha:</label><br />
                    <input class="input-form" type="password" id="confirm-password" name="confirm-password" required /><br />
                <label class="name-1"for="name"> Nome Completo:</label><br />
                    <input class="input-form" type="text" id="name" name="name" required /><br />
                <label class="email-1" for="name">Email:</label><br />
                    <input class="input-form" type="email" id="Email" formaction="/email" /><br/>
                <label class="phone" for="name">Telefone:</label><br />
                    <input class="input-form" type="numbet" id="Telefone" formaction="/phone" /><br>
                <label class="age" type="checkbox" for="age">Maior de 18 anos?</label>
                    <input type="checkbox" id="terms" name="terms" value="accepted" required /><br>
                        <input class="btt-cadastro" type="submit" value="Registrar" />
                    <button class="btt-cadastro" type="button" onclick="window.location.href='index.html'">Página Inicial</button>
            </form>
        </div>
    </body>
</html>