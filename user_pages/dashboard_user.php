<?php
session_start();
require_once "config/conexao.php";

// 🔒 Segurança: Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Buscar dados do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$sql_user = "SELECT * FROM usuarios WHERE id = $usuario_id LIMIT 1";
$res_user = mysqli_query($conexao, $sql_user);
$usuario = mysqli_fetch_assoc($res_user);

// Roteamento simples
$page = $_GET['page'] ?? 'home';
$page_file = match($page) {
    'home'       => 'homepage_user.php',
    'favoritos'  => 'favoritos.php',
    'ler_depois' => 'ler_depois.php',
    'pesquisa'   => 'pesquisa.php',
    default      => 'homepage_user.php'
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Biblioteca - <?php echo ucfirst($page); ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* === CSS EXCLUSIVO DO DASHBOARD USUÁRIO === */
        .user-dashboard { display: flex; min-height: 100vh; background: #f8f9fa; }
        .user-sidebar { width: 260px; background: #fff; padding: 25px 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 8px; }
        .user-logo { font-size: 20px; font-weight: bold; color: var(--cor-primaria); text-align: center; padding-bottom: 15px; border-bottom: 2px solid #eee; margin-bottom: 10px; }
        .user-nav a { display: flex; align-items: center; gap: 10px; padding: 12px 15px; text-decoration: none; color: #444; border-radius: 8px; transition: 0.2s; font-weight: 500; }
        .user-nav a:hover, .user-nav a.active { background: var(--cor-primaria); color: #fff; }
        .user-main { flex: 1; padding: 30px; overflow-y: auto; }
        .user-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: #fff; padding: 15px 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); flex-wrap: wrap; gap: 15px; }
        .page-content { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Cards de Listas (Favoritos / Ler Depois) */
        .list-card { background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; gap: 15px; margin-bottom: 15px; align-items: flex-start; transition: transform 0.2s; }
        .list-card:hover { transform: translateX(3px); }
        .list-card img { width: 60px; height: 90px; object-fit: cover; border-radius: 6px; flex-shrink: 0; }
        .list-info { flex: 1; }
        .list-info h3 { font-size: 16px; margin: 0 0 5px 0; }
        .list-info p { font-size: 13px; color: #666; margin: 0 0 8px 0; }

        /* Barra de Progresso */
        .progress-wrapper { margin: 10px 0; }
        .progress-bar-bg { background: #eee; height: 8px; border-radius: 4px; overflow: hidden; }
        .progress-bar-fill { background: var(--cor-primaria); height: 100%; border-radius: 4px; transition: width 0.4s ease; }
        .progress-text { font-size: 12px; color: #666; margin-top: 4px; text-align: right; }

        /* Área de Comentário */
        .comment-box { margin-top: 8px; }
        .comment-box textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit; font-size: 13px; resize: vertical; min-height: 60px; background: #fafafa; }
        .comment-box textarea:focus { outline: none; border-color: var(--cor-primaria); background: #fff; }
        .btn-save-comment { margin-top: 6px; padding: 6px 12px; font-size: 12px; width: auto; }
        .btn-remove { background: #dc3545; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; transition: opacity 0.2s; }
        .btn-remove:hover { opacity: 0.85; }

        /* Responsivo */
        @media (max-width: 768px) {
            .user-dashboard { flex-direction: column; }
            .user-sidebar { width: 100%; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
            .user-nav { display: flex; overflow-x: auto; gap: 5px; padding-bottom: 5px; }
            .user-nav a { white-space: nowrap; padding: 10px; font-size: 14px; }
            .user-header { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
    <div class="user-dashboard">
        <aside class="user-sidebar">
            <div class="user-logo">📚 Minha Biblioteca</div>
            <nav class="user-nav">
                <a href="?page=home" class="<?php echo $page === 'home' ? 'active' : ''; ?>">🏠 Início</a>
                <a href="?page=favoritos" class="<?php echo $page === 'favoritos' ? 'active' : ''; ?>">❤ Favoritos</a>
                <a href="?page=ler_depois" class="<?php echo $page === 'ler_depois' ? 'active' : ''; ?>">📖 Ler Depois</a>
                <a href="?page=pesquisa" class="<?php echo $page === 'pesquisa' ? 'active' : ''; ?>">🔍 Pesquisa</a>
            </nav>
            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
                <p style="font-size: 12px; color: #888; margin-bottom: 5px;">Logado como:</p>
                <p style="font-weight: 600; margin-bottom: 10px; font-size: 14px;"><?php echo htmlspecialchars($usuario['nome']); ?></p>
                <a href="logout.php" class="btn btn-secundario" style="width: 100%; padding: 8px; font-size: 13px;">Sair</a>
            </div>
        </aside>

        <main class="user-main">
            <div class="user-header">
                <h2 style="margin:0; font-size: 22px;"><?php echo match($page) { 'home' => 'Explorar Acervo', 'favoritos' => 'Meus Favoritos', 'ler_depois' => 'Lista de Leitura', 'pesquisa' => 'Pesquisar Livros', default => 'Dashboard' }; ?></h2>
                <?php if ($page === 'pesquisa'): ?>
                <form class="search-form" style="max-width: 400px; margin:0;" action="?page=pesquisa" method="GET">
                    <input type="text" name="q" placeholder="Título, autor ou gênero..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" class="search-input">
                    <button type="submit" class="btn-search">🔍</button>
                </form>
                <?php endif; ?>
            </div>

            <div class="page-content">
                <?php 
                // Variáveis passadas para as views
                $usuario_id = $usuario['id']; 
                require_once $page_file; 
                ?>
            </div>
        </main>
    </div>
</body>
</html>