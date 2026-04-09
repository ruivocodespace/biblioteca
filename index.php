<?php
// 1. O SEU MOTOR: Chama a conexão com o banco de dados
require_once 'config/conexao.php';

try {
    // 2. Busca os livros cadastrados, do mais novo para o mais antigo
    $sql = "SELECT * FROM livros ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar os livros: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home page Biblioteca</title>
  </head>

  <body>
    <p>Bem-vindo à Biblioteca On-line!</p>
    
    <h2>O que é a Biblioteca on-line?</h2>
    <p>A Biblioteca on-line é um recurso digital que oferece acesso a uma vasta coleção de livros, artigos, revistas e outros materiais de leitura. Ela permite que os usuários acessem conteúdos de forma conveniente e rápida, sem a necessidade de visitar fisicamente uma biblioteca tradicional.</p>
    
    <h2>Benefícios da Biblioteca on-line</h2>
    <ul>
      <li>Acesso 24/7: Os usuários podem acessar a biblioteca a qualquer hora e de qualquer lugar, desde que tenham uma conexão à internet.</li>
      <li>Variedade de materiais: A biblioteca on-line oferece uma ampla gama de recursos, incluindo livros digitais, artigos acadêmicos, revistas e muito mais.</li>
      <li>Facilidade de pesquisa: Os usuários podem pesquisar por títulos, autores ou palavras-chave para encontrar rapidamente o material desejado.</li>
      <li>Economia de espaço: A biblioteca on-line elimina a necessidade de espaço físico para armazenar livros e outros materiais.</li>
    </ul>

    <div>
      <h2>Como acessar a Biblioteca on-line?</h2>
      <p>Para acessar a Biblioteca on-line, basta visitar o site oficial da biblioteca e criar uma conta. Depois de se registrar, você poderá explorar a coleção de materiais disponíveis e começar a ler imediatamente.</p>
      
      <h2>Faça Login</h2>
      <form action="/login" method="post">
        <label for="username">Nome de usuário:</label><br />
        <input type="text" id="username" name="username" required /><br />
        <label for="password">Senha:</label><br />
        <input type="password" id="password" name="password" required /><br />
        <input type="submit" value="Entrar" />
        <label for="remember">Esqueceu a senha?:</label>
        <input type="button" value="Ajude-me" />
      </form>
      
      <h2>Cadastre-se</h2>
      <form action="/register" method="post">
        <label for="new-username">Nickname:</label><br />
        <input type="text" id="new-username" name="new-username" required /><br />
        <label for="new-password">Senha:</label><br />
        <input type="password" id="new-password" name="new-password" required /><br />      
        <label for="name"> Nome Completo:</label><br />
        <input type="text" id="name" name="name" required /><br />
        <label for="email">Email:</label><br />
        <input type="email" id="email" name="email" required /><br/>
        <label for="telefone">Telefone:</label><br />
        <input type="number" id="telefone" name="telefone" /><br><br>
        <input type="submit" value="Registrar" />  
      </form>
    </div>
        
    <div>
      <h2>Pesquise</h2>     
      <form action="/search" method="get">
        <label for="search">Pesquisar livros:</label><br />
        <input type="text" id="search" name="search" required />
        <input type="submit" value="Pesquisar" />
      </form>
    </div>

    <hr>

    <div>
      <h2>📚 Acervo da Biblioteca</h2>
      
      <p><a href="cadastrar.php" style="font-weight: bold; color: green;">+ Adicionar Novo Livro</a></p>
      
      <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
        
        <?php if (count($livros) > 0): ?>
            <?php foreach ($livros as $livro): ?>
                <div style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; width: 220px; background-color: #f9f9f9;">
                    
                    <?php if (!empty($livro['capa_caminho']) && file_exists($livro['capa_caminho'])): ?>
                        <img src="<?php echo $livro['capa_caminho']; ?>" alt="Capa do Livro" style="width: 100%; height: 280px; object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                        <div style="width: 100%; height: 280px; background: #ddd; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                            <span>Sem Capa</span>
                        </div>
                    <?php endif; ?>
                    
                    <h3 style="font-size: 18px; margin: 10px 0 5px 0;"><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p style="margin: 0; font-size: 14px;"><strong>Autor:</strong> <?php echo htmlspecialchars($livro['autor']); ?></p>
                    <p style="margin: 0 0 15px 0; font-size: 14px;"><strong>Ano:</strong> <?php echo htmlspecialchars($livro['ano_publicacao']); ?></p>
                    
                    <div style="display: flex; gap: 10px;">
                        <a href="editar.php?id=<?php echo $livro['id']; ?>" style="background: #007BFF; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 14px;">Editar</a>
                        <a href="excluir.php?id=<?php echo $livro['id']; ?>" onclick="return confirm('Tem certeza que deseja apagar este livro?');" style="background: #DC3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 14px;">Excluir</a>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum livro encontrado no banco de dados.</p>
        <?php endif; ?>

      </div>
    </div>
    <br><br>

  </body>
</html>