<?php
$q = isset($_GET['q']) ? mysqli_real_escape_string($conexao, trim($_GET['q'])) : '';
$filtro = $q ? "WHERE titulo LIKE '%$q%' OR autor LIKE '%$q%' OR genero LIKE '%$q%'" : "";
$sql = "SELECT * FROM livros $filtro ORDER BY id DESC";
$res = mysqli_query($conexao, $sql);
$resultados = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<?php if ($q && empty($resultados)): ?>
    <div class="empty-state"><p>🔍 Nenhum resultado para "<strong><?php echo htmlspecialchars($q); ?></strong>".</p></div>
<?php elseif (empty($resultados) && !$q): ?>
    <div class="empty-state"><p>🔍 Digite algo na barra de pesquisa acima.</p></div>
<?php else: ?>
<div class="books-grid">
    <?php foreach ($resultados as $l): ?>
    <article class="book-card">
        <div class="book-cover">
            <img src="uploads/capas/<?php echo htmlspecialchars($l['capa'] ?? 'sem-nome.jpeg'); ?>" alt="Capa" onerror="this.src='uploads/capas/sem-nome.jpeg'">
        </div>
        <div class="book-details">
            <h4 class="book-title"><?php echo htmlspecialchars($l['titulo']); ?></h4>
            <p class="book-author"><?php echo htmlspecialchars($l['autor']); ?></p>
            <p class="book-meta"><?php echo $l['ano']; ?> | <?php echo htmlspecialchars($l['genero']); ?></p>
            <div class="book-actions">
                <button class="btn-small" onclick="acaoLista('favoritos', <?php echo $l['id']; ?>)">❤ Favoritar</button>
                <button class="btn-small" style="background:#6c757d;" onclick="acaoLista('ler_depois', <?php echo $l['id']; ?>)">📖 Ler Depois</button>
            </div>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>