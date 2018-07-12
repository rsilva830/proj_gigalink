<?php
if (!defined('ABSPATH'))
    exit;
?>

<div class="wrap">
    <?php
    //$modelo->posts_por_pagina = 5;
    $lista1 = $modelo->listar_pedidos();
    $varios = false;
    //verifica se deve mostrar o titulo e o link quando for um unico pedido
    if (!is_numeric(chk_array($modelo->parametros, 0))) {
        echo "<h3>Pedidos:</h3>";
        echo "<strong>ID - Valor</strong><br/>";
        $varios = true;
    }
    foreach ($lista1 as $pedido):
        if ($varios) {
            $data = $modelo->inverte_data($pedido['data_hora']);
            $valor = number_format($pedido['valor_total'],2,',','.');
            echo "<strong><a href=" . HOME_URI . "/pedidos/index/" . $pedido['id'] . ">" . $pedido['id'] . "</a></strong> - ".$valor."<br/>";
        }
    endforeach;
    // Verifica se estamos visualizando um único pedido
    if (is_numeric(chk_array($modelo->parametros, 0))):
        $dataAtual = date("d/m/Y H:i:s");
        if ($pedido['data_hora'] != NULL) {
            $dataAtual = $modelo->inverte_data($pedido['data_hora']);
        }     
        $frete = number_format($pedido['valor_frete'], 2, ',', '.');
        $desconto = number_format($pedido['desconto'], 2, ',', '.');
        $valorTotal = number_format($pedido['valor_total'], 2, ',', '.');
        ?>
        <table>
            <tr><td align="right"><strong>Pedido: </strong></td><td><?php echo $pedido['id'];?></td></tr>
            <tr><td align="right"><strong>Data: </strong></td><td><?php echo $dataAtual;?></td></tr>
            <tr><td align="right"><strong>Nota Fiscal: </strong></td><td><?php echo $pedido['notafiscal'];?></td></tr>
            <tr><td align="right"><strong>Valor Frete R$: </strong></td><td><?php echo $frete;?></td></tr>
            <tr><td align="right"><strong>Desconto R$: </strong></td><td><?php echo $desconto;?></td></tr>
            <tr><td align="right"><strong>Valor Total  R$: </strong></td><td><?php echo $valorTotal;?>&nbsp;&nbsp;<font size="2">((total itens + frete) - desconto)</font></td></tr>
            <tr><td align="right"><strong>Transportadora: </strong></td><td><?php echo $modelo->listar_transportadora_nome($pedido['id_transportadora']);?></td></tr>
        </table>  <br/>                    
        <?php $lista2 = $modelo->listar_pedido_items($pedido['id']); ?>                    
        <table class="item-table">
            <tr><td colspan="3"><strong>Itens:</strong></td></tr>
            <tr><td><strong>Produto</strong></td>
                <td><strong>Quantidade</strong></td>
                <td><strong>Valor Unit. R$</strong></td></tr>
            <?php foreach ($lista2 as $item): ?>             
                <tr>
                    <td align="left"><?php echo $item['nome']; ?></td>
                    <td align="right"><?php echo $item['quantidade']; ?></td>
                    <td align="right"><?php echo number_format($item['valor_unitario'],2,',','.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>


    <?php $modelo->paginacao(); ?>

</div> 
