<?php
    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try{
                $conectar = $this->dbh=new PDO("sqlsrv:Server=.\SQLEXPRESS;TRZ6_CONDOMINIO","sa","jleon1987");
                return $conectar;
            }catch(Exception $e){
                print "Error Conexión BD". $e->getMessage() . "<br/>";
                die();
            }
        }
    }
?>