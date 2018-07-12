<?php

class ProdutosController extends MainController {

    public $login_required = false;
    public $permission_required;

    public function index() {
        $this->title = 'Produtos';
        $modelo = $this->load_model('/produto-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/produtos/produtos-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adm() {
        $this->title = 'Gerenciar Produtos';
        $modelo = $this->load_model('/produto-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/produtos/produtos-adm-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
