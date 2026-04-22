<?php
session_start();
require_once "config/conexao.php";

// 🔒 Segurança: Apenas admins
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: index.php"); exit;
}

// 📥 Controle de Visualização (Dashboard ou Formulários)
$view = $_GET['view'] ?? 'dashboard';
$edit_id = $_GET['id'] ?? null;

// Buscar dados para edição
$livro_edit = null;
$user_edit = null;

if ($view === 'editar_livro' && $edit_id) {
    $livro_edit = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT * FROM livros WHERE id = $edit_id"));
}
if ($view === 'editar_usuario' && $edit_id) {
    $user_edit = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT * FROM usuarios WHERE id = $edit_id"));
}

// Buscar listas para o dashboard
$res_livros = mysqli_query($conexao, "SELECT * FROM livros ORDER BY id DESC");
$res_users  = mysqli_query($conexao, "SELECT id, nome, username, email, tipo FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* === CSS DO DASHBOARD (MANTIDO) === */
        .admin-wrapper { display: flex; min-height: 100vh; background: #f4f6f8; }
        .admin-sidebar { width: 260px; background: #fff; padding: 30px 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 20px; }
        .admin-logo { font-size: 22px; font-weight: bold; color: rgba(98, 102, 122, 0.808); text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #eee; }
        .admin-nav a { display: block; padding: 12px 15px; text-decoration: none; color: #444; border-radius: 8px; transition: all 0.2s; font-weight: 500; margin-bottom: 8px; }
        .admin-nav a:hover, .admin-nav a.active { background: rgba(98, 102, 122, 0.808); color: #fff; }
        .admin-content { flex: 1; padding: 30px; overflow-y: auto; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        
        /* Tabelas do Dashboard */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .data-table th, .data-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; }
        .data-table th { background: #f8f9fa; color: #555; font-weight: 600; font-size: 14px; }
        .data-table tr:hover { background: #f9fafb; }
        .cover-thumb { width: 40px; height: 60px; object-fit: cover; border-radius: 4px; }
        .btn-action { padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px; color: #fff; transition: opacity 0.2s; text-decoration: none; display: inline-block; }
        .btn-edit { background: #4a90e2; }
        .btn-delete { background: #e74c3c; }
        .btn-action:hover { opacity: 0.85; }
        .badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .badge-admin { background: #ffebee; color: #c62828; }
        .badge-user { background: #e3f2fd; color: #1565c0; }

        /* === CONTAINER DOS FORMULÁRIOS (MANTÉM CSS ORIGINAL) === */
        .form-dashboard-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 0 auto;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .form-dashboard-container h2 { margin-bottom: 25px; color: rgb(27,18,18); text-align: center; font-family: 'Gill Sans', sans-serif; }
        
        /* CSS herdado exatamente das páginas de cadastro */
        .cadastro-form { display: flex; flex-direction: column; gap: 15px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .label-form { color: rgb(2,2,2); font-size: 16px; font-weight: 500; display: block; margin-bottom: 5px; font-family: 'Gill Sans', sans-serif; }
        .input-form { width: 100%; padding: 12px; font-size: 16px; border: 2px solid #ccc; border-radius: 8px; font-family: inherit; transition: border 0.2s, box-shadow 0.2s; }
        .input-form:focus { outline: none; border-color: rgba(98, 102, 122, 0.808); box-shadow: 0 0 0 3px rgba(98, 102, 122, 0.15); }
        .btn { padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: 600; transition: all 0.2s ease; width: 100%; margin-top: 10px; }
        .btn-primario { background: rgba(98, 102, 122, 0.808); color: #fff; }
        .btn-primario:hover { background: rgba(98, 102, 122, 1); transform: translateY(-2px); }
        .btn-secundario { background: transparent; color: rgba(98, 102, 122, 0.808); border: 2px solid rgba(98, 102, 122, 0.808); }
        .btn-secundario:hover { background: rgba(98, 102, 122, 0.1); }
        .alert { padding: 12px 15px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; text-align: center; }
        .alert-erro { background: #ffebee; color: #c62828; border-left: 4px solid #c62828; }
        .alert-sucesso { background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="admin-logo">📚 Admin Panel</div>
            <nav class="admin-nav">
                <a href="?view=dashboard" class="<?php echo $view === 'dashboard' ? 'active' : ''; ?>">📖 Dashboard</a>
                <a href="?view=cadastro_livro" class="<?php echo strpos($view, 'livro') !== false ? 'active' : ''; ?>">📚 Gerenciar Livros</a>
                <a href="?view=cadastro_usuario" class="<?php echo strpos($view, 'usuario') !== false ? 'active' : ''; ?>">👥 Gerenciar Usuários</a>
                <a href="index.php" style="margin-top: auto; color: #888;">🏠 Voltar ao Site</a>
            </nav>
        </aside>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="admin-content">
            <div class="admin-header">
                <h2>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
                <div>
                    <a href="?view=dashboard" class="btn-action btn-edit">Voltar</a>
                    <a href="logout.php" class="btn btn-secundario" style="width:auto; margin:0; padding: 8px 15px;">Sair</a>
                </div>
            </div>

            <?php if ($view === 'dashboard'): ?>
                <!-- TABELA DE LIVROS -->
                <section style="margin-bottom: 40px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                        <h3>📖 Livros Cadastrados</h3>
                        <a href="?view=cadastro_livro" class="btn-action btn-edit" style="background:#28a745;">+ Adicionar</a>
                    </div>
                    <table class="data-table">
                        <thead><tr><th>Capa</th><th>ID</th><th>Título</th><th>Autor</th><th>Ano</th><th>Gênero</th><th>Ações</th></tr></thead>
                        <tbody>
                            <?php if(mysqli_num_rows($res_livros) > 0): while($l = mysqli_fetch_assoc($res_livros)): ?>
                                <tr>
                                    <td><img src="uploads/capas/<?php echo htmlspecialchars($l['capa'] ?? 'sem-nome.jpeg'); ?>" class="cover-thumb" onerror="this.src='uploads/capas/sem-nome.jpeg'"></td>
                                    <td>#<?php echo $l['id']; ?></td>
                                    <td><?php echo htmlspecialchars($l['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($l['autor']); ?></td>
                                    <td><?php echo $l['ano']; ?></td>
                                    <td><?php echo htmlspecialchars($l['genero']); ?></td>
                                    <td>
                                        <a href="?view=editar_livro&id=<?php echo $l['id']; ?>" class="btn-action btn-edit">Editar</a>
                                        <button class="btn-action btn-delete" onclick="if(confirm('Excluir este livro?')) window.location.href='deletar_livro.php?id=<?php echo $l['id']; ?>'">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="7" style="text-align:center; padding:20px; color:#888;">Nenhum livro cadastrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>

                <!-- TABELA DE USUÁRIOS -->
                <section>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                        <h3>👥 Usuários Cadastrados</h3>
                        <a href="?view=cadastro_usuario" class="btn-action btn-edit" style="background:#28a745;">+ Adicionar</a>
                    </div>
                    <table class="data-table">
                        <thead><tr><th>ID</th><th>Nome</th><th>Username</th><th>Email</th><th>Tipo</th><th>Ações</th></tr></thead>
                        <tbody>
                            <?php if(mysqli_num_rows($res_users) > 0): while($u = mysqli_fetch_assoc($res_users)): ?>
                                <tr>
                                    <td>#<?php echo $u['id']; ?></td>
                                    <td><?php echo htmlspecialchars($u['nome']); ?></td>
                                    <td>@<?php echo htmlspecialchars($u['username']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td><span class="badge <?php echo $u['tipo'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>"><?php echo ucfirst($u['tipo']); ?></span></td>
                                    <td>
                                        <a href="?view=editar_usuario&id=<?php echo $u['id']; ?>" class="btn-action btn-edit">Editar</a>
                                        <button class="btn-action btn-delete" onclick="if(confirm('Excluir este usuário?')) window.location.href='deletar_usuario.php?id=<?php echo $u['id']; ?>'">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="6" style="text-align:center; padding:20px; color:#888;">Nenhum usuário cadastrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>

            <?php elseif ($view === 'cadastro_livro' || $view === 'editar_livro'): ?>
                <!-- FORMULÁRIO DE LIVRO (CSS ORIGINAL PRESERVADO) -->
                <div class="form-dashboard-container">
                    <h2><?php echo $view === 'editar_livro' ? '✏️ Editar Livro' : '📚 Cadastrar Livro'; ?></h2>
                    <form class="   tro-form" action="processa_livro.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $livro_edit['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label class="label-form">Título:</label>
                            <input type="text" name="titulo" class="input-form" value="<?php echo htmlspecialchars($livro_edit['titulo'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Autor:</label>
                            <input type="text" name="autor" class="input-form" value="<?php echo htmlspecialchars($livro_edit['autor'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Ano:</label>
                            <input type="number" name="ano" class="input-form" value="<?php echo htmlspecialchars($livro_edit['ano'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Gênero:</label>
                            <input type="text" name="genero" class="input-form" value="<?php echo htmlspecialchars($livro_edit['genero'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Capa:</label>
                            <input type="file" name="capa" class="input-form" accept="image/jpeg,image/png,image/webp">
                            <?php if ($livro_edit): ?><small style="color:#666;">Deixe em branco para manter a atual.</small><?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primario">Salvar Livro</button>
                        <a href="?view=dashboard" class="btn btn-secundario" style="display:block; text-align:center; text-decoration:none; margin-top:10px;">Cancelar</a>
                    </form>
                </div>

            <?php elseif ($view === 'cadastro_usuario' || $view === 'editar_usuario'): ?>
                <!-- FORMULÁRIO DE USUÁRIO (CSS ORIGINAL PRESERVADO) -->
                <div class="form-dashboard-container">
                    <h2><?php echo $view === 'editar_usuario' ? '✏️ Editar Usuário' : '👤 Cadastrar Usuário'; ?></h2>
                    <form class="cadastro-form" action="processa_usuario.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $user_edit['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label class="label-form">Nome Completo:</label>
                            <input type="text" name="nome" class="input-form" value="<?php echo htmlspecialchars($user_edit['nome'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Nome de Usuário:</label>
                            <input type="text" name="username" class="input-form" value="<?php echo htmlspecialchars($user_edit['username'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Tipo:</label>
                            <select name="tipo" class="input-form">
                                <option value="user" <?php echo ($user_edit['tipo'] ?? 'user') === 'user' ? 'selected' : ''; ?>>Usuário</option>
                                <option value="admin" <?php echo ($user_edit['tipo'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="label-form">E-mail:</label>
                            <input type="email" name="email" class="input-form" value="<?php echo htmlspecialchars($user_edit['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Telefone:</label>
                            <input type="tel" name="telefone" class="input-form" value="<?php echo htmlspecialchars($user_edit['telefone'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-form">Senha: <?php if ($user_edit) echo '<small style="color:#666;">(Deixe em branco para não alterar)</small>'; ?></label>
                            <input type="password" name="senha" class="input-form" <?php echo $user_edit ? '' : 'required'; ?> minlength="6">
                        </div>
                        <?php if (!$user_edit): ?>
                        <div class="form-group">
                            <label class="label-form">Confirmar Senha:</label>
                            <input type="password" name="confirma_senha" class="input-form" required minlength="6">
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primario">Salvar Usuário</button>
                        <a href="?view=dashboard" class="btn btn-secundario" style="display:block; text-align:center; text-decoration:none; margin-top:10px;">Cancelar</a>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>