<?php

use Carbon\Carbon; ?>

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
                echo $msg['data'] ?? 'Data';
            }
            ?>

            <?php if (isset($msg['id'])): ?>
            <a href="index.php?acao=editar&id=<?php echo $msg['id']; ?>"
                style="color: #f39c12; text-decoration: none; margin-left: 10px; font-weight: bold;" title="Editar">
                [Editar]
            </a>

            <a href="index.php?acao=excluir&id=<?php echo $msg['id']; ?>"
                style="color: #e74c3c; text-decoration: none; margin-left: 5px; font-weight: bold;"
                onclick="return confirm('Apagar mensagem?')" title="Excluir">
                &times;
            </a>
            <?php endif; ?>
        </span>
    </div>

    <div class="msg-body">
        <?php echo nl2br($msg['texto']); ?>
    </div>

    <?php if (isset($msg['editado_em'])): ?>
    <div style="font-size: 0.7rem; color: #95a5a6; margin-top: 5px; font-style: italic;">
        (Editado <?php echo Carbon::parse($msg['editado_em'])->diffForHumans(); ?>)
    </div>
    <?php endif; ?>
</li>