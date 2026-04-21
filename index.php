<?php
session_start();
require_once "config/conexao.php";

// Busca por termo (opcional)
$termo = isset($_GET['search']) ? mysqli_real_escape_string($conexao, trim($_GET['search'])) : '';
$filtro_sql = $termo ? "WHERE titulo LIKE '%$termo%' OR autor LIKE '%$termo%' OR genero LIKE '%$termo%'" : "";

// Buscar livros do banco
$sql = "SELECT id, titulo, autor, genero, ano, capa FROM livros $filtro_sql ORDER BY id DESC";
$resultado = mysqli_query($conexao, $sql);
$livros = [];
if ($resultado) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        $livros[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca On-line</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header com Busca e Acesso -->
    <header class="main-header">
        <div class="header-content">
            <h1 class="logo">📚 Biblioteca On-line</h1>
            
            <form class="search-form" action="index.php" method="GET">
                <input type="text" name="search" placeholder="Buscar livros, autores ou gêneros..." 
                       value="<?php echo htmlspecialchars($termo); ?>" class="search-input">
                <button type="submit" class="btn-search">🔍 Pesquisar</button>
            </form>

            <div class="auth-buttons">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span class="user-greeting">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?>!</span>
                    <a href="dashboard.php" class="btn-outline">Meu Painel</a>
                    <a href="includes/logout.php" class="btn-outline">Sair</a>
                <?php else: ?>
                    <a href="login.php" class="btn-outline">Login</a>
                    <a href="cadastro_usuario.php" class="btn-primary">Cadastre-se</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        
        <!-- Seção de Destaque -->
        <section class="hero">
            <h2>Bem-vindo à Biblioteca On-line</h2>
            <p>Explore nossa coleção de livros digitais. Leia onde e quando quiser.</p>
        </section>

        <!-- Grade de Livros -->
        <section class="books-section">
            <div class="section-header">
                <h3><?php echo $termo ? "Resultados para '$termo'" : "Livros Disponíveis"; ?></h3>
                <span class="book-count"><?php echo count($livros); ?> livro(s)</span>
            </div>

            <?php if (empty($livros)): ?>
                <div class="empty-state">
                    <p>📭 Nenhum livro encontrado.</p>
                    <?php if ($termo): ?>
                        <a href="index.php" class="btn-link">Limpar busca</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="books-grid">
                    <?php foreach ($livros as $livro): ?>
                        <article class="book-card">
                            <div class="book-cover">
                                <img src="uploads/capas/<?php echo htmlspecialchars($livro['capa'] ?? 'sem-nome.jpeg'); ?>" 
                                     alt="Capa de <?php echo htmlspecialchars($livro['titulo']); ?>"
                                     onerror="this.src='uploads/capas/sem-nome.jpeg'">
                            </div>
                            <div class="book-details">
                                <h4 class="book-title"><?php echo htmlspecialchars($livro['titulo']); ?></h4>
                                <p class="book-author"><?php echo htmlspecialchars($livro['autor']); ?></p>
                                <p class="book-meta">
                                    <span class="book-year"><?php echo htmlspecialchars($livro['ano']); ?></span>
                                    <span class="book-genre"><?php echo htmlspecialchars($livro['genero']); ?></span>
                                </p>
                                <div class="book-actions">
                                    <a href="livro.php?id=<?php echo $livro['id']; ?>" class="btn-small">Ver detalhes</a>
                                    <?php if (isset($_SESSION['usuario_id'])): ?>
                                        <button class="btn-small btn-favorite" data-id="<?php echo $livro['id']; ?>">❤ Favoritar</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Seção Informativa -->
        <section class="info-section">
            <div class="info-card">
                <h4>🔓 Acesso 24/7</h4>
                <p>Leia a qualquer hora, de qualquer dispositivo com internet.</p>
            </div>
            <div class="info-card">
                <h4>📚 Acervo Diversificado</h4>
                <p>Literatura, acadêmicos, revistas e muito mais em um só lugar.</p>
            </div>
            <div class="info-card">
                <h4>🔍 Busca Inteligente</h4>
                <p>Encontre livros por título, autor ou gênero em segundos.</p>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <p>&copy; <?php echo date('Y'); ?> Biblioteca On-line. Todos os direitos reservados.</p>
    </footer>

    <script>
        // Favoritar livro (exemplo de interação)
        document.querySelectorAll('.btn-favorite').forEach(btn => {
            btn.addEventListener('click', function() {
                const livroId = this.dataset.id;
                // Aqui você pode fazer uma requisição AJAX para salvar o favorito
                alert('Livro adicionado aos favoritos! (funcionalidade em desenvolvimento)');
                this.textContent = '❤ Favoritado';
                this.disabled = true;
            });
        });
    </script>

</body>
</html>