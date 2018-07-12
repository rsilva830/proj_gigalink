<?php
if (!defined('ABSPATH')) {
    exit;
}
// Configura as URLs
$adm_uri = HOME_URI . '/telefones/adm/';
$edit_uri = $adm_uri . 'edit/';
$delete_uri = $adm_uri . 'del/';
// Carrega os métodos
$modelo->obtem_telefone();
$modelo->insere_telefone();
$modelo->form_confirma = $modelo->apaga_telefone();
$modelo->sem_limite = true;
?>

<div class="wrap">
    <?php
    echo $modelo->form_confirma;
    ?>
<h4>Telefones:</h4>
    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validaDados(this);">
        <table class="form-table">
            <tr>
                <td>
                    DDD: <br>
                    <input type="text" name="ddd" validar="6" maxlength="3" size="5" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'ddd'));
                    ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    Numero: <br>
                    <input type="text" name="numero" validar="6" maxlength="9" size="14" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'numero'));
                    ?>" />
                </td>
            </tr>
            <tr>
            <tr>
                <td>
                    <?php $lista1 = $modelo->listar_fornecedores();
                    $fornecedorProduto = htmlentities(chk_array($modelo->form_data, 'id_fornecedor'));
                    ?>                    
                    Fornecedor: <br>             
                    <select id="fornecedor" name="id_fornecedor" validar="4">
                        <option value="0">Selecione</option>
                        <?php
                        foreach ($lista1 as $fornecedor):
                            if ($fornecedor['id'] == $fornecedorProduto) {
                                echo "<option value='" . $fornecedor['id'] . "' selected>" . $fornecedor['nome'] . "</option>";
                            } else {
                                echo "<option value='" . $fornecedor['id'] . "'>" . $fornecedor['nome'] . "</option>";
                            }
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <td colspan="2">
                <?php
                // Mensagem de feedback para o usuário
                echo $modelo->form_msg;
                ?>
                <input type="submit" value="Salvar" />
            </td>
            </tr>
        </table>

        <input type="hidden" name="insere_telefone" value="1" />
    </form>

        <?php $lista = $modelo->listar_telefones(); ?>

    <table class="list-table">
<?php foreach ($lista as $telefone): ?>
            <tr>
                <td><?php echo $modelo->fornecedor_nome($telefone['id_fornecedor']); ?></td>
                <td><?php echo $telefone['numero']; ?></td>
                <td>
                    <a href="<?php echo $edit_uri . $telefone['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/alterar.gif" alt="Editar"/>Editar</a> 				
                    <a href="<?php echo $delete_uri . $telefone['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/excluir.gif" alt="Excluir"/>Excluir</a>
                </td>
            </tr>

<?php endforeach; ?>
    </table>
</div> 
