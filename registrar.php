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
        // Recuperar datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $cedula = $_POST['cedula'];
        $password = $_POST['password'];

        // Verificar si el correo electrónico ya está registrado
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "El correo electrónico ya está registrado.";
            echo "<script>alert('El correo electrónico ya está registrado.');</script>";
            echo "<script>document.getElementById('email').value = '';</script>";
            echo "<script>window.location.href = 'registrar.html';</script>";
            exit();
        }

        // Verificar si la cédula ya está registrada
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE cedula = :cedula");
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "La cédula ya está registrada.";
            echo "<script>alert('La cédula ya está registrada.');</script>";
            echo "<script>document.getElementById('cedula').value = '';</script>";
            echo "<script>window.location.href = 'registrar.html';</script>";
            exit();
        }

        // Si no hay duplicados, insertar nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, cedula, password) VALUES (:nombre, :apellido, :email, :cedula, :password)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $_SESSION['success'] = "Usuario registrado exitosamente. Puedes iniciar sesión ahora.";
        echo "<script>alert('Usuario registrado exitosamente. Puedes iniciar sesión ahora.');</script>";
        header("Location: index.html");
        exit();
    }
} catch (PDOException $e) { //revisión en el caso que no se pueda acceder
    $_SESSION['error'] = "Error: " . $e->getMessage();
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    echo "<script>window.location.href = 'registrar.html';</script>";
    exit();
}
?>


