<?php
    class Conectar{
        protected $dbh;
        
        protected function Conexion(){
            try{
                $conectar = $this->dbh=new PDO("sqlsrv:Server=DESKTOP-QA9NUHN\SQLEXPRESS;DataBase=TRZ6_CONDOMINIO","sa","jleon1987");
            }catch(Exception $e){
                print "Error Conexión BD". $e->getMessage() ."br/>";
                die();
            }
        }
    }
?>