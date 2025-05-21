<?php
// Incluir la conexión
require_once './../conexiónBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];

    if ($pass !== $pass2) {
        die('Las contraseñas no coinciden.');
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die('Correo no válido.');
    }

    $hash = password_hash($pass, PASSWORD_BCRYPT);

    // Generar token aleatorio
    $token = bin2hex(random_bytes(32)); // 64 caracteres hex

    try {
        $sql = "INSERT INTO users (
                    mail, username, passHash, userFirstName, userLastName,
                    creationDate, removeDate, lastSignIn, active, token_activacio
                ) VALUES (
                    :mail, :username, :passHash, :firstName, :lastName,
                    NOW(), NULL, NOW(), 0, :token
                )";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':mail', $correo);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':passHash', $hash);
        $stmt->bindParam(':firstName', $nombre);
        $stmt->bindParam(':lastName', $apellidos);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        // Enviar email con token
        require_once './php-sendVerificat.php';

        header('Location: html-registroSuccess.html');
        exit;

    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            header("Location: ./html-registrarse.php?error=usuario_existente");
            exit;
        } else {
            header("Location: ./html-registrarse.php?error=otro");
            exit;
        }
    }
}
?>