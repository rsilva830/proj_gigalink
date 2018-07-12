<?php
if (!defined('ABSPATH'))
    exit;
?>

<div class="wrap">
    <?php
    //$modelo->posts_por_pagina = 5;
    $lista = $modelo->listar_fornecedores();
    ?>
    <h3>Fornecedores:</h3>  
    <?php foreach ($lista as $fornecedor): ?>
        <h4>
            <a href="<?php echo HOME_URI ?>/fornecedores/index/<?php echo $fornecedor['id'] ?>">
                <?php echo $fornecedor['nome'] ?>
            </a>
        </h4>

        <?php
        // Verifica se estamos visualizando um únicao fornecedor
        if (is_numeric(chk_array($modelo->parametros, 0))):
            ?>
            <?php echo $fornecedor['nome']; ?><br/>
            <?php echo $fornecedor['descricao']; ?><br/>
            <?php echo $fornecedor['cidade']; ?><br/>
            <?php echo $fornecedor['endereco']; ?><br/>
            <?php echo $fornecedor['bairro']; ?><br/>
            <?php echo $fornecedor['numero']; ?><br/><br/>       
            <strong>Telefones:</strong><br/>     
            <?php $lista1 = $modelo->listar_fornecedor_telefones($fornecedor['id']); ?>                    
            <?php
            foreach ($lista1 as $telefone):
                echo $telefone['ddd']." ".$telefone['numero'] . "<br/>";
            endforeach;
            ?>   
            <br/>
            <strong>Emails:</strong><br/>     
            <?php $lista1 = $modelo->listar_fornecedor_emails($fornecedor['id']); ?>                    
            <?php
            foreach ($lista1 as $email):
                echo $email['email']. "<br/>";
            endforeach;
            ?>  
        <?php endif; ?>

<?php endforeach; ?>
<?php $modelo->paginacao(); ?>

</div> 
