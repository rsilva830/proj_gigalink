<?php
if (!defined('ABSPATH')) {
    exit;
}
// Configura as URLs
$adm_uri = HOME_URI . '/emails/adm/';
$edit_uri = $adm_uri . 'edit/';
$delete_uri = $adm_uri . 'del/';
// Carrega os métodos
$modelo->obtem_email();
$modelo->insere_email();
$modelo->form_confirma = $modelo->apaga_email();
$modelo->sem_limite = true;
?>

<div class="wrap">
    <?php
    echo $modelo->form_confirma;
    ?>
    <h4>Emails:</h4>
    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validaDados(this);">
        <table class="form-table">
            <td>
                Email: <br>
                <input type="text" name="email" validar="4" maxlength="80" size="85" value="<?php
                echo htmlentities(chk_array($modelo->form_data, 'email'));
                ?>" />
            </td>
            </tr>
            <tr>
            <tr>
                <td>
                    <?php
                    $lista1 = $modelo->listar_fornecedores();
                    $fornecedorEmail = htmlentities(chk_array($modelo->form_data, 'id_fornecedor'));
                    ?>                    
                    Fornecedor: <br>             
                    <select id="fornecedor" name="id_fornecedor" validar="2">
                        <option value="0">Selecione</option>
                        <?php
                        foreach ($lista1 as $fornecedor):
                            if ($fornecedor['id'] == $fornecedorEmail) {
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

        <input type="hidden" name="insere_email" value="1" />
    </form>

    <?php $lista = $modelo->listar_emails(); ?>

    <table class="list-table">
        <?php foreach ($lista as $email): ?>
            <tr>
                <td><?php echo $modelo->fornecedor_nome($email['id_fornecedor']); ?></td>
                <td><?php echo $email['email']; ?></td>
                <td>
                    <a href="<?php echo $edit_uri . $email['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/alterar.gif" alt="Editar"/>Editar</a> 				
                    <a href="<?php echo $delete_uri . $email['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/excluir.gif" alt="Excluir"/>Excluir</a>
                </td>
            </tr>

        <?php endforeach; ?>
    </table>
</div> 
