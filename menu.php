<?php
$servidor = "localhost";
$basededatos = "facturacion";
$usuario = "root";
$contrasena = "";
//CONSULTA DE LA BD
try {
    $conexion = mysqli_connect($servidor, $usuario, '', $basededatos);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
// Validación de inicio de sesión de usuario
// Verificar si se ha enviado un tipo de comprobante para filtrar la tabla
if(isset($_POST['tipo_comprobante'])) {
    $tipo_comprobante = $_POST['tipo_comprobante'];
    // Consulta SQL filtrada por tipo de comprobante
    $sql = "SELECT * FROM documentos WHERE tipo_comprobante = '$tipo_comprobante'";
} else {
    // Consulta SQL sin filtrar
    $sql = "SELECT * FROM documentos";
}
$result = mysqli_query($conexion, $sql);
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="icon" type="image/png" href="img/favicon.png">
  <!--Inserción de boostrap-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!--TIPO DE FUENTE-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <!--INSERCIÓN DE CSS-->
  <link rel="stylesheet" type="text/css" href="css/disme.css"> <!--TOMAR EN CUENTA-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1S.0">
  <title>Documentos Electrónicos</title>
  <!--INSERCIÓN DE JQUERY-->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!--INSERCIÓN DE DATATABLE JQUERY-->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />
   <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
  <!--INSERCIÓN DE JSWEETALERS-->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="d-flex flex-column min-vh-100">
  <script> 
    Swal.fire({icon:"success",text:"Bienvenido! a Alimec"});
  </script>
<script>
    $(document).ready(function(){ 
    $("#tableid").DataTable({
      "pageLength":3,
      lengthMenu:[
      [3,10,25,50],
      [3,10,25,50]
    ],
    "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
    }
    });
  });  
  </script>
  <div class="usuario">
    <div class="container">
      <div class="row align-items-center py-3">
        <div class="col-md-6">
          <img src="img/alimeclogo.png" alt="Logo" style="height: 60px;">
          <!-- Ajusta la ruta y el tamaño según necesites -->
        </div>
        <div class="col-md-6 text-right">
          <h2>Documentos Electrónicos</h2>
          <h5>ALIMENTOS ECUATORIANOS S.A.</h5>
        </div>
      </div>
      <div class="row align-items-center py-3">
          <div class="col-md-6">
            <h4>Hola, Nombres Apellidos</h4>
          </div>
          <div class="col-md-6 text-right">
            <ul class="nav justify-content-end">
              <li class="nav-item">
                <a class="nav-link" href="cambiarcontrasena.html">Cambiar Contraseña </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.html">Salir <img src="img/salir.svg"></a>
              </li>
            </ul>
          </div>
      </div>
    </div>
  </div>
  <div class="tabla">
    <div class=" container">
      <div class="card">
        <h5 class="card-header">Detalle de Documentos en XML y PDF</h5>
        <div class="card-body">        
        <div class="row">
            <div class="col">
                <form class="form-inline justify-content-left"  method="post">
                  <label for="exampleInputEmail1"><h5>Consulta:</label>
                  <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Número de Comprobante" name="numero_comprobante">                    
                    <select class="form-control mb-2 mr-sm-2" name="tipo_comprobante">
                        <option value="">Selecciona un tipo</option>
                        <option value="factura">Factura</option>
                        <option value="nota_credito">Nota de Crédito</option>
                        <option value="nota_debito">Nota de Débito</option>
                        <option value="guia_remision">Guía de Remisión</option>
                        <option value="comprobante_retencion">Comprobante de Retención</option>
                        <!-- Añade más opciones según necesites -->
                    </select>
                    <button type="submit" class="btn btn-success mb-2">Buscar <img src="img/search.svg"></button>
                </form>
            </div>
        </div>
        </div>
      </div>
      <!--FUNCIONES APLICAR-->
      <!--PARA QUE SEA UNA TABLA RESPONSIVA DE LA BASE DE DATOS-->
      <div class="table-responsive">
        <table class="table">
          <table class="table table-bordered Regular shadow">
            <thead class="thead-light">
              <tr>
                <th scope="col">Ruc/Cédula</th>
                <th scope="col">Razón Social</th>
                <th scope="col">Fecha de Emisión</th>
                <th scope="col">T. de Comprobante</th>
                <th scope="col">N° de Documento</th>
                <th scope="col">V. Total</th>
                <th scope="col">Estado</th>
                <th scope="col">XML</th>
                <th scope="col">PDF</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($mostrar = mysqli_fetch_array($result)) {
                ?>
                <tr>
                  <th>
                    <?php echo $mostrar['ruc_cedula'] ?>
                  </th>
                  <td>
                    <?php echo $mostrar['razon_social'] ?>
                  </td>
                  <td>
                    <?php echo $mostrar['fecha_emision'] ?>
                  </td>
                  <td>
                    <?php echo $mostrar['tipo_comprobante'] ?>
                  </td>
                  <td>
                    <?php echo $mostrar['numero_documento'] ?>
                  </td>
                  <td>
                    <?php echo $mostrar['valor_total'] ?>
                  </td>
                  <td>
                    <?php echo $mostrar['estado'] ?>
                  </td>
                  <td>
                      <div class="Selecionar">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Seleccionar
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                          <a class="dropdown-item" href="/documentos/fac1.xml" target="_blank">Ver XML</a>                             
                          <a class="dropdown-item" href="/documentos/fac1.xml" download>Descargar</a>
                        </div>
                      </div>
                    <?php echo $mostrar['xml'] ?>
                  </td>
                  <td>
                  <div class="Selecionar">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Seleccionar
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                          <a class="dropdown-item" href="/documentos/fac1.pdf" target="_blank">Ver PDF</a>
                          <a class="dropdown-item" href="/documentos/fac1.pdf" download>Descargar</a>
                        </div>
                    </div>
                    <?php echo $mostrar['pdf'] ?>               
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
      </div>
      <div class="numeros">
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link">Anterior</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Siguiente</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
  <!--INDICAR PARA JALAR EL XML-->
  <?php
  //Espacio a realizar 
  ?>
  
  <footer class="bg-info text-light mt-auto">
    <div class="container">
      <div class="row py-3">
        <div class="col-md-6 text-md-left text-center">
          <p class="mb-0">© 2024 ALIMEC.</p>
        </div>
        <div class="col-md-6 text-md-right text-center">
          <a href="#" class="text-light mr-3">Todos los derechos reservados.</a>
        </div>
      </div>
    </div>
  </footer>
  <!--INSERCIÓN DE JS PARA USO DEL FRAMEWORK DE BOOSTRAP-->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
    <!--Cambio de Funciones en espec-->
  </script>
</body>
</html>