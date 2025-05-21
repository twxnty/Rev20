<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: ./../dashboard/home.html");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Rev. 20</title>
    <link rel="stylesheet" href="./style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<div id="particles-js"></div>

<body>
    <div class="wrapper">
        <form action="./php-registro.php" method="POST">
            <h1>Rev. 20!</h1>
            <p class="inicia">Regístrate</p>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'usuario_existente'): ?>
            <div class="error-msg">Este usuario o dirección de correo ya está en uso.</div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 'otro'): ?>
            <div class="error-msg">Ha ocurrido un error inesperado. Inténtalo de nuevo.</div>
            <?php endif; ?>

            <div class="input-box">
                <input type="text" placeholder="Usuario" name="username" required>
                <i class='bx bxs-user'></i>
            </div>


            <div class="input-box">
                <input type="text" placeholder="Nombre" name="nombre" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Apellidos" name="apellidos" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="email" placeholder="Correo electrónico" name="correo" required>
                <i class='bx bxs-envelope'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Contraseña" name="pass" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Confirmar contraseña" name="pass2" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <button type="submit" class="btn">Registrarse</button>

            <div class="register-link">
                <p>¿Ya tienes una cuenta?
                    <a href="./../login/index.php">Inicia sesión</a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./script.js"></script>