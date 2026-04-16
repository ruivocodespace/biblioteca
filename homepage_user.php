
<?php
require_once 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="pt-BR"></html>
<link rel="stylesheet" href="style.css">
<link dashboard href="dashboard.php">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title >Home Page - User</title>
<head>
        <body>        
            <nav>
                <div class="dash user">
                    <ul>
                        <li><a>Página Inicial</a></li>
                        <li><a>Meu Perfil</a></li>
                        <li><a >Favoritos</a></li>
                        <li><a>Ler Depois</a></li>                    
                    </ul>
                    <div class="auth-buttons">
                        <button type="button" class="btn out user">Sair</button>
                        <button type="button" class="btn comumnity user">Comunidade</button><br>
                    </div>
                            <form class="search-form">
                            <div class="form-group">
                                <label>Titulo:</label>
                                <input type="text" placeholder="Nome do livro">
                                </div>

                            <div class="form-group">
                                <label>Autor:</label>
                                <input type="text" placeholder="Nome do autor">
                            </div>
                            
                            <div class="form-group">
                                <label>Gênero:</label>
                                <input type="text" placeholder="Ex: Ficção, Romance...">
                            </div>
                            
                            <div class="form-group">
                                <label>Ano de Publicação:</label>
                                <input type="number" placeholder="Ex: 2024">
                            </div>   
                            </form>                      
                        <button type="submit" class="btn search user">Pesquisar</button>
                    </div>
                <div>
            </nav>        
        </body>
      <main class="main-content">
    </main>
</head>