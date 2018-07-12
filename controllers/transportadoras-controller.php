<?php

class TransportadorasController extends MainController {

    public $login_required = false;
    public $permission_required;

    public function index() {
        $this->title = 'Transportadoras';
        $modelo = $this->load_model('/transportadora-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/transportadoras/transportadoras-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adm() {
        $this->title = 'Gerenciar Transportadoras';
        $modelo = $this->load_model('/transportadora-adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/transportadoras/transportadoras-adm-view.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
