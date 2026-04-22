<?php
session_start();
require_once "config/conexao.php";
require_once "includes/logado.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo  = mysqli_real_escape_string($conexao, $_POST["titulo"]);
    $autor   = mysqli_real_escape_string($conexao, $_POST["autor"]);
    $genero  = mysqli_real_escape_string($conexao, $_POST["genero"]);
    $ano     = mysqli_real_escape_string($conexao, $_POST["ano"]);
    $nome_capa = "sem-nome.jpeg";
    $erro = ""; 

    $pasta_destino = "uploads/capas/";

    if (isset($_FILES['capa']) && $_FILES['capa']['size'] > 0) {
        $extensao = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($extensao, $extensoes_permitidas)) {
            $erro = "Formato de imagem inválido.";
        } else {
            $novo_nome_imagem = uniqid() . "." . $extensao;
            if (move_uploaded_file($_FILES['capa']['tmp_name'], $pasta_destino . $novo_nome_imagem)) {
                $nome_capa = $novo_nome_imagem;
            } else { $erro = "Erro ao salvar a imagem."; }
        }
    }

    if (empty($erro)) {
        $sql_busca = "SELECT id FROM livros WHERE titulo = '$titulo'";
        $resultado_busca = mysqli_query($conexao, $sql_busca);
        if (mysqli_num_rows($resultado_busca) > 0) {
            $erro = "Já existe um livro com este título.";
        } else {
            $sql_salvar = "INSERT INTO livros (titulo, autor, genero, ano, capa) VALUES ('$titulo', '$autor', '$genero', '$ano', '$nome_capa')";
            if (mysqli_query($conexao, $sql_salvar)) {
                header("Location: dashboard.php?sucesso=1"); exit;
            } else { $erro = "Erro ao salvar no banco."; }
        }
    }
    if (!empty($erro)) { header("Location: processa-cadastro.php?erro=" . urlencode($erro)); exit; }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cadastro de Livro</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="pag-cadastro">

    <div class="cadastro-layout">
        
        <!-- BOX ESQUERDA: Livros Cadastrados -->
        <aside class="cadastro-sidebar">
            <h2 style="text-align:center; margin-bottom:20px;">Livros Cadastrados</h2>
            <div class="sidebar-list">
                <?php
                $sql = "SELECT id, titulo, autor, genero, ano, capa FROM livros ORDER BY id DESC LIMIT 50";
                $res = mysqli_query($conexao, $sql);
                while ($livro = mysqli_fetch_assoc($res)):
                ?>
                <div class="list-item">
                    <img src="uploads/capas/<?php echo htmlspecialchars($livro['capa'] ?? 'sem-nome.jpeg'); ?>" alt="Capa" onerror="this.src='uploads/capas/sem-nome.jpeg'">
                    <div class="list-info">
                        <h3 class="book-title"><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                        <p class="book-author"><?php echo htmlspecialchars($livro['autor']); ?></p>
                        <p class="book-meta"><?php echo htmlspecialchars($livro['ano']); ?> | <?php echo htmlspecialchars($livro['genero']); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </aside>

        <!-- BOX DIREITA: Formulário de Cadastro -->
        <main class="cadastro-form-wrapper">
            <h1>Cadastrar Livro</h1>
            
            <?php if (isset($_GET['erro'])): ?>
                <div class="alert alert-erro"><?php echo htmlspecialchars(urldecode($_GET['erro'])); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-sucesso">Livro cadastrado com sucesso!</div>
            <?php endif; ?>

            <form class="cadastro-form" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="label-form">Título:</label>
                    <input type="text" name="titulo" class="input-form" required maxlength="255">
                </div>
                <div class="form-group">
                    <label class="label-form">Autor:</label>
                    <input type="text" name="autor" class="input-form" required maxlength="100">
                </div>
                <div class="form-group">
                    <label class="label-form">Ano:</label>
                    <input type="number" name="ano" class="input-form" min="1000" max="9999" required>
                </div>
                <div class="form-group">
                    <label class="label-form">Gênero:</label>
                    <input type="text" name="genero" class="input-form" required maxlength="50">
                </div>
                <div class="form-group">
                    <label class="label-form">Capa do Livro:</label>
                    <input type="file" name="capa" class="input-form" accept="image/jpeg,image/png,image/webp">
                </div>
                <button type="submit" class="btn btn-primario">Salvar</button>
                <button type="reset" class="btn btn-secundario">Excluir</button>
            </form>
            <p style="text-align:center; margin-top:15px; font-size:14px; color:#666;">
                <a href="homepage_admin.php" style="color:rgba(98,102,122,0.808);">Voltar</a>            
        </main>

    </div>
</body>
</html>