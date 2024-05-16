<?php
// Incluir conexión a la base de datos
$servidor = "localhost";//ip direccion
$basededatos = "facturacion";
$usuario = "root";
$contrasena = "";
try {
    $conexion = new PDO("mysql:host=$servidor; dbname=$basededatos", $usuario, $contrasena);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['email'])) { //condición de acceso validando las consultas
    $email = $_POST['email'];
    //Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->rowCount() > 0){        
        //funcion para poder realizar envío de correoSSSS        
        function sendMail($subject,$body,$email,$name, $html = false)
        {
            //Funciones específicas para lograr enviar las credenciales a través del correo
            //Configuración del servidor
            $phpmailer -> new PHPMailer();                              //Habilitar salida de depuración detallada 
            $phpmailer -> isSMTP();                                       //Enviar usando SMTP 
            $phpmailer -> Host  = 'smtp.gmail.com' ;                     //Configura el servidor SMTP para enviar a través de 
            $phpmailer -> SMTPAuth  = true ;
            $phpmailer -> SMTPSecure   = PHPMailer::ENCRYPTION_SMTPS;                                   //Habilitar autenticación SMTP 
            $phpmailer -> Port  = 465; 
            $phpmailer -> Username  = 'gilberjoelmejor@gmail.com' ;                     //Nombre de usuario SMTP 
            $phpmailer -> Password  = 'vydw eesk eztc vvkp' ;                               //Contraseña SMTP 
            $phpmailer -> setFrom ('from@example.com' , 'PRUEBA' );
            $phpmailer -> addAddress($mail,$name);     //Agregar un destinatario 
        }        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Genera un token único para la recepción
        $token = bin2hex(random_bytes(32));
        // Guardar el token en la base de datos con una marca de tiempo
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // El token expira en 1 hora
        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = :token, token_expires_at = :expires_at WHERE email = :email");
        $stmt->execute([':token' => $token, ':expires_at' => $expires_at, ':email' => $email]);        
        //Enviar mediante el MAIL
        $resetLink = "http://tu-sitio.com/restablecer_password.php?token=$token";
        $message = "Haz clic aquí para restablecer tu contraseña: $resetLink";
        // Aquí deberías usar la función mail() de PHP o una biblioteca de correo para enviar el mensaje
        // mail($email, "Restablece tu contraseña", $message);
        echo "Si tu correo electrónico coincide con una cuenta en nuestro sistema, recibirás un enlace para restablecer tu contraseña.";
    } else {
        echo "Si tu correo electrónico coincide con una cuenta en nuestro sistema, recibirás un enlace para restablecer tu contraseña.";
    }
}
?>