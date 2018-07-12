<?php

class MainController {

    public $db;
    public $title;
    public $parametros = array();

    public function __construct($parametros = array()) {
        $this->db = new Banco();
        $this->parametros = $parametros;
    }

    public function load_model($model_name = false) {
        if (!$model_name)
            return;
        // Garante que o nome do modelo tenha letras minúsculas
        $model_name = strtolower($model_name);
        $model_path = ABSPATH . '/models/' . $model_name . '.php';
        // Verifica se o arquivo existe
        if (file_exists($model_path)) {
            require_once $model_path;
            $model_name = explode('/', $model_name);
            $model_name = end($model_name);
            // Remove caracteres inválidos do nome do arquivo
            $model_name = preg_replace('/[^a-zA-Z0-9]/is', '', $model_name);
            // Verifica se a classe existe
            if (class_exists($model_name)) {
                return new $model_name($this->db, $this);
            }
            return;
        }
    }

}
