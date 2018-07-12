<?php

class EmailsController extends MainController {

    public $login_required = false;
    public $permission_required;

    public function index() {
        $this->title = 'Telefones';
        $modelo = $this->load_model('/email-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/emails/emails-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adm() {
        $this->title = 'Gerenciar Emails';
        $modelo = $this->load_model('/email-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/emails/emails-adm-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
