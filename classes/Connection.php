<?php
    require_once($_SERVER["DOCUMENT_ROOT"] .'/classloader.php');
    /**
     * Class Connection
     * Custom SQL connection interface with PDO
     */
    class Connection extends PDO{

        public function __construct(){
            try {
                //Fetching database configuration
                $dbdata = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini', true)['mysql'];
                $options = array(
                    PDO::ATTR_PERSISTENT => true, //Keep alive, connections are closed manually
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //Exception mode
                );
                //Establishing connection with PDO constructor
                parent::__construct('mysql:host='.$dbdata['host'].';port='.$dbdata['port'].';dbname='.$dbdata['db'].';charset=utf8' ,  $dbdata['user'], $dbdata['pw'], $options);
            }catch(Exception $e){
                http_response_code(500);
                die("Végzetes hiba történt a szerverrel való kommunikáció közben! Nem lehetett felépíteni az adatbázis kapcsolatot!");
            }
        }
    }
?>