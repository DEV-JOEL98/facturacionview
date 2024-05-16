<?php
session_start();
// Verifica si el usuario está logueado
// Datos de conexión a la base de datos
$host = "localhost";
$dbname = "facturacion";
$username = "root";
$password = "";
// Conexión a la base de datos
try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_new_password = $_POST['confirm_new_password'];
        // Asegúrate de validar y sanear las entradas aquí
        // Verificar que la nueva contraseña y la confirmación coincidan
        if ($new_password != $confirm_new_password) {
            echo "Las nuevas contraseñas no coinciden.";
            exit();
        }
        // Obtener la contraseña actual del usuario de la base de datos
        $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['id_usuario']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verificar que la contraseña actual es correcta
        if (!password_verify($current_password, $user['password'])) {
            echo "La contraseña actual es incorrecta.";
            exit();
        }
        // Actualizar la contraseña en la base de datos en base al registro
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = :new_password WHERE id = :id");
        $stmt->bindParam(':new_password', $new_password_hash);
        $stmt->bindParam(':id', $_SESSION['id_usuario']);
        $stmt->execute();
        echo "Contraseña cambiada exitosamente.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>;