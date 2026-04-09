<?php
// Configurações do servidor local (XAMPP)
$host = 'localhost';
$dbname = 'biblioteca_db';
$usuario = 'root'; // Usuário padrão do MySQL no XAMPP
$senha = '';       // O XAMPP vem sem senha por padrão

try {
    // Monta a string de conexão e instancia o PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $senha);
    
    // Configura o PDO para avisar quando houver qualquer erro no SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Descomente a linha abaixo para testar a conexão, mas apague depois!
    // echo "Conexão estabelecida com sucesso!";
    
} catch (PDOException $e) {
    // Se algo der errado (ex: XAMPP desligado), ele para tudo e avisa
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>