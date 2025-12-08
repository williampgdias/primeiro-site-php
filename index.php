<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'src/Guestbook.php';

use Carbon\Carbon;

// Configurações
date_default_timezone_set('Europe/Lisbon');
Carbon::setLocale('pt');

$meuGuestbook = new Guestbook('mensagens.json');
$msgEditar = null;
$modoEdicao = false;

// --- LÓGICA DO SERVIDOR ---

// Carregar dados para edição
if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $msgEditar = $meuGuestbook->buscarPorId($_GET['id']);
    if ($msgEditar) $modoEdicao = true;
}

// Excluir
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $meuGuestbook->excluir($_GET['id']);
    header("Location: index.php?status=excluido");
    exit;
}

// Processar NOVA RESPOSTA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'responder') {
    $idPai = $_POST['id_pai'] ?? '';
    $nome = htmlspecialchars(trim($_POST['nome_resposta'] ?? ''));
    $texto = htmlspecialchars(trim($_POST['texto_resposta'] ?? ''));

    if (!empty($idPai) && !empty($nome) && !empty($texto)) {
        $meuGuestbook->responder($idPai, $nome, $texto);
        header("Location: index.php?status-respondido");
        exit;
    }
}

// Processar Formulário (Criar ou Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remetente = htmlspecialchars(trim($_POST['remetente'] ?? ''));
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));
    $idEdicao = $_POST['id_edicao'] ?? '';

    if (!empty($remetente) && !empty($mensagem)) {
        if (!empty($idEdicao)) {
            $meuGuestbook->atualizar($idEdicao, $remetente, $mensagem);
            header("Location: index.php?status=editado");
        } else {
            $meuGuestbook->salvar($remetente, $mensagem);
            header("Location: index.php?status=sucesso");
        }
        exit;
    }
}

$listaMensagens = $meuGuestbook->ler();
$nomeDono = "William";
$cargo = "Backend Developer PHP";
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Guestbook do William</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="profile-card">
            <h1><?php echo $nomeDono; ?></h1>
            <p><?php echo $cargo; ?></p>
        </div>

        <?php if (isset($_GET['status'])): ?>
        <div class="feedback">
            <?php
                if ($_GET['status'] === 'sucesso') echo "✅ Mensagem salva!";
                elseif ($_GET['status'] === 'excluido') echo "🗑️ Mensagem apagada!";
                elseif ($_GET['status'] === 'editado') echo "✏️ Mensagem atualizada!";
                elseif ($_GET['status'] === 'respondido') echo "💬 Resposta enviada!";
                ?>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <h3><?php echo $modoEdicao ? '✏️ Editando' : 'Deixa a tua marca ✍🏻'; ?></h3>

            <form method="POST" action="index.php">
                <?php if ($modoEdicao): ?>
                <input type="hidden" name="id_edicao" value="<?php echo $msgEditar['id']; ?>">
                <?php endif; ?>

                <input type="text" name="remetente" placeholder="Seu nome..." required
                    value="<?php echo $modoEdicao ? $msgEditar['nome'] : ''; ?>">

                <textarea name="mensagem" rows="3" placeholder="Sua mensagem..."
                    required><?php echo $modoEdicao ? $msgEditar['texto'] : ''; ?></textarea>

                <button type="submit" class="<?php echo $modoEdicao ? 'btn-editar' : ''; ?>">
                    <?php echo $modoEdicao ? 'Salvar Alterações' : 'Publicar Mensagem'; ?>
                </button>

                <?php if ($modoEdicao): ?>
                <a href="index.php"
                    style="display:block; text-align:center; margin-top:10px; color:#7f8c8d; text-decoration:none;">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <h3>Últimas Mensagens (<?php echo count($listaMensagens); ?>)</h3>

        <ul class="msg-list">
            <?php foreach ($listaMensagens as $msg): ?>
            <?php include 'components/mensagem_item.php'; ?>
            <?php endforeach; ?>
        </ul>

        <small style="opacity: 0.5; margin-top: 20px;">&copy; <?php echo date('Y'); ?> - Sistema PHP Modular</small>
    </div>
</body>

</html>