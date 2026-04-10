<?php
session_start();
require_once "includes/logado.php";
require_once "config/conexao.php";

if (isset($_GET["id"])) {

    $id = intval($_GET["id"]);

    // Buscar o nome da capa antes de apagar o livro
    $sql_busca = "SELECT capa FROM livros WHERE id = $id";
    $resultado = mysqli_query($conexao, $sql_busca);

    // Verifica se achou o livro
    if ($livro = mysqli_fetch_assoc($resultado)) {

        $nome_capa = $livro['capa'];
        $caminho_imagem = "uploads/capas/" . $nome_capa;

        // Apagar o arquivo de imagem da pasta
        // Só apaga se o arquivo existir e NÃO for a imagem padrão
        if ($nome_capa !== 'sem-nome.jpeg' && file_exists($caminho_imagem)) {
            unlink($caminho_imagem); // O unlink serve para deletar o arquivo físico (files)
        }

        // 3. Deletar do banco de dados
        $sql_delete = "DELETE FROM livros WHERE id = $id";

        if (mysqli_query($conexao, $sql_delete)) {
            // Sucesso! Volta para o dashboard
            header("Location: dashboard.php?sucesso=livro_deletado");
            exit;
        } else {
            // Caso falhe o banco de dados
            header("Location: dashboard.php?erro=Erro ao deletar do banco");
            exit;
        }
    } else {
        // Se o ID não existir no banco
        header("Location: dashboard.php?erro=Livro não encontrado");
        exit;
    }
} else {
    // Redirecionamento caso acessar sem passar o ID na URL
    header("Location: dashboard.php");
    exit;
}
