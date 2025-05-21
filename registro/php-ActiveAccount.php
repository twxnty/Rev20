<?php
    require_once('./../conexiónBD.php');

    $token = $_GET['token'] ?? '';
    $mensaje = '';
    $esExito = false;

    if (!$token) {
        $mensaje = '❌ Token no proporcionado.';
    } else {
        $consulta = "SELECT token_activacio FROM users WHERE token_activacio = ?";
        $stmt = $db->prepare($consulta);
        $stmt->execute([$token]);

        if ($stmt->rowCount() > 0) {
            $update = "UPDATE users SET active = 1, token_activacio = NULL WHERE token_activacio = ?";
            $stmt = $db->prepare($update);
            $stmt->execute([$token]);
            $mensaje = '✅ ¡Has sido verificado correctamente!';
            $esExito = true;
        } else {
            $mensaje = '❌ Token incorrecto o ya ha sido usado.';
        }
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación de cuenta - Rev. 20</title>
    <link rel="stylesheet" href="./style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<div id="particles-js"></div>

<body>
    <div class="wrapper">
        <form>
            <h1>Rev. 20!</h1>
            <p class="inicia"><?php echo $mensaje; ?></p>

            <?php if ($esExito): ?>
            <button type="button" class="btn" onclick="window.location.href='./../login/index.php'">Ir al
                login</button>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./script.js"></script>