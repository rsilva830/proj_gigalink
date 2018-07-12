<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">

    <?php
    //$modelo->posts_por_pagina = 5;
    $lista = $modelo->listar_telefones();
    ?>

    <?php foreach ($lista as $telefone): ?>
        <h2>
            <a href="<?php echo HOME_URI ?>/telefones/index/<?php echo $telefone['id'] ?>">
                <?php echo $telefone['numero'] ?>
            </a>
        </h2>
        <?php
        // Verifica se estamos visualizando um unico registro
        if (is_numeric(chk_array($modelo->parametros, 0))): 
            ?>
            <p>
                <?php echo $telefone['numero']; ?> 
            </p>
        <?php endif;   ?>
    <?php endforeach; ?>

    <?php $modelo->paginacao(); ?>

</div> 
