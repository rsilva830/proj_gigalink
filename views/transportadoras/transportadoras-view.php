<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">

    <?php
    //$modelo->posts_por_pagina = 5;
    $lista = $modelo->listar_transportadoras();
    ?>

    <?php foreach ($lista as $transportadoras): ?>
        <h2>
            <a href="<?php echo HOME_URI ?>/transportadoras/index/<?php echo $transportadoras['id'] ?>">
                <?php echo $transportadoras['nome'] ?>
            </a>
        </h2>
        <?php
        // Verifica se estamos visualizando um unico registro
        if (is_numeric(chk_array($modelo->parametros, 0))): 
            ?>
            <p>
                <?php echo $transportadoras['nome']; ?> 
            </p>
        <?php endif;   ?>
    <?php endforeach; ?>

    <?php $modelo->paginacao(); ?>

</div> 
