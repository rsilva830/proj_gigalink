<?php

class EmailAdmModel extends MainModel {

    public $posts_por_pagina = REG_PG;

    public function __construct($db = false, $controller = null) {
        $this->db = $db;
        $this->controller = $controller;
        $this->parametros = $this->controller->parametros;
        //$this->userdata = $this->controller->userdata;
    }

    public function listar_emails() {
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
        $query = $this->db->query('SELECT * FROM email ' . $where . ' ORDER BY id_fornecedor DESC' . $query_limit, $id);
        return $query->fetchAll();
    }

    public function listar_fornecedores() {
        $query = $this->db->query('SELECT * FROM fornecedor');
        return $query->fetchAll();
    }

    public function fornecedor_nome($id = null) {
        if ($id > 0) {
            $query = $this->db->query('SELECT nome FROM fornecedor where id=' . $id);
            $fornecedor = $query->fetchAll();
            return $fornecedor[0]['nome'];
        }
        return;
    }

    public function obtem_email() {
        // Verifica se o primeiro parâmetro é "edit"
        if (chk_array($this->parametros, 0) != 'edit') {
            return;
        }
        // Verifica se o segundo parâmetro é um número
        if (!is_numeric(chk_array($this->parametros, 1))) {
            return;
        }
        $id = chk_array($this->parametros, 1);
        if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['insere_email'])) {

            // Remove o campo insere_fornecedor para não gerar problema com o PDO
            unset($_POST['insere_email']);

            // Atualiza os dados
            $query = $this->db->update('email', 'id', $id, $_POST);
            if ($query) {
                $this->form_msg = '<p class="success">Email atualizado com sucesso!</p>';
            }
        }
        $query = $this->db->query('SELECT * FROM email WHERE id = ? LIMIT 1', array($id));
        $fetch_data = $query->fetch();
        if (empty($fetch_data)) {
            return;
        }
        $this->form_data = $fetch_data;
    }

    public function insere_email() {
        if ('POST' != $_SERVER['REQUEST_METHOD'] || empty($_POST['insere_email'])) {
            return;
        }
        if (chk_array($this->parametros, 0) == 'edit') {
            return;
        }
        // Só pra garantir que não estamos atualizando nada
        if (is_numeric(chk_array($this->parametros, 1))) {
            return;
        }
        // Remove o campo insere_fornecedor para não gerar problema com o PDO
        unset($_POST['insere_email']);
        // Insere os dados na base de dados
        $query = $this->db->insert('email', $_POST);

        if ($query) {
            $this->form_msg = '<p class="success">Email atualizado com sucesso!</p>';
            return;
        }
        $this->form_msg = '<p class="error">Erro ao enviar dados!</p>';
    }

    public function apaga_email() {
        // O parâmetro del deverá ser enviado
        if (chk_array($this->parametros, 0) != 'del') {
            return;
        }
        if (!is_numeric(chk_array($this->parametros, 1))) {
            return;
        }

        // Para excluir, o terceiro parâmetro deverá ser "confirma"
        if (chk_array($this->parametros, 2) != 'confirma') {
            $mensagem = '<p class="alert">Tem certeza que deseja apgar o Email?</p>';
            $mensagem .= '<p><a href="' . $_SERVER['REQUEST_URI'] . '/confirma/">Sim</a> | ';
            $mensagem .= '<a href="' . HOME_URI . '/emails/adm/">Não</a></p>';
            return $mensagem;
        }

        $id = (int) chk_array($this->parametros, 1);
        $query = $this->db->delete('email', 'id', $id);
        echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/emails/adm/">';
        echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/emails/adm/";</script>';
    }

    public function paginacao() {
        //Verifica se o primeiro parâmetro não é um número. Se for é um single e não precisa de paginação.
        if (is_numeric(chk_array($this->parametros, 0))) {
            return;
        }
        //verifica se foi passado alguma pagina para o metodo, caso contrario seta 1
        $pPg = $this->parametros[1] ? $this->parametros[1] : 1;
        //calcula o numero de paginas 
        $query = $this->db->query('SELECT COUNT(*) as total FROM email');
        $linhas = $query->fetch();
        $linhas = $linhas['total'];
        $resPorPg = $this->posts_por_pagina;
        $qtPg = (($linhas % $resPorPg) > 0) ? (int) ($linhas / $resPorPg) + 1 : ($linhas / $resPorPg);
        //seta o caminho
        $caminho = HOME_URI . '/emails/index/page/';
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
