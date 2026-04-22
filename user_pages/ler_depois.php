<?php
$sql = "SELECT ld.*, l.titulo, l.autor, l.capa 
        FROM ler_depois ld 
        JOIN livros l ON ld.livro_id = l.id 
        WHERE ld.usuario_id = $usuario_id 
        ORDER BY ld.id DESC";
$res = mysqli_query($conexao, $sql);
$ler_depois = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<?php if (empty($ler_depois)): ?>
    <div class="empty-state"><p>📚 Sua lista de leitura está vazia.</p></div>
<?php else: ?>
    <?php foreach ($ler_depois as $item): ?>
    <div class="list-card">
        <img src="uploads/capas/<?php echo htmlspecialchars($item['capa'] ?? 'sem-nome.jpeg'); ?>" alt="Capa" onerror="this.src='uploads/capas/sem-nome.jpeg'">
        <div class="list-info">
            <h3><?php echo htmlspecialchars($item['titulo']); ?></h3>
            <p><?php echo htmlspecialchars($item['autor']); ?></p>
            <div class="book-actions" style="margin-top:8px;">
                <button class="btn-small" onclick="moverParaFavoritos(<?php echo $item['id']; ?>)">❤ Iniciar Leitura</button>
                <button class="btn-remove" onclick="removerLerDepois(<?php echo $item['id']; ?>)">🗑 Remover</button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function moverParaFavoritos(id) {
    // 🔹 Lógica backend: INSERT em favoritos + DELETE em ler_depois
    alert('Livro movido para Favoritos!');
    location.reload();
}
function removerLerDepois(id) {
    if(confirm('Remover da lista?')) location.reload();
}
</script>