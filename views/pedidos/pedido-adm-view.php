<?php
if (!defined('ABSPATH')) {
    exit();
}
$adm_uri = HOME_URI . '/pedidos/adm/';
$edit_uri = $adm_uri . 'edit/';
$delete_uri = $adm_uri . 'del/';
$item_uri = $adm_uri . 'item/';
$excluir_item_uri = $adm_uri . 'delitem/';

//carrega os metodos
$modelo->obtem_pedido();
$modelo->insere_pedido();
$modelo->del_item_pedido();
$modelo->form_confirma = $modelo->apaga_pedido();
$modelo->sem_limite = true;

$dataAtual = htmlentities(chk_array($modelo->form_data, 'data_hora'));
if ($dataAtual != NULL) {
    $dataAtual = $modelo->inverte_data($dataAtual);
} else {
    $dataAtual = date("d-m-Y H:i:s");
}
?>

<div class="wrap">
    <?php
    // Mensagem de configuração caso o usuário tente apagar algo
    echo $modelo->form_confirma;
    ?>

    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validaDados(this);">
        <table border='0'>
            <tr><td valign="top">
                    <?php
                    if (chk_array($modelo->parametros, 0) != 'item') {
                        ?>
                        <table class="form-table">
                            <tr><td><strong>Pedido:&nbsp;</strong><?php echo htmlentities(chk_array($modelo->form_data, 'id')); ?></td></tr>
                            <tr>
                                <td>
                                    Data e Hora: <br>
                                    <input type="text" name="data_hora" validar="3" maxlength="19" size="24" value="<?php echo $dataAtual; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nota Fiscal: <br>
                                    <input type="text" id="notafiscal" name="notafiscal" validar="6" maxlength="5" size="10" value="<?php
                                    echo htmlentities(chk_array($modelo->form_data, 'notafiscal'));
                                    ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Valor Frete R$: <br>
                                    <input type="text" name="valor_frete" validar="8" maxlength="15" size="20" value="<?php
                                    echo htmlentities(chk_array($modelo->form_data, 'valor_frete'));
                                    ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Desconto R$: <br>
                                    <input type="text" name="desconto" validar="8" maxlength="15" size="20" value="<?php
                                    echo htmlentities(chk_array($modelo->form_data, 'desconto'));
                                    ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    $lista = $modelo->listar_transportadoras();
                                    $transportadoraPedido = htmlentities(chk_array($modelo->form_data, 'id_transportadora'));
                                    ?>                    
                                    Transportadora: <br>             
                                    <select name="id_transportadora">
                                        <option value="0">Selecione</option>
                                        <?php
                                        foreach ($lista as $transportadora):
                                            if ($transportadora['id'] == $transportadoraPedido) {
                                                echo "<option value='" . $transportadora['id'] . "' selected>" . $transportadora['nome'] . "</option>";
                                            } else {
                                                echo "<option value='" . $transportadora['id'] . "'>" . $transportadora['nome'] . "</option>";
                                            }
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        ?>


                        <?php
                        $ped = chk_array($modelo->parametros, 1);
                        $lista1 = $modelo->listar_pedido_items($ped);
                        ?>                    
                        <table class="item-table">
                            <tr><td colspan="3"><strong>Pedido:&nbsp;</strong><?php echo chk_array($modelo->parametros, 1); ?></strong></td></tr>
                            <tr><td><strong>Produto</strong></td>
                                <td><strong>Quantidade</strong></td>
                                <td><strong>Valor Unit. R$</strong></td></tr>
                            <?php foreach ($lista1 as $item): 
                                $valor = number_format($item['valor_unitario'],2,',','.');
                                ?>             
                                <tr>
                                    <td align="left"><?php echo $item['nome']; ?></td>
                                    <td align="right"><?php echo $item['quantidade']; ?></td>
                                    <td align="right"><?php echo $valor; ?></td>
                                    <td><a href="<?php echo $excluir_item_uri . $item['id'].'/'.$ped ?>"><img src="<?php echo HOME_URI ?>/views/_images/excluir.gif" alt="Excluir"/>Excluir</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <input type="hidden" name="insere_item" value="1" />
                        <input type="hidden" name="id_pedido" value="<?php echo chk_array($modelo->parametros, 1) ?>" />
                        <?php
                    }
                    ?> 

                </td>
                <td valign='top'>
                    <?php
                    if (chk_array($modelo->parametros, 0) != 'edit') {
                        ?>
                        <table class="form-table">
                            <tr><td><strong>Item:</strong></td></tr>
                            <tr>
                                <td>
                                    <?php $lista2 = $modelo->listar_produtos(); ?>                    
                                    Produto: <br>             
                                    <select id="produto" name="id_produto" validar="2">
                                        <option value="0">Selecione</option>
                                        <?php
                                        foreach ($lista2 as $produto):
                                            echo "<option value='" . $produto['id'] . "'>" . $produto['nome'] . "</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Quantidade: <br>
                                    <input type="text" name="quantidade" validar="6" maxlength="15" size="20" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Valor Unitario R$: <br>
                                    <input type="text" name="valor_unitario" validar="5" maxlength="15" size="20" value="" />
                                </td>
                            </tr>
                        </table>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr><td colspan="2" align="left">
                    <?php echo $modelo->form_msg; ?>
                    <input type="hidden" name="insere_pedido" value="1" />
                    <input type="submit" value="Incluir / Salvar" />
                </td></tr>
        </table>
    </form>

    <br/>
    <?php $lista = $modelo->listar_pedidos(); ?>
    <table class="list-table">
        <tr><td colspan="2"><strong>Pedidos</strong></td></tr>
        <?php foreach ($lista as $pedido): ?>		
            <tr>
                <td><?php echo $pedido['id'] ?></td>
                <td>
                    <a href="<?php echo $edit_uri . $pedido['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/alterar.gif" alt="Editar"/>Editar</a> 
                    <a href="<?php echo $delete_uri . $pedido['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/excluir.gif" alt="Excluir"/>Excluir</a>
                    <a href="<?php echo $item_uri . $pedido['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/alterar.gif" alt="Incluir Itens"/>Incluir Itens</a>
                </td>
            </tr>		
        <?php endforeach; ?>
    </table>
</div> 
