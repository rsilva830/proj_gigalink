<?php

class Banco {

    //PARAMETROS PARA ACESSAR A BASE DE DADOS
    //UTILIZA DRIVER PDO
    public $host = 'localhost',
            $db_name = 'banco',
            $password = '',
            $user = 'root',
            $charset = 'utf8',
            $pdo = null,
            $error = null,
            $debug = false,
            $last_id = null;

    public function __construct($host = null, $db_name = null, $password = null, $user = null, $charset = null, $debug = null) {
        // CARREGA AS PROPRIEDADES QUE FORAM INFORMADAS NO ARQUIVO CONFIG.PHP
        // SE INFORMAR OS DADOS DIRETAMENTE NA CLASSE NAO E NECESSARIO INFORMAR NO ARQUIVO CONFIG.PH
        $this->host = defined('HOSTNAME') ? HOSTNAME : $this->host;
        $this->db_name = defined('DB_NAME') ? DB_NAME : $this->db_name;
        $this->password = defined('DB_PASSWORD') ? DB_PASSWORD : $this->password;
        $this->user = defined('DB_USER') ? DB_USER : $this->user;
        $this->charset = defined('DB_CHARSET') ? DB_CHARSET : $this->charset;
        $this->debug = defined('DEBUG') ? DEBUG : $this->debug;
        $this->connect();
    }

    final protected function connect() {
        //DETALHES DA CONEXAO PDO
        $pdo_details = "mysql:host={$this->host};";
        $pdo_details .= "dbname={$this->db_name};";
        $pdo_details .= "charset={$this->charset};";
        try {
            $this->pdo = new PDO($pdo_details, $this->user, $this->password);
            if ($this->debug === true) {
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }
            //DEPOIS DE CONECTADO AS PROPRIEDADES NAO SAO MAIS NECESSARIAS
            unset($this->host);
            unset($this->db_name);
            unset($this->password);
            unset($this->user);
            unset($this->charset);
        } catch (PDOException $e) {
            //VERIFICA SE DEVE DEBUGAR ERROS
            if ($this->debug === true) {
                echo "Erro: " . $e->getMessage();
            }
            die();
        }
    }

    public function query($stmt, $data_array = null) {
        $query = $this->pdo->prepare($stmt);
        $check_exec = $query->execute($data_array);
        //VERIFICA SE CONSULTA ACONTECEU E RETORNA
        //CASO CONTRARIO CONFIGURA O ERRO E RETORNA FALSE
        if ($check_exec) {
            return $query;
        } else {
            $error = $query->errorInfo();
            $this->error = $error[2];
            return false;
        }
    }

    public function insert($table) {
        // Configura o array de colunas
        $cols = array();
        // Configura o valor inicial do modelo
        $place_holders = '(';
        // Configura o array de valores
        $values = array();
        // O $j will assegura que colunas serão configuradas apenas uma vez
        $j = 1;
        // Obtém os argumentos enviados
        $data = func_get_args();
        // É preciso enviar pelo menos um array de chaves e valores
        if (!isset($data[1]) || !is_array($data[1])) {
            return;
        }
        // Faz um laço nos argumentos
        for ($i = 1; $i < count($data); $i++) {
            // Obtém as chaves como colunas e valores como valores
            foreach ($data[$i] as $col => $val) {
                // A primeira volta do laço configura as colunas
                if ($i === 1) {
                    $cols[] = "`$col`";
                }
                if ($j <> $i) {
                    // Configura os divisores
                    $place_holders .= '), (';
                }
                // Configura os place holders do PDO
                $place_holders .= '?, ';
                // Configura os valores que vamos enviar
                $values[] = $val;
                $j = $i;
            }
            // Remove os caracteres extra dos place holders
            $place_holders = substr($place_holders, 0, strlen($place_holders) - 2);
        }

        // Separa as colunas por vírgula
        $cols = implode(', ', $cols);
        // Cria a declaração para enviar ao PDO
        $stmt = "INSERT INTO `$table` ( $cols ) VALUES $place_holders) ";
        // Insere os valores
        $insert = $this->query($stmt, $values);
        // Verifica se a consulta foi realizada com sucesso
        if ($insert) {
            // Verifica se temos o último ID enviado
            if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()) {
                $this->last_id = $this->pdo->lastInsertId();
            }
            return $insert;
        }
        return;
    }

    public function LastInsertId() {
        return $this->last_id;
    }

    public function update($table, $where_field, $where_field_value, $values) {
        // Você tem que enviar todos os parâmetros
        if (empty($table) || empty($where_field) || empty($where_field_value)) {
            return;
        }
        // Começa a declaração
        $stmt = " UPDATE `$table` SET ";
        // Configura o array de valores
        $set = array();
        // Configura a declaração do WHERE campo=valor
        $where = " WHERE `$where_field` = ? ";
        // Você precisa enviar um array com valores
        if (!is_array($values)) {
            return;
        }
        // Configura as colunas a atualizar
        foreach ($values as $column => $value) {
            $set[] = " `$column` = ?";
        }
        // Separa as colunas por vírgula
        $set = implode(', ', $set);
        // Concatena a declaração
        $stmt .= $set . $where;
        // Configura o valor do campo que vamos buscar
        $values[] = $where_field_value;
        // Garante apenas números nas chaves do array
        $values = array_values($values);
        // Atualiza
        $update = $this->query($stmt, $values);
        // Verifica se a consulta está OK
        if ($update) {
            // Retorna a consulta
            return $update;
        }
        return;
    }

    public function delete($table, $where_field, $where_field_value) {
        // Você precisa enviar todos os parâmetros
        if (empty($table) || empty($where_field) || empty($where_field_value)) {
            return;
        }
        // Inicia a declaração
        $stmt = " DELETE FROM `$table` ";
        // Configura a declaração WHERE campo=valor
        $where = " WHERE `$where_field` = ? ";
        // Concatena tudo
        $stmt .= $where;
        // O valor que vamos buscar para apagar
        $values = array($where_field_value);
        // Apaga
        $delete = $this->query($stmt, $values);
        // Verifica se a consulta está OK
        if ($delete) {
            // Retorna a consulta
            return $delete;
        }
        return;
    }

}
