<?php
// Inicia la sesión
session_start();

// Datos de conexión a la base de datos
$host = "localhost";
$dbname = "facturacion";
$username = "root";
$password = "";

try {
    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar usuario y contraseña del formulario
        $cedula = $_POST['username'];
        $pass = $_POST['password'];
        
        // Verificar si se han ingresado datos en ambos campos
        if (empty($cedula) || empty($pass)) {
            echo "<script>alert('Por favor, rellena todos los campos.');</script>";
            echo "<script>window.location.href = 'index.html';</script>";
            exit();
        } else {
            // Consulta SQL para buscar al usuario por su cédula
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE cedula = :cedula");
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el usuario existe y la contraseña es correcta
            if ($user) {
                // Verificar la contraseña utilizando password_verify
                if (password_verify($pass, $user['password'])) {
                    $_SESSION['username'] = $cedula;
                    header("Location: menu.php");
                    exit();
                } else {
                    echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
                    echo "<script>window.location.href = 'index.html';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
                echo "<script>window.location.href = 'index.html';</script>";
                exit();
            }
        }
    }
} catch(PDOException $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    echo "<script>window.location.href = 'index.html';</script>";
    exit();
}
?>