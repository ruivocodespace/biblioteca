<?php
require_once "../includes/logado.php";
require_once "config/conexao.php";

$sql = "SELECT * FROM livros ORDER BY id DESC LIMIT 24";
$res = mysqli_query($conexao, $sql);
$livros = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<?php if (empty($livros)): ?>
    <div class="empty-state"><p>📭 Nenhum livro disponível no momento.</p></div>
<?php else: ?>
<div class="books-grid">
    <?php foreach ($livros as $l): ?>
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

<script>
function acaoLista(lista, livroId) {
    // 🔹 AQUI: Substitua por fetch() chamando processa_lista_usuario.php
    console.log(`Adicionando livro ${livroId} à lista ${lista}`);
    alert(`✅ Livro adicionado a "${lista}"!`);
    location.reload();
}
</script>