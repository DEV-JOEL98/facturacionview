<?php
// Inicia la sesión
session_start();

// Datos de conexión a la base de datos
$host = "localhost";
$dbname = "facturacion";
$username = "root";
$password = "";
$con = mysqli_connect($host, $username, $password, $dbname);  

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['cedula'];  
    $password = $_POST['password'];  
      
    // Evitar inyección SQL
    $username = stripcslashes($username);  
    $password = stripcslashes($password);  
    $username = mysqli_real_escape_string($con, $username);  
    $password = mysqli_real_escape_string($con, $password);  
    
    // Consulta SQL para buscar al usuario por su cédula y contraseña
    $sql = "SELECT * FROM usuarios WHERE cedula = '$username' AND password = '$password'";  
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);  
          
    if($count == 1){  
        // Usuario autenticado, iniciar sesión y redirigir al menú
        $_SESSION['username'] = $username;  
        header("Location: menu.php");  
    } else {  
        // Mostrar mensaje de error en una ventana emergente y redirigir al formulario de inicio de sesión
        echo "<script>alert('Error de inicio de sesion. Usuario o contraseña incorrectos.'); window.location.href = 'index.html';</script>";  
    }
} else {
    // Si no se envió ningún formulario, redirigir al formulario de inicio de sesión
    header("Location: index.html");
}

// Cerrar la conexión a la base de datos
mysqli_close($con);
?>
