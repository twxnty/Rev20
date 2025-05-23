<?php
session_start();
require_once('./../conexiónBD.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./../login/index.php");
    exit;
}

$idrev = $_POST['idrev'];
$iduser = $_SESSION['usuario_id'];
$comentario = trim($_POST['comentario']);

if (!empty($comentario)) {
    // Insertar comentario
    $stmt = $db->prepare("INSERT INTO comentarios (idrev, iduser, comentario) VALUES (?, ?, ?)");
    $stmt->execute([$idrev, $iduser, $comentario]);

    // Obtener ID del usuario dueño del rev
    $stmt = $db->prepare("SELECT iduser FROM revs WHERE idrev = ?");
    $stmt->execute([$idrev]);
    $revData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($revData) {
        $idUserDelRev = $revData['iduser'];

        // Obtener su username
        $stmt = $db->prepare("SELECT username FROM users WHERE iduser = ?");
        $stmt->execute([$idUserDelRev]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $a = htmlspecialchars($userData['username']);
            header("Location: verPerfil.php?usuario=$a");
            exit;
        } else {
            echo "No se encontró el usuario dueño del rev";
        }
    } else {
        echo "Rev no encontrado 🥴";
    }
} else {
    echo "Comentario vacío. ¡No seas tímido!";
}