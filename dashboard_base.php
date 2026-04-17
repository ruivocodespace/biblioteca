<!DOCTYPE html>
<html lang="pt-BR">
<head> 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<body>      
  <style>
    .main-content {
      margin-left: 320px;
      padding: 40px;
    }    
    .bookmark-sidebar {
      position: fixed;
      left: 20px;
      top: 20px;
      bottom: 20px;
      width: 280px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      display: flex;
      flex-direction: column;
      padding: 30px 20px;
      z-index: -1000;
      height: 340px;
    }
    .user-profile {
      width: 50px;
      height: 50px;
      background: #e0e7ff;
      border-radius: 50%;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #4f46e5;
      font-weight: bold;
      font-size: 20px;
    }    
  </style>
</head>
<body>

  <nav class="bookmark-sidebar">
    <div class="user-profile">U</div>
  </nav>

  <main class="main-content">
    <h1>Dashboard</h1>
    <p>Exibe as informações da página atual</p>
  </main>

</body>
</html>