<?php
// 1. Chama a conexão
require_once 'config/conexao.php';

// 2. Verifica se o ID do livro foi passado pela URL (ex: excluir.php?id=5)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 3. Primeiro, buscamos qual é a imagem desse livro no banco
        $sql_busca = "SELECT capa_caminho FROM livros WHERE id = ?";
        $stmt_busca = $pdo->prepare($sql_busca);
        $stmt_busca->execute([$id]);
        $livro = $stmt_busca->fetch(PDO::FETCH_ASSOC);

        // 4. Se o livro existir e tiver uma imagem salva, apagamos o arquivo físico
        if ($livro && !empty($livro['capa_caminho'])) {
            // Verifica se o arquivo realmente existe na pasta antes de tentar apagar
            if (file_exists($livro['capa_caminho'])) {
                unlink($livro['capa_caminho']); // Essa função do PHP deleta o arquivo!
            }
        }

        // 5. Agora sim, deletamos o registro do banco de dados
        $sql_delete = "DELETE FROM livros WHERE id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$id]);

        // 6. Redireciona o usuário de volta para a tela inicial
        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao excluir o livro: " . $e->getMessage() . "</p>";
    }
} else {
    // Se tentarem acessar a página sem passar um ID, volta para o index
    header("Location: index.php");
    exit;
}
?>