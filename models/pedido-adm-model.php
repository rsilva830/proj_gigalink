<?php

class PedidoAdmModel extends MainModel {

    public $posts_por_pagina = REG_PG;

    public function __construct($db = false, $controller = null) {
        $this->db = $db;
        $this->controller = $controller;
        $this->parametros = $this->controller->parametros;
        //$this->userdata = $this->controller->userdata;
    }

    public function listar_pedidos() {
        $id = $where = $query_limit = null;
        // Verifica se um parâmetro foi enviado para carregar um fornecedor
        if (is_numeric(chk_array($this->parametros, 0))) {
            // Configura o ID para enviar para a consulta
            $id = array(chk_array($this->parametros, 0));
            $where = " WHERE id = ? ";
        }
        $pagina = !empty($this->parametros[1]) ? $this->parametros[1] : 1;
        $pagina--;
        $posts_por_pagina = $this->posts_por_pagina;
        $offset = $pagina * $posts_por_pagina;

        if (empty($this->sem_limite)) {
            $query_limit = " LIMIT $offset,$posts_por_pagina ";
        }
        $query = $this->db->query('SELECT * FROM pedido ' . $where . ' ORDER BY id DESC' . $query_limit, $id);
        return $query->fetchAll();
    }

    public function listar_produtos() {
        $query = $this->db->query('SELECT * FROM produto');
        return $query->fetchAll();
    }

    public function listar_pedido_items($id = null) {
        if ($id != null) {
            $query = $this->db->query('SELECT pi.id_pedido,pi.id_produto,pi.quantidade,pi.valor_unitario,p.nome,pi.id '
                    . 'FROM pedido_item pi, produto p where pi.id_pedido=' . $id . ' and pi.id_produto = p.id;');
            return $query->fetchAll();
        }
        return;
    }

    public function listar_transportadoras() {
        $query = $this->db->query('SELECT * FROM transportadora');
        return $query->fetchAll();
    }

    public function listar_transportadora_nome($id = null) {
        if ($id > 0) {
            $query = $this->db->query('SELECT * FROM transportadora where id=' . $id);
            $transportadora = $query->fetchAll();
            return $transportadora[0]['nome'];
        }
        return;
    }

    public function obtem_pedido() {
        // Verifica se o primeiro parâmetro é "edit"
        if (chk_array($this->parametros, 0) != 'edit') {
            return;
        }
        //verifica se quer incluir item
        if (chk_array($this->parametros, 0) == 'item') {
            return;
        }
        // Verifica se o segundo parâmetro é um número
        if (!is_numeric(chk_array($this->parametros, 1))) {
            return;
        }

        $pedido_id = chk_array($this->parametros, 1);
        if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['insere_pedido'])) {
            // Remove o campo insere_pedido para não gerar problema com o PDO
            unset($_POST['insere_pedido']);
            unset($_POST['id_produto']);
            unset($_POST['quantidade']);
            unset($_POST['valor_unitario']);
            //trata a data
            $_POST["data_hora"] = $this->inverte_data($_POST["data_hora"]);
            // Atualiza os dados
            $query = $this->db->update('pedido', 'id', $pedido_id, $_POST);
            $this->atualiza_valor_pedido($pedido_id);
            if ($query) {
                $this->form_msg = '<p class="success">Pedido atualizado com sucesso!</p>';
            }
        }

        $query = $this->db->query('SELECT * FROM pedido WHERE id = ? LIMIT 1', array($pedido_id));
        $fetch_data = $query->fetch();
        if (empty($fetch_data)) {
            return;
        }
        $this->form_data = $fetch_data;
    }

    public function atualiza_valor_pedido($idPedido = null) {
        if ($idPedido) {
            $query1 = $this->db->query('SELECT * FROM pedido_item WHERE id_pedido = ?', array($idPedido));
            $items = $query1->fetchAll();
            $valorPedidos = 0;
            foreach ($items as $item):
                $valorPedidos = $valorPedidos + ($item["quantidade"] * $item["valor_unitario"]);
            endforeach;
            $query2 = $this->db->query('SELECT * FROM pedido WHERE id = ? limit 1', array($idPedido));
            $pedido = $query2->fetch();
            $desconto = chk_array($pedido, 'desconto');
            $frete = chk_array($pedido, 'valor_frete');
            $valor_total = ($valorPedidos + $frete) - $desconto;
            unset($_POST);
            $_POST["valor_total"] = $valor_total;
            $query = $this->db->update('pedido', 'id', $idPedido, $_POST);
        }
    }

    public function insere_pedido() {
        if ('POST' != $_SERVER['REQUEST_METHOD'] || empty($_POST['insere_pedido'])) {
            return;
        }
        if (chk_array($this->parametros, 0) == 'edit') {
            return;
        }

        //verifica se e para incluir somente o item
        if (!empty($_POST['insere_item'])) {
            //Remove o campo insere_pedido para não gerar problema com o PDO
            unset($_POST['insere_pedido']);
            unset($_POST['insere_item']);
            //insere o item do pedido   
            $query1 = $this->db->insert('pedido_item', $_POST);
            //atualiza o valor total do pedido   
            $idPedido = $_POST['id_pedido'];
            $this->atualiza_valor_pedido($idPedido);
            if ($query1) {
                return;
            }
            $this->form_msg = '<p class="error">Erro ao enviar dados!</p>';
        } else {
            //armazena os valores para inserir o item do pedido
            $idProduto = $_POST['id_produto'];
            $quantidade = $_POST['quantidade'];
            $valor_unitario = $_POST['valor_unitario'];
            //Remove o campo insere_pedido para não gerar problema com o PDO
            unset($_POST['insere_pedido']);
            unset($_POST['insere_item']);
            unset($_POST['id_produto']);
            unset($_POST['quantidade']);
            unset($_POST['valor_unitario']);
            //trata a data
            $_POST["data_hora"] = $this->inverte_data($_POST["data_hora"]);
            // Insere os dados na base de dados
            $query1 = $this->db->insert('pedido', $_POST);
            // Remove o campo desnecessarios para inserçao dos itens
            unset($_POST['data_hora']);
            unset($_POST['notafiscal']);
            unset($_POST['valor_frete']);
            unset($_POST['desconto']);
            unset($_POST['id_transportadora']);
            // prepara as variaveis em POST
            $idPedido = $this->db->lastInsertId();
            $_POST["id_pedido"] = $idPedido;
            $_POST['id_produto'] = $idProduto;
            $_POST['quantidade'] = $quantidade;
            $_POST['valor_unitario'] = $valor_unitario;
            //insere o item do pedido  
            $query2 = $this->db->insert('pedido_item', $_POST);
            $idPedido = $_POST['id_pedido'];
            $this->atualiza_valor_pedido($idPedido);

            if ($query1 && $query2) {
                $this->form_msg = '<p class="success">Pedido atualizado com sucesso!</p>';
                return;
            }
            $this->form_msg = '<p class="error">Erro ao enviar dados!</p>';
        }
    }

    public function apaga_pedido() {
        // O parâmetro del deverá ser enviado
        if (chk_array($this->parametros, 0) != 'del') {
            return;
        }
        if (!is_numeric(chk_array($this->parametros, 1))) {
            return;
        }

        // Para excluir, o terceiro parâmetro deverá ser "confirma"
        if (chk_array($this->parametros, 2) != 'confirma') {
            // Configura uma mensagem de confirmação para o usuário
            $mensagem = '<p class="alert">Tem certeza que deseja apagar o pedido?</p>';
            $mensagem .= '<p><a href="' . $_SERVER['REQUEST_URI'] . '/confirma/">Sim</a> | ';
            $mensagem .= '<a href="' . HOME_URI . '/pedidos/adm/">Não</a></p>';
            return $mensagem;
        }

        $pedido_id = (int) chk_array($this->parametros, 1);
        // Executa a consulta

        $query = $this->db->delete('pedido_item', 'id_pedido', $pedido_id);
        $query = $this->db->delete('pedido', 'id', $pedido_id);


        // Redireciona para a página de administração de pedidos
        echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/pedidos/adm/">';
        echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/pedidos/adm/";</script>';
    }

    public function del_item_pedido() {
        // O parâmetro del deverá ser enviado
        if (chk_array($this->parametros, 0) != 'delitem') {
            return;
        }
        if (!is_numeric(chk_array($this->parametros, 1))) {
            return;
        }

        $idItem = (int) chk_array($this->parametros, 1);
        $idPedido = (int) chk_array($this->parametros, 2);
        $query = $this->db->delete('pedido_item', 'id', $idItem);
        $this->atualiza_valor_pedido($idPedido);

        // Redireciona para a página de administração de pedidos
        echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/pedidos/adm/item/'.$idPedido.'">';
        echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/pedidos/adm/item/'.$idPedido.'";</script>';
    }

    public function paginacao() {
        //Verifica se o primeiro parâmetro não é um número. Se for é um single e não precisa de paginação.
        if (is_numeric(chk_array($this->parametros, 0))) {
            return;
        }
        //verifica se foi passado alguma pagina para o metodo, caso contrario seta 1
        $pPg = $this->parametros[1] ? $this->parametros[1] : 1;
        //calcula o numero de paginas 
        $query = $this->db->query('SELECT COUNT(*) as total FROM pedido');
        $linhas = $query->fetch();
        $linhas = $linhas['total'];
        $resPorPg = $this->posts_por_pagina;
        $qtPg = (($linhas % $resPorPg) > 0) ? (int) ($linhas / $resPorPg) + 1 : ($linhas / $resPorPg);
        //seta o caminho
        $caminho = HOME_URI . '/pedidos/index/page/';
        //imprime a paginacao
        if ($qtPg > 1) {
            $prox = $pPg + 1;
            $ant = $pPg - 1;
            echo "<table boder='0'>";
            echo "<tr>";
            echo "<td><font size='2'>Pg. $pPg de $qtPg.</font></td>";
            if ($pPg < $qtPg) {
                echo "<td><a href='$caminho$prox'><font size='2'><strong>Proxima</strong></font></a></td>";
            }
            if ($pPg > 1) {
                echo "<td><a href='$caminho$ant'><font size='2'><strong>Anterior</strong></font></a></td>";
            }
            echo "</tr>";
            echo "</table>";
        }
    }

}
