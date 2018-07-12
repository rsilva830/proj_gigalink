<?php

class TelefonesController extends MainController {

    public $login_required = false;
    public $permission_required;

    public function index() {
        $this->title = 'Telefones';
        $modelo = $this->load_model('/telefone-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/telefones/telefones-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adm() {
        $this->title = 'Gerenciar Telefones';
        $modelo = $this->load_model('/telefone-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/telefones/telefones-adm-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
