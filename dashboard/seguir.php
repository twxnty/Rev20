<?php
session_start();
require_once('./../conexiónBD.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./../login/index.php");
    exit;
}



$seguidor_id = $_SESSION['usuario_id'];
$seguido_id = $_POST['seguido_id'];

$accion = $_POST['accion'];

if ($accion === "follow") {
    if ($seguidor_id !== $seguido_id) {
        // Verificar si ya lo sigue
        $stmt = $db->prepare("SELECT * FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?");
        $stmt->execute([$seguidor_id, $seguido_id]);
    
        if ($stmt->rowCount() === 0) {
            // Insertar seguimiento
            $insert = $db->prepare("INSERT INTO seguidores (seguidor_id, seguido_id) VALUES (?, ?)");
            $insert->execute([$seguidor_id, $seguido_id]);
        }
    }
} elseif ($accion === "unfollow") {
    $delete = $db->prepare("DELETE FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?");
    $delete->execute([$seguidor_id, $seguido_id]);
}

// Obtener username del seguido
$stmt = $db->prepare("SELECT username FROM users WHERE iduser = ?");
$stmt->execute([$seguido_id]);
$seguido_username = $stmt->fetchColumn();


// Volver al perfil
header("Location: verPerfil.php?usuario=" . $seguido_username);
exit;