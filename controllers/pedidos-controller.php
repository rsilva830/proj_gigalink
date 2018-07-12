<?php

class PedidosController extends MainController {

    public $login_required = false;
    public $permission_required;

    public function index() {
        $this->title = 'Pedidos';
        $modelo = $this->load_model('/pedido-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/pedidos/pedido-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adm() {
        $this->title = 'Gerenciar Pedidos';
        $modelo = $this->load_model('/pedido-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/pedidos/pedido-adm-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
   
}
