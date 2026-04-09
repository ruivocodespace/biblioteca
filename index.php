<?php

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
  </body>

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
        <label for="remember">Esqueseu a senha?:</label>
        <input type="submit" value="Ajude-me" formaction="/remenber" />
        
      </form>
    <h2>Cadastre-se</h2>
        <form action="/register" method="post">
        <label for="new-username">Nickname:</label><br />
        <input type="text" id="new-username" name="new-username" required /><br />
        <label for="new-password">Senha:</label><br />
        <input type="password" id="new-password" name="new-password" required /><br />      
        <label for="name"> Nome Completo:</label><br />
        <input type="text" id="name" name="name" required /><br />
        <label for="name">Email:</label><br />
        <input type="email" id="Email" formaction="/email" /><br/>
        <label for="name">Telefone:</label><br />
        <input type="numbet" id="Telefone" formaction="/phone" />
        <input type="submit" value="Registrar" /><br/>  
        </form>
    </div>
        
    <div>
    <h2>Pesquise     
        <form action="/search" method="get">
          <label for="search">Pesquisar livros:</label><br />
          <input type="text" id="search" name="search" required />
          <input type="submit" value="Pesquisar" />
        </form>
    </h2>
    </div>

    
</html>