<?php

declare(strict_types=1);

// Autoload Composer
require_once __DIR__ . '/vendor/autoload.php';

// Importar a classe
require_once 'src/Guestbook.php';

use Carbon\Carbon;

date_default_timezone_set('Europe/Lisbon');

// Criar o Guestbook
$meuGuestbook = new Guestbook('mensagens.json');

// --- Lógica Web Server ---
$nomeDono = "William";
$cargo = "Backend Developer PHP";
$anoAtual = date('Y');

$feedback = "";

// Verifica sucesso (GET)
if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
    $feedback = "✅ Mensagem salva com sucesso!";
}

// Processo de Envio (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remetente = htmlspecialchars(trim($_POST['remetente'] ?? ''));
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));

    if (!empty($remetente) && !empty($mensagem)) {
        $meuGuestbook->salvar($remetente, $mensagem);

        header("Location: index.php?status=sucesso");
        exit;
    } else {
        $feedback = "❌ Preenche todos os campos!";
    }
}

// Carregar mensagens
$listaMensagens = $meuGuestbook->ler();

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Guestbook do William</title>
    <style>
    body {
        background-color: #2c3e50;
        color: #ecf0f1;
        font-family: sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        margin: 0;
    }

    .container {
        width: 100%;
        max-width: 500px;
    }

    /* Estilo do Cartão Principal */
    .profile-card {
        background: #34495e;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        margin-bottom: 20px;
    }

    /* Estilo do Formulário */
    .form-card {
        background: #ecf0f1;
        color: #333;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin: 5px 0 15px 0;
        border: 1px solid #bdc3c7;
        border-radius: 5px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 10px;
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background: #2ecc71;
    }

    /* Estilo da Lista de Mensagens */
    .msg-list {
        list-style: none;
        padding: 0;
    }

    .msg-item {
        background: #fff;
        color: #333;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        border-left: 5px solid #3498db;
    }

    .msg-header {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-bottom: 5px;
    }

    .msg-author {
        font-weight: bold;
        color: #2980b9;
    }

    .feedback {
        background: #e67e22;
        color: white;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile-card">
            <h1><?php echo $nomeDono; ?></h1>
            <p><?php echo $cargo; ?></p>
        </div>

        <?php if ($feedback): ?>
        <div class="feedback">
            <?php echo $feedback; ?>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <h3>Deixa a tua marca ✍🏻</h3>
            <form method="POST">
                <input type="text" name="remetente" placeholder="O teu nome..." required>
                <textarea name="mensagem" rows="3" placeholder="Escreve algo fixe..." required></textarea>
                <button type="submit">Publicar Mensagem</button>
            </form>
        </div>

        <h3>Últimas Mensagens (<?php echo count($listaMensagens); ?>)</h3>

        <ul class="msg-list">
            <?php foreach ($listaMensagens as $msg): ?>
            <li class="msg-item">
                <div class="msg-header">
                    <span class="msg-autor">
                        <?php echo $msg['nome']; ?>
                    </span>
                    <span class="msg-date">
                        <?php
                            if (isset($msg['data_hora'])) {
                                $data = Carbon::parse($msg['data_hora']);
                                $data->locale('pt');
                                echo $data->diffForHumans();
                            } else {
                                echo $msg['data'] ?? 'Data desconhecida';
                            }
                            ?>
                    </span>
                </div>
                <?php echo $msg['texto']; ?>
            </li>
            <?php endforeach; ?>

            <?php if (count($listaMensagens) === 0): ?>
            <p style="text-align: center; opacity:0.6;">Seja o primeiro a comentar!</p>
            <?php endif; ?>
        </ul>

        <small style="opacity: 0.5;">&copy; <?php echo $anoAtual; ?> - Sistema PHP JSON</small>
    </div>
</body>

</html>