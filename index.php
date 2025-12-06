<?php

// Backend
$nomeBruto = $_GET['nome'] ?? 'Visitante';
$nome = htmlspecialchars($nomeBruto);

$cargo = "Backend Developer em Formação";
$anoAtual = date('Y');

$hora = (int)date('H');

if ($hora >= 6 && $hora < 12) {
    $saudacao = "Bom dia ☀️";
    $corFundo = "#f0f8ff";
} else if ($hora >= 12 && $hora < 18) {
    $saudacao = "Boa tarde 🌤️";
    $corFundo = "#fffacd";
} else {
    $saudacao = "Boa noite 🌙";
    $corFundo = "#2c3e50";
    $corTexto = "#ecf0f1";
}

$corTexto = $corTexto ?? "#333";
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Meu Primeiro Site PHP</title>
    <style>
    body {
        background-color: <?php echo $corFundo;
        ?>;
        color: <?php echo $corTexto;
        ?>;
        font-family: sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        text-align: center;
    }

    .card {
        background: rgba(255, 255, 255, 0.1);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    h1 {
        margin-bottom: 10px;
    }

    p {
        font-size: 1.2rem;
        opacity: 0.8;
    }

    .badge {
        background-color: #ff4757;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="card">
        <h1>
            <?php echo $saudacao; ?>, <?php echo $nome; ?>!
        </h1>

        <p>
            Atualmente focando em: <span class="badge">PHP Moderno</span>
        </p>
        <p>
            <?php echo $cargo; ?>
        </p>
        <small>&copy; <?php echo $anoAtual; ?> - Gerado via PHP Server</small>
    </div>
</body>

</html>