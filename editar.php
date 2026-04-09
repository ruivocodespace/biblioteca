<?php
// 1. Chama a conexão
require_once 'config/conexao.php';

// ==============================================================================
// PARTE 1: PROCESSA A ATUALIZAÇÃO (Quando o usuário clica em "Salvar")
// ==============================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe o ID do livro (que virá de um input hidden no formulário do Dev 2)
    $id = $_POST['id']; 
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ano_publicacao = $_POST['ano_publicacao'];

    try {
        // Verifica se o usuário selecionou uma nova imagem no input file
        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            
            $extensao = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
            $extensoes_permitidas = ["jpg", "jpeg", "png", "webp"];

            if (in_array($extensao, $extensoes_permitidas)) {
                $novo_nome_imagem = uniqid() . "." . $extensao;
                $caminho_completo = "uploads/" . $novo_nome_imagem;

                if (move_uploaded_file($_FILES['capa']['tmp_name'], $caminho_completo)) {
                    
                    // IMPORTANTE: Antes de atualizar, busca a imagem antiga e deleta da pasta
                    $sql_busca = "SELECT capa_caminho FROM livros WHERE id = ?";
                    $stmt_busca = $pdo->prepare($sql_busca);
                    $stmt_busca->execute([$id]);
                    $livro_antigo = $stmt_busca->fetch(PDO::FETCH_ASSOC);

                    if ($livro_antigo && !empty($livro_antigo['capa_caminho'])) {
                        if (file_exists($livro_antigo['capa_caminho'])) {
                            unlink($livro_antigo['capa_caminho']); 
                        }
                    }

                    // Atualiza o banco incluindo o novo caminho da imagem
                    $sql = "UPDATE livros SET titulo = ?, autor = ?, ano_publicacao = ?, capa_caminho = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$titulo, $autor, $ano_publicacao, $caminho_completo, $id]);
                }
            } else {
                echo "<p style='color:red;'>Formato de imagem inválido.</p>";
            }
        } else {
            // SITUAÇÃO 2: O usuário NÃO enviou imagem nova. Atualiza apenas os textos.
            $sql = "UPDATE livros SET titulo = ?, autor = ?, ano_publicacao = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titulo, $autor, $ano_publicacao, $id]);
        }

        echo "<p style='color:green;'>Livro atualizado com sucesso!</p>";
        // header("Location: index.php"); // Descomente para redirecionar depois do front-end pronto

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao atualizar: " . $e->getMessage() . "</p>";
    }
}

// ==============================================================================
// PARTE 2: BUSCA OS DADOS (Para preencher o formulário quando a página carrega)
// ==============================================================================
$livro_atual = null;

// Verifica se a URL tem um ID (ex: editar.php?id=5)
if (isset($_GET['id'])) {
    $id_get = $_GET['id'];
    
    $sql = "SELECT * FROM livros WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_get]);
    $livro_atual = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Se digitarem um ID que não existe na URL, volta pro início
    if (!$livro_atual) {
        header("Location: index.php");
        exit;
    }
}
?>