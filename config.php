<?php
//CONFIGURACOES GERAIS

//CAMINHO PARA RAIZ
define( 'ABSPATH', dirname( __FILE__ ) );

//URL DA HOME
define( 'HOME_URI', 'http://www.site.com.br/gigalink' );

//DADOS DA BASE DE DADOS
define( 'HOSTNAME', 'localhost' );
define( 'DB_NAME', 'gigalink' );
define( 'DB_USER', 'gigalink_user' );
define( 'DB_PASSWORD', 'password' );
define( 'DB_CHARSET', 'utf8' );

//CONSTANTE PARA NUMERO DE REGISTROS POR PAGINA, UTILIZADO NA PAGINACAO
define( 'REG_PG', 5);

//EM AMBIENTE DE PRODUCAO MUDAR DEBUG PARA FALSE
define( 'DEBUG', true );

?>