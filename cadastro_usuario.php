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
