<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <?php
    //$modelo->posts_por_pagina = 5;
    $lista = $modelo->listar_produtos();
    ?>
    <h3>Produtos:</h3>    
    <?php foreach ($lista as $produto): ?>
        <h4>
            <a href="<?php echo HOME_URI ?>/produtos/index/<?php echo $produto['id'] ?>">
                <?php echo $produto['nome'] ?>
            </a>
        </h4>
        <?php
        // Verifica se estamos visualizando um unico registro
        if (is_numeric(chk_array($modelo->parametros, 0))): 
            ?>
            <p>
                <?php echo $produto['nome']; ?> 
            </p>
        <?php endif;   ?>
    <?php endforeach; ?>

    <?php $modelo->paginacao(); ?>

</div> 
