<?php
require_once('./../conexiónBD.php');

$code = $_GET['code'] ?? '';
$mail = $_GET['mail'] ?? '';
$mensaje = '';
$esExito = false;

// Validar los parámetros GET
if (!$code || !$mail) {
    $mensaje = 'Código o correo incorrectos.';
} else {
    // Comprobar si el código y el correo son válidos
    $consulta = "SELECT * FROM users WHERE resetPassCode = ? AND mail = ?";
    $stmt = $db->prepare($consulta);
    $stmt->execute([$code, $mail]);

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el código ha expirado
        if (strtotime($usuario['resetPassExpiry']) > time()) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obtener la nueva contraseña
                $newPassword = $_POST['newPassword'] ?? '';
                $confirmPassword = $_POST['confirmPassword'] ?? '';

                if ($newPassword === $confirmPassword) {
                    // Actualizar la contraseña en la base de datos
                    $newPassHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $update = "UPDATE users SET passHash = ?, resetPassCode = NULL, resetPassExpiry = NULL WHERE iduser = ?";
                    $stmt = $db->prepare($update);
                    $stmt->execute([$newPassHash, $usuario['iduser']]);

                    // Enviar correo de confirmación
                    $subject = "Contraseña actualizada correctamente";
                    $message = "Hola, \n\nTu contraseña ha sido actualizada con éxito.";
                    $headers = "From: no-reply@tudominio.com";
                    mail($usuario['mail'], $subject, $message, $headers);

                    $mensaje = 'Contraseña actualizada con éxito.';
                    $esExito = true;
                } else {
                    $mensaje = 'Las contraseñas no coinciden.';
                }
            }
        } else {
            $mensaje = 'El enlace de reseteo ha expirado.';
        }
    } else {
        $mensaje = 'Código o correo incorrectos.';
    }
}

if ($esExito) {
    echo $mensaje;
    // Redirigir al inicio después de la actualización
    header("Location: ./../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
    /* Agregar algunos estilos básicos para mejorar la apariencia */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
        text-align: center;
    }

    h2 {
        color: #333;
    }

    form {
        margin-top: 20px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: inline-block;
        width: 100%;
        max-width: 400px;
    }

    input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    button {
        padding: 10px 20px;
        background-color: #5cb85c;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }

    button:hover {
        background-color: #4cae4c;
    }

    p {
        color: red;
    }
    </style>
</head>

<body>
    <h2>Restablecer Contraseña</h2>
    <p><?php echo $mensaje; ?></p>

    <?php if (!$esExito): ?>
    <form action="resetPassword.php?code=<?php echo $code; ?>&mail=<?php echo $mail; ?>" method="POST">
        <input type="password" name="newPassword" placeholder="Nueva Contraseña" required>
        <input type="password" name="confirmPassword" placeholder="Confirmar Contraseña" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>
    <?php endif; ?>
</body>

</html>