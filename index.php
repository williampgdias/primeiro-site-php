<?php

declare(strict_types=1);

// Autoload Composer
require_once __DIR__ . '/vendor/autoload.php';

// Importar a classe
require_once 'src/Guestbook.php';

use Carbon\Carbon;

date_default_timezone_set('Europe/Lisbon');
Carbon::setLocale('pt');

// Criar o Guestbook
$meuGuestbook = new Guestbook('mensagens.json');

// Editar
$msgEditar = null;
$modoEdicao = false;

// Verificar se o usuário clicou em editar
if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $msgEditar = $meuGuestbook->buscarPorId($_GET['id']);
    if ($msgEditar) {
        $modoEdicao = true;
    }
}

// Exclusão
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $idParaExcluir = $_GET['id'];
    $meuGuestbook->excluir($idParaExcluir);

    header("Location: index.php?status=excluido");
    exit;
}

// --- Lógica Web Server ---
$nomeDono = "William";
$cargo = "Backend Developer PHP";
$anoAtual = date('Y');

$feedback = "";

// Verifica sucesso (GET)
// if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
//     $feedback = "✅ Mensagem salva com sucesso!";
// } elseif ($_GET['status'] === 'excluido') {
//     $feedback = "🗑️ Mensagem apagada!";
// }

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'sucesso') $feedback = '✅ Mensagem salva com sucesso!';
    elseif ($_GET['status'] === 'excluido') $feedback = "🗑️ Mensagem apagada!";
    elseif ($_GET['status'] === 'editado') $feedback = "✏️ Mensagem atualizada com sucesso!";
}

// Processo de Envio (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remetente = htmlspecialchars(trim($_POST['remetente'] ?? ''));
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));
    $idEdicao = $_POST['id_edicao'] ?? '';

    if (!empty($remetente) && !empty($mensagem)) {
        if (!empty($idEdicao)) {
            $meuGuestbook->atualizar($idEdicao, $remetente, $mensagem);
            header('Location: index.php?status=editado');
        } else {
            $meuGuestbook->salvar($remetente, $mensagem);
            header("Location: index.php?status=sucesso");
        }
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

    button.btn-editar {
        background: #f39c12;
    }

    button.btn-editar:hover {
        background: #e67e22;
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
            <h3><?php echo $modoEdicao ? '✏️ Editando Mensagem' : 'Deixa a tua marca ✍🏻'; ?></h3>

            <form method="POST" action="index.php">
                <?php if ($modoEdicao): ?>
                <input type="hidden" name="id_edicao" value="<?php echo $msgEditar['id']; ?>">
                <?php endif; ?>

                <input type="text" name="remetente" placeholder="O teu nome..." required
                    value="<?php echo $modoEdicao ? $msgEditar['nome'] : ''; ?>">

                <textarea name="mensagem" rows="3" placeholder="Escreve algo fixe..."
                    required><?php echo $modoEdicao ? $msgEditar['texto'] : ''; ?></textarea>

                <?php if ($modoEdicao): ?>
                <button type="submit" class="btn-editar">Salvar Alterações</button>
                <a href="index.php"
                    style="display:block; text-align:center; margin-top:10px; color:#7f8c8d; text-decoration:none;">Cancelar
                    Edição</a>
                <?php else: ?>
                <button type="submit">Publicar Mensagem</button>
                <?php endif; ?>
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
                                echo Carbon::parse($msg['data_hora'])->diffForHumans();
                            } else {
                                echo $msg['data'] ?? 'Data desconhecida';
                            }
                            ?>

                        <?php if (isset($msg['id'])): ?>
                        <a href="index.php?acao=editar&id=<?php echo $msg['id']; ?>"
                            style="color: #f39c12; text-decoration: none; margin-left: 10px; font-weight: bold;"
                            title="Editar mensagem">
                            [Editar]
                        </a>

                        <a href="index.php?acao=excluir&id=<?php echo $msg['id']; ?>"
                            style="color: #e74c3c; text-decoration: none; margin-left: 5px; font-weight: bold;"
                            onclick="return confirm('Apagar mensagem?')" title="Excluir mensagem">
                            &times;
                        </a>
                        <?php endif; ?>
                    </span>
                </div>
                <?php echo $msg['texto']; ?>

                <?php if (isset($msg['editado_em'])): ?>
                <div style="font-size: 0.7rem; color: #95a5a6; margin-top: 5px; font-style: italic;">
                    (Editado <?php echo Carbon::parse($msg['editado_em'])->diffForHumans(); ?>)
                </div>
                <?php endif; ?>
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