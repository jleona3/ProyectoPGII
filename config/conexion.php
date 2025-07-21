<?php
class Conectar {
    protected $dbh;

    protected function Conexion() {
        try {
            $conectar = $this->dbh = new PDO(
                "sqlsrv:Server=.\SQLEXPRESS;Database=TRZ6_CONDOMINIO",
                "sa",
                "jleon1987"
            );
            $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conectar;
        } catch (Exception $e) {
            print "Error Conexión BD: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public static function ruta() {
        return "http://localhost:90/ProyectoPGII/";
    }
}
?>