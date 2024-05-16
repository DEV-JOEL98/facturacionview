<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturacion");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
// Leer el archivo de texto
$archivo = fopen("archive/FACT.txt", "r");
$archivocliente = fopen("archive/MOVILCLIENTE.txt", "r");

// Procesar el archivo línea por línea
while (!feof($archivo)) { 
    $linea = fgets($archivo); 
    $linea = str_replace(array('<', '>'), '', $linea); //Cleaning of caracters '<' y '>' in word
    $datos = explode(";", $linea); // Suppose what this data is in (;)
    
    //Realizar el ingreso de datos tabla por tabla y sus registros actuales
    $cliente_query = "INSERT INTO cliente (razonSocial, nombreComercial, direccion, celular, ciudad, ruc, email, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $cliente_statement = $conexion->prepare($cliente_query);
    $cliente_statement->bind_param("ssssssss",$datos,$datos,$datos,$datos,$datos,$datos,$datos,$datos); //verificar como se esta ingresando los datos. ¡IMPORTANTE!
    $cliente_statement->execute();

    $cliente_query = "INSERT INTO factura (cliente_id,ambiente, tipoEmision, razonSocial, nombreComercial, ruc, claveAcceso, codDoc, estab, ptoEmi, secuencial,
dirMatriz, fechaEmision, contribuyenteEspecial, obligadoContabilidad, tipoIdentificacionComprador, razonSocialComprador, identificacionComprador, totalSinImpuestos,
totalDescuento, propina, importeTotal, moneda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //select edit 
    $cliente_statement = $conexion->prepare($cliente_query);
    $cliente_statement->bind_param("ssssssss", ...$datos);
    $cliente_statement->execute();
    
    //import for llenar datos de cada espacio a selected versión, diferent
    $cliente_query = "INSERT INTO detalle_factura(codigoPrincipal, codigoAuxiliar, descripcion, cantidad, precioUnitario, descuento, precioTotalSinImpuesto, codigoImpuesto, codigoPorcentaje, tarifa, baseimponible, valor) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $cliente_statement = $conexion->prepare($cliente_query);
    $cliente_statement->bind_param("ssssssss", ...$datos);
    $cliente_statement->execute();
    //Read data for TXT.
        
    $cliente_query = "INSERT INTO info_adicional(nombreCampo,valorCampo) VALUES (?,?)";
    $cliente_statement = $conexion->prepare($cliente_query);
    $cliente_statement->bind_param("ssssssss", ...$datos);
    $cliente_statement->execute();
}

//ciclo iterativo para que pueda tomar los datos de clientes
while (!feof($archivocliente)) {
    $linea = fgets($archivocliente);
    $datos = str_getcsv($linea, ";", '"'); // Separar los datos usando coma, permitiendo espacios dentro de comillas dobles
    
    // Verificar si se han obtenido datos válidos
    if (count($datos) >= 24) { //consulta de ingreso de datos que son válidos de diferente forma
        $cliente_query = "INSERT INTO cliente (clave, razonSocial, nombreComercial, direccion, celular, ciudad, ruc, email, contrasena) VALUES (?,?,?,?,?,?,?,?,?)";
        // Preparar la consulta
        $cliente_statement = $conexion->prepare($cliente_query);        
        // Verificar si la preparación de la consulta fue exitosa
        if ($cliente_statement) { //if-consult a new user diferent manage a selected practices
            if($user){
                $cliente_statement->data_seek(); //in the activities manage for client
            }
            //insert data a database
            $cliente_statement->bind_param("ssssssssssssssssssssssss", ...$datos);
            $cliente_statement->execute(); //run of select ó interfas actions for dates
            //work use of diferent class for selected use manage diferent practices            
            //Use of diferent manage for selected activities for data base. // use a new interfaz manage for use activitie
            
            if ($cliente_statement->affected_rows == -1) {
                echo "Error al insertar datos: " . $cliente_statement->error;
            }
        } else { //select for diferent case practices script use PHP
            echo "Error en la preparación de la consulta: " . $conexion->error;
        }
    } else {
        echo "Datos insuficientes en la línea: " . $linea;
    }
}
//Finalizar la conexión de uso respectivo como fuente de entrega a los otros factores
fclose($archivo);
fclose($archivocliente);
$conexion->close();
echo "Datos insertados correctamente."; //Mensaje Exitoso
?>