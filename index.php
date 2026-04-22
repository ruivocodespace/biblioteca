<?php
session_start();
require_once "config/conexao.php";

$termo = isset($_GET['search']) ? mysqli_real_escape_string($conexao, trim($_GET['search'])) : '';
$filtro_sql = $termo ? "WHERE titulo LIKE '%$termo%' OR autor LIKE '%$termo%' OR genero LIKE '%$termo%'" : "";
$sql = "SELECT id, titulo, autor, genero, ano, capa FROM livros $filtro_sql ORDER BY id DESC";
$resultado = mysqli_query($conexao, $sql);
$livros = [];
if ($resultado) {
    while ($row = mysqli_fetch_assoc($resultado)) { $livros[] = $row; }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca On-line</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="pag-index">

    <header class="index-header">
        <div class="index-container">
            <h1 class="logo" style="font-size: 28px; font-weight: bold;">📚 Biblioteca On-line</h1>
            
            <form class="index-search" action="index.php" method="GET">
                <input type="text" name="search" placeholder="Buscar livros, autores ou gêneros..." value="<?php echo htmlspecialchars($termo); ?>">
                <button type="submit" class="btn btn-primario" style="width: auto; margin:0; padding: 10px 20px;">🔍</button>
            </form>

            <div class="auth-buttons">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <?php 
                        // Define o destino baseado no tipo salvo na sessão
                        $url_painel = ($_SESSION['usuario_tipo'] === 'admin') ? 'homepage_admin.php' : 'user_pages/homepage_user.php';
                    ?>
                    
                    <span class="user-greeting">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?>!</span>
                    
                    <!-- ✅ Botão Painel com redirecionamento dinâmico -->
                    <a href="<?php echo $url_painel; ?>" class="btn btn-secundario" style="width: auto; padding: 8px 15px; margin:0;">Painel</a>
                    
                    <a href="logout.php" class="btn btn-secundario" style="width: auto; padding: 8px 15px; margin:0; border-color:#dc3545; color:#dc3545;">Sair</a>
                    
                <?php else: ?>
                    <a href="login.php" class="btn btn-secundario" style="width: auto; padding: 8px 15px; margin:0;">Login</a>
                    <a href="cadastro.php" class="btn btn-primario" style="width: auto; padding: 8px 15px; margin:0;">Cadastre-se</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="index-main">
        <section class="hero">
            <h2>Bem-vindo à Biblioteca On-line</h2>
            <p>Explore nossa coleção de livros digitais. Leia onde e quando quiser.</p>
        </section>

        <section class="books-section">
            <div class="section-header" style="display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 2px solid rgba(98,102,122,0.2); padding-bottom: 10px;">
                <h3><?php echo $termo ? "Resultados para '$termo'" : "Livros Disponíveis"; ?></h3>
                <span style="font-size: 14px; color: #888;"><?php echo count($livros); ?> livro(s)</span>
            </div>

            <?php if (empty($livros)): ?>
                <div class="empty-state" style="text-align: center; padding: 60px 20px; color: #888;">
                    <p>Nenhum livro encontrado.</p>
                    <?php if ($termo): ?><a href="index.php" style="color:rgba(98,102,122,0.808);">Limpar busca</a><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="books-grid">
                    <?php foreach ($livros as $livro): ?>
                        <article class="book-card">
                            <div class="book-cover">
                                <img src="uploads/capas/<?php echo htmlspecialchars($livro['capa'] ?? 'sem-nome.jpeg'); ?>" alt="Capa" onerror="this.src='uploads/capas/sem-nome.jpeg'">
                            </div>
                            <div class="book-info">
                                <h4 class="book-title"><?php echo htmlspecialchars($livro['titulo']); ?></h4>
                                <p class="book-author"><?php echo htmlspecialchars($livro['autor']); ?></p>
                                <p style="font-size: 13px; color: #888;">
                                    <?php echo htmlspecialchars($livro['ano']); ?> | <?php echo htmlspecialchars($livro['genero']); ?>
                                </p>
                                <a href="livro.php?id=<?php echo $livro['id']; ?>" class="btn-small">Ver detalhes</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer style="text-align: center; padding: 25px; background: rgba(255,255,255,0.95); color: #666; margin-top: 40px;">
        <p>&copy; <?php echo date('Y'); ?> Biblioteca On-line.</p>
    </footer>

</body>
</html>