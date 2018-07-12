<?php
if (!defined('ABSPATH')) {
    exit();
}

$adm_uri = HOME_URI . '/fornecedores/adm/';
$edit_uri = $adm_uri . 'edit/';
$delete_uri = $adm_uri . 'del/';

$modelo->obtem_fornecedor();
$modelo->insere_fornecedor();
$modelo->form_confirma = $modelo->apaga_fornecedor();
$modelo->sem_limite = true;
?>

<div class="wrap">
    <?php
    // Mensagem de configuração caso o usuário tente apagar algo
    echo $modelo->form_confirma;
    ?>
    <h4>Fornecedores:</h4>
    <!-- Formulário de edição dos fornecedores -->
    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validaDados(this);">
        <table class="form-table">
            <tr>
                <td>
                    Nome Fornecedor: <br>
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
                    Cidade: <br>
                    <input type="text" name="cidade" validar="4" maxlength="30" size="35" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'cidade'));
                    ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    Endereço: <br>
                    <input type="text" name="endereco" validar="4" maxlength="80" size="85" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'endereco'));
                    ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    Bairro: <br>
                    <input type="text" name="bairro" validar="4" maxlength="30" size="35" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'bairro'));
                    ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    Numero: <br>
                    <input type="text" name="numero" validar="6" maxlength="5" size="10" value="<?php
                    echo htmlentities(chk_array($modelo->form_data, 'numero'));
                    ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo $modelo->form_msg; ?>
                    <input type="submit" value="Salvar" />
                </td>
            </tr>
        </table>

        <input type="hidden" name="insere_fornecedor" value="1" />
    </form>

    <!-- LISTA OS FORNECEDORES -->
    <?php $lista = $modelo->listar_fornecedores(); ?>

    <table class="list-table">
        <?php foreach ($lista as $fornecedor): ?>		
            <tr>
                <td><?php echo $fornecedor['nome'] ?></td>
                <td>
                    <a href="<?php echo $edit_uri . $fornecedor['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/alterar.gif" alt="Editar"/>Editar</a> 
                    <a href="<?php echo $delete_uri . $fornecedor['id'] ?>"><img src="<?php echo HOME_URI ?>/views/_images/excluir.gif" alt="Excluir"/>Excluir</a>
                </td>
            </tr>		
        <?php endforeach; ?>
    </table>
</div> 
