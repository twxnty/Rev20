<?php
session_start();

// Eliminar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Eliminar cookies si se usó "recordarme"
if (isset($_COOKIE['login'])) {
    setcookie("login", "", time() - 3600, "/");
}
if (isset($_COOKIE['password'])) {
    setcookie("password", "", time() - 3600, "/");
}

// Redirigir al inicio o login
header("Location: ./../login/index.php");
exit;
?>