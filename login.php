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
