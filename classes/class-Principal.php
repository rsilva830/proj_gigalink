<?php

class Principal {

    private $controlador;
    private $acao;
    private $parametros;
    private $not_found = '/includes/404.php';

    public function __construct() {

        $this->get_url_data();
        if (!$this->controlador) {
            // Adiciona o controlador padrão
            require_once ABSPATH . '/controllers/home-controller.php';
            $this->controlador = new HomeController();
            $this->controlador->index();
            return;
        }

        // Se o arquivo do controlador não existir, não faremos nada
        if (!file_exists(ABSPATH . '/controllers/' . $this->controlador . '.php')) {
            // Página não encontrada
            require_once ABSPATH . $this->not_found;
            return;
        }

        // Inclui o arquivo do controlador
        require_once ABSPATH . '/controllers/' . $this->controlador . '.php';

        // Remove caracteres inválidos do nome do controlador para gerar o nome
        // da classe. Se o arquivo chamar "news-controller.php", a classe deverá
        // se chamar NewsController.
        $this->controlador = preg_replace('/[^a-zA-Z]/i', '', $this->controlador);

        // Se a classe do controlador indicado não existir, não faremos nada
        if (!class_exists($this->controlador)) {
            // Página não encontrada
            require_once ABSPATH . $this->not_found;
            return;
        } // class_exists
        // Cria o objeto da classe do controlador e envia os parâmentros
        $this->controlador = new $this->controlador($this->parametros);

        // Remove caracteres inválidos do nome da ação (método)
        $this->acao = preg_replace('/[^a-zA-Z]/i', '', $this->acao);

        // Se o método indicado existir, executa o método e envia os parâmetros
        if (method_exists($this->controlador, $this->acao)) {
            $this->controlador->{$this->acao}($this->parametros);
            return;
        } // method_exists
        // Sem ação, chamamos o método index
        if (!$this->acao && method_exists($this->controlador, 'index')) {
            $this->controlador->index($this->parametros);
            return;
        } // ! $this->acao 
        // Página não encontrada
        require_once ABSPATH . $this->not_found;
        return;
    }

    //A URL DEVE TER O SEGUINTE FORMATO
    //http://www.example.com/controlador/acao/parametro1/parametro2/etc...
    public function get_url_data() {
        // Verifica se o parâmetro path foi enviado
        if (isset($_GET['path'])) {
            $path = $_GET['path'];
            // Limpa os dados
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
            $path = explode('/', $path);
            // Configura as propriedades
            $this->controlador = chk_array($path, 0);
            $this->controlador .= '-controller';
            $this->acao = chk_array($path, 1);
            // Configura os parâmetros
            if (chk_array($path, 2)) {
                unset($path[0]);
                unset($path[1]);
                // Os parâmetros sempre virão após a ação
                $this->parametros = array_values($path);
            }
        }
    }

}
