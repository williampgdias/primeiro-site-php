<?php

declare(strict_types=1);

// Iniciar Sessão
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once 'src/Guestbook.php';

use Carbon\Carbon;

date_default_timezone_set('Europe/Lisbon');
Carbon::setLocale('pt');

// Verifica se é o Admin
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

$meuGuestbook = new Guestbook('mensagens.json');
$msgEditar = null;
$modoEdicao = false;

// --- LÓGICA DE SEGURANÇA ---
// Se tentar editar/excluir/responder sem ser admin, barra o acesso;
if ((isset($_GET['acao']) && in_array($_GET['acao'], ['editar', 'excluir'])) || (isset($_POST['acao']) && $_POST['acao'] === 'responder')) {
    if (!$isAdmin) {
        die("Acesso Negado! Você precisa fazer login.");
    }
}

// --- LÓGICA DO SERVIDOR ---
if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $msgEditar = $meuGuestbook->buscarPorId($_GET['id']);
    if ($msgEditar) $modoEdicao = true;
}

if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $meuGuestbook->excluir($_GET['id']);
    header("Location: index.php?status=excluido");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Resposta (Só Admin)
    if (isset($_POST['acao']) && $_POST['acao'] === 'responder') {
        $idPai = $_POST['id_pai'] ?? '';
        $nome = htmlspecialchars(trim($_POST['nome_resposta'] ?? ''));
        $texto = htmlspecialchars(trim($_POST['texto_resposta'] ?? ''));
        if (!empty($idPai) && !empty($nome) && !empty($texto)) {
            $meuGuestbook->responder($idPai, $nome, $texto);
            header("Location: index.php?status=respondido");
            exit;
        }
    }
    // Post Principal (Público)
    else {
        $remetente = htmlspecialchars(trim($_POST['remetente'] ?? ''));
        $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));
        $idEdicao = $_POST['id_edicao'] ?? '';

        if (!empty($remetente) && !empty($mensagem)) {
            if (!empty($idEdicao)) {
                if (!$isAdmin) die("Sem permissão para editar.");
                $meuGuestbook->atualizar($idEdicao, $remetente, $mensagem);
                header("Location: index.php?status=editado");
            } else {
                $meuGuestbook->salvar($remetente, $mensagem);
                header("Location: index.php?status=sucesso");
            }
            exit;
        }
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

        <div class="top-bar">
            <div>
                <?php if ($isAdmin): ?>
                <span style="color: #27ae60; font-weight: bold;">🔓 Admin</span> |
                <a href="logout.php" style="color: #e74c3c; font-size: 0.9rem;">Sair</a>
                <?php else: ?>
                <a href="login.php" style="color: var(--text-secondary); font-size: 0.9rem;">🔒 Login Admin</a>
                <?php endif; ?>
            </div>

            <button id="theme-toggle" class="theme-toggle" title="Alternar Tema">🌙</button>
        </div>

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
            <h3><?php echo $modoEdicao ? '✏️ Editando (Admin)' : 'Deixa a tua marca ✍🏻'; ?></h3>
            <form method="POST" action="index.php">
                <?php if ($modoEdicao): ?>
                <input type="hidden" name="id_edicao" value="<?php echo $msgEditar['id']; ?>">
                <?php endif; ?>

                <input type="text" name="remetente" placeholder="Seu nome..." required
                    value="<?php echo $modoEdicao ? $msgEditar['nome'] : ($isAdmin ? $nomeDono : ''); ?>">

                <textarea name="mensagem" rows="3" placeholder="Sua mensagem..."
                    required><?php echo $modoEdicao ? $msgEditar['texto'] : ''; ?></textarea>

                <button type="submit" class="<?php echo $modoEdicao ? 'btn-editar' : ''; ?>">
                    <?php echo $modoEdicao ? 'Salvar Alterações' : 'Publicar Mensagem'; ?>
                </button>
                <?php if ($modoEdicao): ?>
                <a href="index.php"
                    style="display:block; text-align:center; margin-top:10px; color:var(--text-secondary);">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <h3>Últimas Mensagens (<?php echo count($listaMensagens); ?>)</h3>

        <ul class="msg-list">
            <?php foreach ($listaMensagens as $msg): ?>
            <?php
                // Passamos a variável $isAdmin para dentro do componente
                include 'components/mensagem_item.php';
                ?>
            <?php endforeach; ?>
        </ul>

        <small style="opacity: 0.5; margin-top: 20px;">&copy; <?php echo date('Y'); ?> - Sistema PHP Seguro</small>
    </div>

    <script src="js/script.js"></script>
</body>

</html>