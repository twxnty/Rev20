<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: ./../dashboard/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rev. 20!</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<div id="particles-js"></div>

<body>
    <div class="wrapper">

        <form action="./php-loginIndex.php" method="POST">
            <h1>Rev. 20!</h1>
            <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 1): ?>
            <div class="error-msg">Credenciales incorrectas</div>
            <?php elseif ($_GET['error'] == 2): ?>
            <div class="error-msg">Debes activar tu cuenta primero</div>
            <?php endif; ?>
            <?php endif; ?>
            <p class="inicia">Inicia Sesión</p>
            <div class="input-box">
                <input type="text" placeholder="Username" name="login" required value="<?= $_COOKIE['login'] ?? '' ?>">
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Password" name="password" required
                    value="<?= $_COOKIE['password'] ?? '' ?>">
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox" name="recordar" <?= isset($_COOKIE['login']) ? 'checked' : '' ?>>Recordar
                    contraseña</label>

                <!-- Enlace a Forgot Password -->
                <p class="forgot-password">
                    <a href="#" id="forgot-password-link">¿Olvidaste tu contraseña?</a>
                </p>
            </div>

            <button type="submit" class="btn">Login</button>

            <div class="register-link">
                <p>¿Aún no tienes una cuenta?
                    <a href="./../registro/html-registrarse.php">¡Regístrate!</a>
                </p>

            </div>

        </form>
    </div>
    <!-- Popup para el reseteo de contraseña -->
    <div id="forgot-password-popup" class="popup">
        <div class="popup-content">
            <span id="close-popup">&times;</span>
            <h2>Restablecer contraseña</h2>
            <form action="resetPasswordSend.php" method="POST">
                <input type="text" name="login" placeholder="Ingresa tu nombre de usuario o correo" required>
                <button type="submit">Enviar email de reseteo</button>
            </form>
        </div>
    </div>

    <!-- Script JS para mostrar y cerrar el popup -->
    <script>
    document.getElementById("forgot-password-link").addEventListener("click", function() {
        document.getElementById("forgot-password-popup").style.display = "block";
    });

    document.getElementById("close-popup").addEventListener("click", function() {
        document.getElementById("forgot-password-popup").style.display = "none";
    });
    </script>

    <!-- Estilos básicos para el Popup -->
    <style>
    .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .popup-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: white;
    }

    #close-popup {
        cursor: pointer;
        font-size: 20px;
        color: #aaa;
    }
    </style>
</body>

</html>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./script.js"></script>