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
        <form action="cadastro_usuario.php" method="post">
            <label class="nickname" for="new-username">Nickname:</label><br />
            <input class="input-form" type="text" id="username" name="username" required /><br />

            <label class="name-1" for="name"> Nome:</label><br />
            <input class="input-form" type="text" id="name" name="nome" required /><br />

            <label class="email-1" for="name">Email:</label><br />
            <input class="input-form" type="email" id="Email" name="email" /><br />

            <label class="phone" for="name">Telefone:</label><br />
            <input class="input-form" type="number" id="Telefone" name="telefone" /><br>

            <label class="pass" for="new-password">Senha:</label><br />
            <input class="input-form" type="password" id="new-password" name="senha" required /><br />

            <label class="confirm-pass" for="confirma_senha">Confirmar Senha:</label><br />
            <input class="input-form" type="password" id="confirm-password" name="confirma_senha" required /><br />

            <input class="btt-cadastro" type="submit" value="Registrar" />
        </form>
    </div>
</body>

</html>