<?php
if (!defined('ABSPATH')) {
    exit;
}
// Configura as URLs
$adm_uri = HOME_URI . '/transportadoras/adm/';
$edit_uri = $adm_uri . 'edit/';
$delete_uri = $adm_uri . 'del/';
// Carrega os métodos
$modelo->obtem_transportadora();
$modelo->insere_transportadora();
$modelo->form_confirma = $modelo->apaga_transportadora();
$modelo->sem_limite = true;
?>

<div class="wrap">
    <?php
    echo $modelo->form_confirma;
    ?>
    <h4>Transportadoras:</h4>
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

            <td colspan="2">
                <?php
                // Mensagem de feedback para o usuário
                echo $modelo->form_msg;
                ?>
                <input type="submit" value="Salvar" />
            </td>
            </tr>
        </table>

        <input type="hidden" name="insere_transportadora" value="1" />
    </form>

    <?php $lista = $modelo->listar_transportadoras(); ?>

    <table class="list-table">
        <?php foreach ($lista as $transportadora): ?>
            <tr>
                <td><?php echo $transportadora['nome']; ?></td>
                <td>
                    <a href="<?php echo $edit_uri . $transportadora['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/alterar.gif" alt="Editar"/>Editar</a> 				
                    <a href="<?php echo $delete_uri . $transportadora['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/excluir.gif" alt="Excluir"/>Excluir</a>
                </td>
            </tr>

        <?php endforeach; ?>
    </table>
</div> 
