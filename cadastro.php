<?php
// 1. Chama a conexão com o banco de dados
require_once 'config/conexao.php';

// 2. Verifica se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Recebe os dados de texto
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ano_publicacao = $_POST['ano_publicacao'];
    
    $caminho_imagem = null; // Começa vazio, caso o usuário não envie imagem

    // 4. Lógica de Upload de Imagem
    // Verifica se um arquivo foi enviado e se não há erros de upload
    if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
        
        $pasta_destino = "uploads/";
        $nome_arquivo_original = $_FILES['capa']['name'];
        $extensao = strtolower(pathinfo($nome_arquivo_original, PATHINFO_EXTENSION));
        
        // Validação de segurança: aceitar apenas imagens
        $extensoes_permitidas = array("jpg", "jpeg", "png", "webp");
        
        if (in_array($extensao, $extensoes_permitidas)) {
            // Gera um nome único para não sobrescrever arquivos com o mesmo nome
            $novo_nome_imagem = uniqid() . "." . $extensao;
            $caminho_completo = $pasta_destino . $novo_nome_imagem;
            
            // Move o arquivo da pasta temporária do servidor para a nossa pasta /uploads
            if (move_uploaded_file($_FILES['capa']['tmp_name'], $caminho_completo)) {
                // Se moveu com sucesso, salvamos o caminho para enviar ao banco
                $caminho_imagem = $caminho_completo;
            } else {
                echo "<p style='color:red;'>Erro ao salvar a imagem na pasta.</p>";
            }
        } else {
            echo "<p style='color:red;'>Formato de arquivo não permitido. Use JPG, PNG ou WEBP.</p>";
        }
    }

    // 5. Inserção no Banco de Dados usando PDO (Seguro contra SQL Injection)
    try {
        $sql = "INSERT INTO livros (titulo, autor, ano_publicacao, capa_caminho) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Executa a query substituindo os "?" pelas nossas variáveis
        $stmt->execute([$titulo, $autor, $ano_publicacao, $caminho_imagem]);
        
        echo "<p style='color:green;'>Livro cadastrado com sucesso!</p>";
        // Aqui depois vocês podem colocar um redirecionamento para o index.php
        // header("Location: index.php");
        
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar no banco: " . $e->getMessage() . "</p>";
    }
}
?>