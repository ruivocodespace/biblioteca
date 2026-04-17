
<?php
require_once 'dashboard_base.php';
?>
<!DOCTYPE html>
<html lang="pt-BR"></html>
<link rel="stylesheet" href="assets/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title >Home Page - User</title>
<head>
        <body>        
            <nav>
                <div class="user-dash">
                    <ul>
                        <button class="pag-ini-user">Página Inicial</button><br>
                        <button class="my-profile-user">Meu Perfil</button><br>
                        <button class="my-favorites-user">Favoritos</button><br>
                        <button class="read-after-user">Ler Depois</button><br> 
                        <button type="button" class="btn-out-user">Sair</button><br>                        
                    </ul>
                </div>

                <div class="dash-community">
                    <button type="button" class="btn-community-user">Comunidade</button><br>
                </div>

                <div class="search-sidebar">                               
                    <form class="search-form">
                        <div class="form-group">                            
                            <input type="text" class="name-input" placeholder="Nome do livro">   
                            <input type="text" class="name-input" placeholder="Nome do autor">
                            <input type="text" class="name-input" placeholder="Ex: Ficção, Romance...">
                            <input type="number" class="name-input" placeholder="Ex: 2024">                        
                            <button type="submit" class="btn-search-user">Pesquisar</button>
                    </form>  
                </div>                       
            </nav>        
        </body>
      <main class="main-content">
    </main>
</head>