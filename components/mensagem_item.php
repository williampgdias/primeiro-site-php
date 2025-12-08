<?php

use Carbon\Carbon; ?>

<li class="msg-item">
    <div class="msg-header">
        <span class="msg-autor"><?php echo $msg['nome']; ?></span>
        <span class="msg-date">
            <?php echo isset($msg['data_hora']) ? Carbon::parse($msg['data_hora'])->diffForHumans() : ($msg['data'] ?? 'Data'); ?>

            <?php if (isset($msg['id'])): ?>
            <a href="index.php?acao=editar&id=<?php echo $msg['id']; ?>"
                style="color: #f39c12; text-decoration: none; margin-left: 10px; font-weight: bold;">[Editar]</a>
            <a href="index.php?acao=excluir&id=<?php echo $msg['id']; ?>"
                style="color: #e74c3c; text-decoration: none; margin-left: 5px; font-weight: bold;"
                onclick="return confirm('Apagar?')">&times;</a>
            <?php endif; ?>
        </span>
    </div>

    <div class="msg-body">
        <?php echo nl2br($msg['texto']); ?>
    </div>

    <?php if (isset($msg['editado_em'])): ?>
    <div style="font-size: 0.7rem; color: #95a5a6; margin-top: 5px; font-style: italic;">(Editado
        <?php echo Carbon::parse($msg['editado_em'])->diffForHumans(); ?>)</div>
    <?php endif; ?>

    <?php if (isset($msg['id'])): ?>
    <details style="margin-top: 10px; cursor: pointer;">
        <summary style="color: #3498db; font-size: 0.9rem;">Responder a <?php echo $msg['nome']; ?></summary>

        <form action="index.php" method="POST" class="form-resposta">
            <input type="hidden" name="acao" value="responder">
            <input type="hidden" name="id_pai" value="<?php echo $msg['id']; ?>">

            <input type="text" name="nome_resposta" placeholder="Seu nome..." required>
            <textarea name="texto_resposta" rows="2" placeholder="Sua resposta..." required></textarea>
            <button type="submit" class="btn-responder-envia">Enviar Resposta</button>
        </form>
    </details>
    <?php endif; ?>

    <?php if (!empty($msg['respostas'])): ?>
    <div class="respostas-container">
        <?php foreach ($msg['respostas'] as $resp): ?>
        <div class="resposta-item">
            <div class="msg-header">
                <span class="msg-autor" style="color: #e67e22;"><?php echo $resp['nome']; ?></span>
                <span style="font-size: 0.75rem;">
                    <?php echo Carbon::parse($resp['data_hora'])->diffForHumans(); ?>
                </span>
            </div>
            <div><?php echo nl2br($resp['texto']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</li>