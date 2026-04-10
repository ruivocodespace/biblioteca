<?php
session_start();
require_once "config/conexao.php"; // Chama a conexão com o banco

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recebe formulário e evita SQL Injection
    $nome = mysqli_real_escape_string($conexao, $_POST["nome"]);
    $nickname = mysqli_real_escape_string($conexao, $_POST["nickname"]);
    $email = mysqli_real_escape_string($conexao, $_POST["email"]);
    $telefone = mysqli_real_escape_string($conexao, $_POST["telefone"]);
    $senha = $_POST["senha"];

    // Criptografar senha)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verificar se o e-mail ou o nickname já estão cadastrados
    $sql_busca = "SELECT id FROM usuarios WHERE email = '$email' OR nickname = '$nickname'";
    $resultado_busca = mysqli_query($conexao, $sql_busca);

    if (mysqli_num_rows($resultado_busca) > 0) {
        // Se já tiver cadastro, devolve para a página inicial com erro
        $erro = "E-mail ou Nickname já estão em uso. Tente outro.";
        header("Location: index.php?erro=" . urlencode($erro));
        exit;
    } else {
        // Salvar o novo usuário no banco de dados
        $sql_salvar = "INSERT INTO usuarios (nome, nickname, email, telefone, senha) 
                       VALUES ('$nome', '$nickname', '$email', '$telefone', '$senha_hash')";

        if (mysqli_query($conexao, $sql_salvar)) {
            // Sucesso! Redireciona para o index
            $sucesso = "Conta criada com sucesso! Você já pode fazer login.";
            header("Location: index.php?sucesso=" . urlencode($sucesso));
            exit;
        } else {
            // Erro no banco
            $erro = "Erro ao cadastrar no banco de dados: " . mysqli_error($conexao);
            header("Location: index.php?erro=" . urlencode($erro));
            exit;
        }
    }
} else {
    // Se tentarem acessar o arquivo direto
    header("Location: index.php");
    exit;
}
