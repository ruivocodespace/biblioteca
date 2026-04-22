<?php
session_start();
require_once "config/conexao.php";

// 🔒 Segurança (restringir apenas a admins)
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') { header("Location: index.php"); exit; }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Recebe e sanitiza os dados
    $id     = isset($_POST['id']) && !empty($_POST['id']) ? (int)$_POST['id'] : 0;
    $titulo = mysqli_real_escape_string($conexao, trim($_POST['titulo']));
    $autor  = mysqli_real_escape_string($conexao, trim($_POST['autor']));
    $ano    = mysqli_real_escape_string($conexao, trim($_POST['ano']));
    $genero = mysqli_real_escape_string($conexao, trim($_POST['genero']));
    
    $erro = "";
    $nome_capa = "sem-nome.jpeg"; // Padrão caso não envie imagem

    // 2. Validação básica
    if (empty($titulo) || empty($autor) || empty($ano) || empty($genero)) {
        $erro = "Preencha todos os campos obrigatórios.";
    }

    // 3. Lógica de Upload da Imagem
    $pasta_destino = "uploads/capas/";
    
    // Se estiver editando e não enviou nova imagem, busca o nome da atual no banco
    if ($id > 0 && empty($_FILES['capa']['name'])) {
        $res_old = mysqli_query($conexao, "SELECT capa FROM livros WHERE id = $id");
        if ($row = mysqli_fetch_assoc($res_old)) {
            $nome_capa = $row['capa'];
        }
    }

    // Se enviou uma nova imagem
    if (isset($_FILES['capa']) && $_FILES['capa']['size'] > 0 && empty($erro)) {
        $extensao = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extensao, $extensoes_permitidas)) {
            $erro = "Formato de imagem inválido. Use JPG, PNG ou WebP.";
        } else {
            // Gera nome único
            $novo_nome_imagem = uniqid() . "." . $extensao;
            
            // Move a imagem
            if (move_uploaded_file($_FILES['capa']['tmp_name'], $pasta_destino . $novo_nome_imagem)) {
                $nome_capa = $novo_nome_imagem;
            } else {
                $erro = "Erro ao salvar a imagem no servidor.";
            }
        }
    }

    // 4. Verifica duplicidade de título (apenas para novos cadastros)
    if (empty($erro) && $id === 0) {
        $sql_check = "SELECT id FROM livros WHERE titulo = '$titulo'";
        if (mysqli_num_rows(mysqli_query($conexao, $sql_check)) > 0) {
            $erro = "Já existe um livro cadastrado com este título.";
        }
    }

    // 5. Executa SQL (INSERT ou UPDATE)
    if (empty($erro)) {
        if ($id > 0) {
            // Atualiza
            $sql = "UPDATE livros SET titulo='$titulo', autor='$autor', ano='$ano', genero='$genero', capa='$nome_capa' WHERE id=$id";
        } else {
            // Cria
            $sql = "INSERT INTO livros (titulo, autor, ano, genero, capa) VALUES ('$titulo', '$autor', '$ano', '$genero', '$nome_capa')";
        }

        if (mysqli_query($conexao, $sql)) {
            $msg = $id > 0 ? "Livro atualizado com sucesso!" : "Livro cadastrado com sucesso!";
            header("Location: homepage_admin.php?sucesso=" . urlencode($msg));
            exit;
        } else {
            $erro = "Erro no banco: " . mysqli_error($conexao);
        }
    }

    // Redireciona com erro
    header("Location: homepage_admin.php?erro=" . urlencode($erro));
    exit;
}

// Se acessou diretamente sem POST
header("Location: homepage_admin.php");
exit;
?>