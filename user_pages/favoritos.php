<?php
$sql = "SELECT f.*, l.titulo, l.autor, l.capa 
        FROM favoritos f 
        JOIN livros l ON f.livro_id = l.id 
        WHERE f.usuario_id = $usuario_id 
        ORDER BY f.id DESC";
$res = mysqli_query($conexao, $sql);
$favoritos = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<?php if (empty($favoritos)): ?>
    <div class="empty-state"><p>❤ Você ainda não tem livros favoritos.</p></div>
<?php else: ?>
    <?php foreach ($favoritos as $fav): ?>
    <div class="list-card">
        <img src="uploads/capas/<?php echo htmlspecialchars($fav['capa'] ?? 'sem-nome.jpeg'); ?>" alt="Capa" onerror="this.src='uploads/capas/sem-nome.jpeg'">
        <div class="list-info">
            <h3><?php echo htmlspecialchars($fav['titulo']); ?></h3>
            <p><?php echo htmlspecialchars($fav['autor']); ?></p>
            
            <!-- Barra de Progresso -->
            <div class="progress-wrapper">
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" style="width: <?php echo (int)($fav['progresso'] ?? 0); ?>%;"></div>
                </div>
                <div class="progress-text"><?php echo (int)($fav['progresso'] ?? 0); ?>% concluído</div>
            </div>

            <!-- Campo de Comentário -->
            <div class="comment-box">
                <textarea placeholder="O que você achou deste livro?" 
                          onblur="salvarComentario(<?php echo $fav['id']; ?>, this.value)"><?php echo htmlspecialchars($fav['comentario'] ?? ''); ?></textarea>
                <button class="btn btn-primario btn-save-comment">💾 Salvar Opinião</button>
            </div>

            <button class="btn-remove" style="margin-top:10px;" onclick="removerFavorito(<?php echo $fav['id']; ?>)">🗑 Remover dos Favoritos</button>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function salvarComentario(favId, texto) {
    // 🔹 AQUI: fetch() -> processa_comentario.php { fav_id, comentario }
    console.log(`Salvando comentário ${favId}: ${texto}`);
}
function removerFavorito(favId) {
    if(confirm('Deseja remover este livro dos favoritos?')) {
        // 🔹 AQUI: fetch() -> processa_lista_usuario.php { acao: 'remover_favorito', id: favId }
        location.reload();
    }
}
</script>