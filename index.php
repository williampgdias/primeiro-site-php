<?php

// Backend
$nomeDono = "William";
$cargo = "Backend Developer";
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


// Lógica do GET (URL)
$nomeVisitante = $_GET['nome'] ?? 'Visitante';
$nomeVisitante = htmlspecialchars($nomeVisitante);

// Lógica POST (Processar Formulário)
$mensagemFeedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remetente = $_POST['remetente'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';

    // Sanitização
    $remetente = htmlspecialchars($remetente);
    $mensagem = htmlspecialchars($mensagem);

    if ($remetente !== '' && $mensagem !== '') {
        $mensagemFeedback = "✅ Obrigado, $remetente! A tua mensagem foi recebida.";
    } else {
        $mensagemFeedback = "❌ Por favor, preenche todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Cartão Digital Interativo</title>
    <style>
    body {
        background-color: <?php echo $corFundo;
        ?>;
        color: <?php echo $corTexto;
        ?>;
        font-family: sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .container {
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    .card {
        background: rgba(255, 255, 255, 0.1);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 20px;
    }

    /* Estilos do Formulário */
    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        /* Garante que o padding não estoure a largura */
    }

    button {
        background-color: #ff4757;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        width: 100%;
    }

    button:hover {
        background-color: #ff6b81;
    }

    .feedback {
        background-color: rgba(0, 0, 0, 0.3);
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>
                <?php echo $saudacao; ?>, <?php echo $nomeVisitante; ?>!
            </h1>
            <p>
                Eu sou o <strong><?php echo $nomeDono; ?></strong>
            </p>
            <p>
                <?php echo $cargo; ?>
            </p>
        </div>

        <?php if ($mensagemFeedback !== ''): ?>
        <div class="feedback">
            <?php echo $mensagemFeedback; ?>
        </div>
        <?php endif; ?>

        <div class="card">
            <h3>Deixe uma mensagem</h3>

            <form method="POST" action="">
                <input type="text" name="remetente" placeholder="O teu nome..." required>
                <textarea name="mensagem" rows="4" placeholder="Escreve aqui..." required></textarea>
                <button type="submit">Enviar Mensagem</button>
            </form>
        </div>

        <small>&copy; <?php echo $anoAtual; ?> - PHP Moderno</small>
    </div>
</body>

</html>