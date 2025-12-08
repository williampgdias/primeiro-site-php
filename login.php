<?php

session_start();

// Senha mestre
$senhaSecreta = 'admin123';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaDigitada = $_POST['senha'] ?? '';

    if ($senhaDigitada === $senhaSecreta) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $erro = "Senha incorreta!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Login - Guestbook</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="form-card login-card">
        <h2>🔒 Acesso Restrito</h2>

        <?php if ($erro): ?>
        <div style="color: #e74c3c; margin-bottom: 15px; font-weight: bold;">
            <?php echo $erro; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <input type="password" name="senha" placeholder="Senha de Admin" required>
            <button type="submit">Entrar no Sistema</button>
        </form>

        <a href="index.php">
            <button class="btn-voltar">Voltar ao Guestbook</button>
        </a>
    </div>
</body>

</html>