<?php

class FornecedoresController extends MainController {

    public $login_required = false;
    public $permission_required;

    public function index() {
        $this->title = 'Fornecedores';
        $modelo = $this->load_model('/fornecedor-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/fornecedores/fornecedor-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adm() {
        $this->title = 'Gerenciar Fornecedores';
        $modelo = $this->load_model('/fornecedor-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/fornecedores/fornecedor-adm-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
