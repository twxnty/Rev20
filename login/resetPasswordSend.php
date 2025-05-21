<?php
// Datos de la cuenta de Gmail
$gmail = "israel.lainesm@educem.net";
$contraseña = "dwbm sgit ajma lfnx";

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$mail = new PHPMailer();
$mail->IsSMTP();

// Configuración del servidor de correo
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;

// Credenciales del cuenta de Gmail
$mail->Username = $gmail;
$mail->Password = $contraseña;

// Datos del correo electrónico
$mail->SetFrom($gmail, 'Rev. 20!');
$mail->Subject = 'Restablecimiento de contraseña en Rev. 20!';
$mail->MsgHTML("
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <style>
        /* Agrega tus estilos aquí, o usa los mismos que en el correo de verificación */
    </style>
</head>
<body>
    <div class='container'>
        <h1>¡Restablecer contraseña en Rev. 20!</h1>
        <p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>
        <a class='btn' href='http://localhost/M9-PROYECTO/resetPassword.php?token=$token'>Restablecer contraseña</a>
        <p class='footer'>Si no has solicitado este restablecimiento, por favor ignora este mensaje.</p>
    </div>
</body>
</html>
");

// Asumiendo que el correo es el que viene del formulario
$correo = $_POST['login']; // Este valor debería venir de un formulario en el que el usuario ingresa su correo o nombre de usuario

// Agregar destinatario
$mail->AddAddress($correo, 'Usuario');

// Enviar el correo
$result = $mail->Send();

if (!$result) {
    echo 'Error: ' . $mail->ErrorInfo;
} else {
    echo 'Correo de restablecimiento enviado correctamente.';
}
?>