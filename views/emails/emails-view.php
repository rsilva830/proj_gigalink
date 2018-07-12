<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">

    <?php
    //$modelo->posts_por_pagina = 5;
    $lista = $modelo->listar_emails();
    ?>

    <?php foreach ($lista as $emails): ?>
        <h2>
            <a href="<?php echo HOME_URI ?>/emails/index/<?php echo $emails['id'] ?>">
                <?php echo $emails['email'] ?>
            </a>
        </h2>
        <?php
        // Verifica se estamos visualizando um unico registro
        if (is_numeric(chk_array($modelo->parametros, 0))):
            ?>
            <p>
            <?php echo $emails['email']; ?> 
            </p>
        <?php endif; ?>
    <?php endforeach; ?>

<?php $modelo->paginacao(); ?>

</div> 
