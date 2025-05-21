<?php
require_once('./../conexiónBD.php');
session_start();

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($login) || empty($password)) {
    header("Location: ./../index.html?error=1");
    exit;
}

// Determinar si es un correo o un nombre de usuario
$campo = filter_var($login, FILTER_VALIDATE_EMAIL) ? "mail" : "username";

$consulta = "SELECT * FROM users WHERE $campo = ?";
$stmt = $db->prepare($consulta);
$stmt->execute([$login]);

if ($stmt->rowCount() > 0) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $usuario['passHash'])) {
        header("Location: ./index.php?error=1"); // credenciales incorrectas
        exit;
    }

    // Aquí comprobamos si la cuenta está activa
    if ($usuario['active'] != 1) {
        header("Location: ./index.php?error=2"); // cuenta no activada
        exit;
    }

    // Iniciar sesión si todo está bien
    $_SESSION['usuario_id'] = $usuario['iduser'];
    $_SESSION['usuario'] = $usuario['username'];

    // Guardar cookies si se marcó "Recordar"
    if (isset($_POST['recordar'])) {
        setcookie("login", $login, time() + (86400 * 30), "/");
        setcookie("password", $password, time() + (86400 * 30), "/");
    } else {
        setcookie("login", "", time() - 3600, "/");
        setcookie("password", "", time() - 3600, "/");
    }

    header("Location: ./../dashboard/dashboard.php");
    exit;
}


header("Location: ./index.php?error=1"); // credenciales incorrectas
exit;
?>