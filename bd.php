<?php
$servidor = "localhost";//ip direccion
$basededatos = "facturacion";
$usuario = "root";
$contrasena = "";
try {
    $conexion = new PDO("mysql:host=$servidor; dbname=$basededatos", $usuario, $contrasena);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>;