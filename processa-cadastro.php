<?php
// Inciar sessão
session_start();

// Verificações
require_once "config/conexao.php";
require_once "includes/logado.php";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo  = mysqli_real_escape_string($conexao, $_POST["titulo"]);
    $autor  = mysqli_real_escape_string($conexao, $_POST["autor"]);
    $genero  = mysqli_real_escape_string($conexao, $_POST["genero"]);
    $ano  = mysqli_real_escape_string($conexao, $_POST["ano"]);

    // Imagem padrão caso não seja inserida a capa
    $nome_capa = "sem-nome.jpeg";
    $erro = ""; // Inicializa a variável de erro

    // LÓGICA DE UPLOAD DA IMAGEM DE CAPA

    // Caminho da pasta de uploads
    $pasta_destino = "uploads/capas/";

    // Verifica se foi enviada uma nova imagem
    if (isset($_FILES['capa']) && $_FILES['capa']['size'] > 0) {

        // Pega extensão
        $extensao = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extensao, $extensoes_permitidas)) {
            $erro = "Formato de imagem inválido.";
        } else {
            // Gera nome único para a imagem
            $novo_nome_imagem = uniqid() . "." . $extensao;

            // Move imagem para pasta uploads
            if (move_uploaded_file($_FILES['capa']['tmp_name'], $pasta_destino . $novo_nome_imagem)) {
                $nome_capa = $novo_nome_imagem;
            } else {
                $erro = "Erro ao salvar a imagem.";
            }
        }
    }

    // Se não houve erro no upload:
    if (empty($erro)) {

        // Verificar se já existe livro com mesmo título
        $sql_busca = "SELECT id FROM livros WHERE titulo = '$titulo'";
        $resultado_busca = mysqli_query($conexao, $sql_busca);

        if (mysqli_num_rows($resultado_busca) > 0) {
            $erro = "Já existe um livro cadastrado com este título.";
        } else {
            // INSERT
            $sql_salvar = "INSERT INTO livros (titulo, autor, genero, ano, capa) 
            VALUES ('$titulo', '$autor', '$genero', '$ano', '$nome_capa')";

            // Executa o salvamento
            if (mysqli_query($conexao, $sql_salvar)) {
                header("Location: dashboard.php?sucesso=1");
                exit;
            } else {
                $erro = "Erro ao salvar no banco: " . mysqli_error($conexao);
            }
        }
    }
    // Redirecionamento de erro
    if (!empty($erro)) {
        header("Location: processa-cadastro.php?erro=" . urlencode($erro));
        exit;
    }
}
