<?php

use Carbon\Carbon;

$inicial = strtoupper(substr($msg['nome'], 0, 1));
?>

<li class="msg-item">
    <div class="msg-header">
        <div class="avatar"><?php echo $inicial; ?></div>

        <div class="msg-info">
            <span class="msg-autor"><?php echo $msg['nome']; ?></span>
            <span class="msg-date">
                <?php echo isset($msg['data_hora']) ? Carbon::parse($msg['data_hora'])->diffForHumans() : ($msg['data'] ?? 'Data'); ?>
                <?php if (isset($msg['editado_em'])): ?> &bull; Editado <?php endif; ?>
            </span>
        </div>

        <?php if (isset($msg['id']) && isset($isAdmin) && $isAdmin): ?>
        <div class="msg-actions">
            <a href="index.php?acao=editar&id=<?php echo $msg['id']; ?>" style="color: #f7b928;" title="Editar">✏️</a>
            <a href="index.php?acao=excluir&id=<?php echo $msg['id']; ?>" style="color: #e74c3c;"
                onclick="return confirm('Apagar?')" title="Excluir">🗑️</a>
        </div>
        <?php endif; ?>
    </div>

    <div class="msg-body">
        <?php echo nl2br($msg['texto']); ?>
    </div>

    <?php if (isset($msg['id']) && isset($isAdmin) && $isAdmin): ?>
    <details>
        <summary>Responder como Admin</summary>
        <form action="index.php" method="POST" class="form-resposta">
            <input type="hidden" name="acao" value="responder">
            <input type="hidden" name="id_pai" value="<?php echo $msg['id']; ?>">

            <input type="text" name="nome_resposta" value="William (Admin)" readonly
                style="background: #e8f0fe; font-weight: bold;">

            <textarea name="texto_resposta" placeholder="Escreva uma resposta..." required></textarea>
            <button type="submit" class="btn-responder-envia">Enviar</button>
        </form>
    </details>
    <?php endif; ?>

    <?php if (!empty($msg['respostas'])): ?>
    <div class="respostas-container">
        <?php foreach ($msg['respostas'] as $resp): ?>
        <?php $inicialResp = strtoupper(substr($resp['nome'], 0, 1)); ?>
        <div class="resposta-item">
            <div class="avatar avatar-small"><?php echo $inicialResp; ?></div>
            <div class="resposta-balao">
                <span class="resposta-autor"><?php echo $resp['nome']; ?></span>
                <span class="resposta-texto"><?php echo nl2br($resp['texto']); ?></span>
            </div>
        </div>
        <div class="resposta-data" style="margin-left: 50px;">
            <?php echo Carbon::parse($resp['data_hora'])->diffForHumans(); ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</li>