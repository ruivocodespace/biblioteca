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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cadastro de Livro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Mensagens de Feedback -->
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars(urldecode($_GET['erro'])); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Livro cadastrado com sucesso!</div>
    <?php endif; ?>

    <div class="main-layout">        
        <!-- BOX ESQUERDA: LIVROS CADASTRADOS -->
        <aside class="livros-box">
            <h2 class="box-title">Livros Cadastrados</h2>
            <div class="books-grid">
                <?php
                $sql = "SELECT id, titulo, autor, genero, ano, capa FROM livros ORDER BY id DESC LIMIT 50";
                $res = mysqli_query($conexao, $sql);
                while ($livro = mysqli_fetch_assoc($res)):
                ?>
                <div class="book-card">
                    <img src="uploads/capas/<?php echo htmlspecialchars($livro['capa'] ?? 'sem-nome.jpeg'); ?>" 
                         alt="Capa" class="book-cover"
                         onerror="this.src='uploads/capas/sem-nome.jpeg'">
                    <div class="book-info">
                        <h3 class="book-title"><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                        <p class="book-author"><?php echo htmlspecialchars($livro['autor']); ?></p>
                        <p class="book-meta"><?php echo htmlspecialchars($livro['ano']); ?> | <?php echo htmlspecialchars($livro['genero']); ?></p>
                        <div class="book-actions">
                            <button class="btn-small btn-edit">Editar</button>
                            <button class="btn-small btn-delete" onclick="return confirm('Excluir?')">Excluir</button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </aside>

        <!-- FORMULÁRIO À DIREITA -->
        <main class="form-container">
            <h1 class="cadastro-book">Cadastrar Livro</h1>
            <form class="login-form" action="processa-cadastro.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="font_tela_login">Título:</label>
                    <input type="text" name="titulo" class="input-form" required maxlength="255">
                </div>
                <div class="form-group">
                    <label class="font_tela_login">Autor:</label>
                    <input type="text" name="autor" class="input-form" required maxlength="100">
                </div>
                <div class="form-group">
                    <label class="font_tela_login">Ano:</label>
                    <input type="number" name="ano" class="input-form" min="1000" max="9999" required>
                </div>
                <div class="form-group">
                    <label class="font_tela_login">Gênero:</label>
                    <input type="text" name="genero" class="input-form" required maxlength="50">
                </div>
                <div class="form-group">
                    <label class="font_tela_login">Capa:</label>
                    <input type="file" name="capa" class="input-form" accept="image/jpeg,image/png,image/webp">
                </div>
                <button type="submit" class="btt-cadastro">Registrar</button>
            </form>
        </main>
    </div>
</body>
</html>
