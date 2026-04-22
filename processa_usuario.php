<?php
session_start();
require_once "config/conexao.php";

// 🔒 restringir o acesso apenas a administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') { header("Location: index.php"); exit; }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Recebe e sanitiza os dados
    $id = isset($_POST['id']) && !empty($_POST['id']) ? (int)$_POST['id'] : 0;
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $username = mysqli_real_escape_string($conexao, trim($_POST['username']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $telefone = mysqli_real_escape_string($conexao, trim($_POST['telefone']));
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    $tipo = isset($_POST['tipo']) ? mysqli_real_escape_string($conexao, trim($_POST['tipo'])) : 'user';

    $erro = "";

    // 2. Validação de campos obrigatórios
    if (empty($nome) || empty($username) || empty($email) || empty($telefone)) {
        $erro = "Preencha todos os campos obrigatórios.";
    }

    // 3. Validação de e-mail único (ignora o próprio usuário em caso de edição)
    if (empty($erro)) {
        $sql_check = "SELECT id FROM usuarios WHERE email = '$email'";
        if ($id > 0) $sql_check .= " AND id != $id";
        if (mysqli_num_rows(mysqli_query($conexao, $sql_check)) > 0) {
            $erro = "Este e-mail já está cadastrado.";
        }
    }

    // 4. Tratamento seguro da senha
    if (empty($erro)) {
        if (!empty($senha)) {
            if ($senha !== $confirma_senha) {
                $erro = "As senhas não conferem.";
            } elseif (strlen($senha) < 6) {
                $erro = "A senha deve ter no mínimo 6 caracteres.";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            }
        } else {
            // Se não enviou senha e é edição, mantém a senha atual do banco
            if ($id > 0) {
                $res_old = mysqli_query($conexao, "SELECT senha FROM usuarios WHERE id = $id");
                $senha_hash = mysqli_fetch_assoc($res_old)['senha'];
            } else {
                $erro = "A senha é obrigatória para novos cadastros.";
            }
        }
    }

    // 5. Execução SQL (INSERT ou UPDATE)
    if (empty($erro)) {
        if ($id > 0) {
            $sql = "UPDATE usuarios SET nome='$nome', username='$username', email='$email', telefone='$telefone', senha='$senha_hash', tipo='$tipo' WHERE id=$id";
        } else {
            $sql = "INSERT INTO usuarios (nome, username, email, telefone, senha, tipo) VALUES ('$nome', '$username', '$email', '$telefone', '$senha_hash', '$tipo')";
        }

        if (mysqli_query($conexao, $sql)) {
            $msg = $id > 0 ? "Usuário atualizado com sucesso!" : "Usuário criado com sucesso!";
            header("Location: homepage_admin.php?sucesso=" . urlencode($msg));
            exit;
        } else {
            $erro = "Erro no banco: " . mysqli_error($conexao);
        }
    }

    // Redireciona de volta com mensagem de erro
    header("Location: homepage_admin.php?erro=" . urlencode($erro));
    exit;
}

// Se acessou diretamente sem POST
header("Location: homepage_admin.php");
exit;
?>