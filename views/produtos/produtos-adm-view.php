<?php
if (!defined('ABSPATH')) {
    exit;
}
// Configura as URLs
$adm_uri = HOME_URI . '/produtos/adm/';
$edit_uri = $adm_uri . 'edit/';
$delete_uri = $adm_uri . 'del/';
// Carrega os métodos
$modelo->obtem_produto();
$modelo->insere_produto();
$modelo->form_confirma = $modelo->apaga_produto();
$modelo->sem_limite = true;
?>

<div class="wrap">
    <?php
    echo $modelo->form_confirma;
    ?>
<h4>Produtos:</h4>
    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validaDados(this);">
        <table class="form-table">
            <tr>
                <td>
                    Nome: <br>
                    <input type="text" name="nome" validar="4" maxlength="50" size="55" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'nome'));
                    ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    Descrição: <br>
                    <input type="text" name="descricao" validar="4" maxlength="80" size="85" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'descricao'));
                    ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <?php $lista1 = $modelo->listar_fornecedores(); 
                    $fornecedorProduto = htmlentities(chk_array($modelo->form_data, 'id_fornecedor'));?>                    
                    Fornecedor: <br>             
                    <select id="fornecedor" name="id_fornecedor" validar="2">
                    <option value="0">Selecione</option>
                    <?php foreach ($lista1 as $fornecedor): 
                        if($fornecedor['id'] == $fornecedorProduto){
                            echo "<option value='".$fornecedor['id']."' selected>".$fornecedor['nome']."</option>";
                        }else{
                            echo "<option value='".$fornecedor['id']."'>".$fornecedor['nome']."</option>";
                        }        
                    endforeach; 
                    ?>
                    </select>
                 </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                    // Mensagem de feedback para o usuário
                    echo $modelo->form_msg;
                    ?>
                    <input type="submit" value="Salvar" />
                </td>
            </tr>
        </table>

        <input type="hidden" name="insere_produto" value="1" />
    </form>

    <?php $lista2 = $modelo->listar_produtos(); ?>

    <table class="list-table">
        <?php foreach ($lista2 as $produto): ?>
            <tr>
                <td><?php echo $produto['nome'] ?></td>
                <td>
                    <a href="<?php echo $edit_uri . $produto['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/alterar.gif" alt="Editar"/>Editar</a> 				
                    <a href="<?php echo $delete_uri . $produto['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/excluir.gif" alt="Excluir"/>Excluir</a>
                </td>
            </tr>

        <?php endforeach; ?>
    </table>
</div> 
