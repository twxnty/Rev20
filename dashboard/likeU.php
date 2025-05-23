<?php
session_start();
require_once('./../conexiónBD.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./../login/index.php");
    exit;
}

$idrev = $_POST['idrev'];
$iduser = $_SESSION['usuario_id'];

// Evitar duplicado
$stmt = $db->prepare("SELECT * FROM likes WHERE idrev = ? AND iduser = ?");
$stmt->execute([$idrev, $iduser]);

if ($stmt->rowCount() == 0) {
    $insert = $db->prepare("INSERT INTO likes (idrev, iduser) VALUES (?, ?)");
    $insert->execute([$idrev, $iduser]);
}

// 1. Buscar el id del usuario dueño del rev
$stmt = $db->prepare("SELECT iduser FROM revs WHERE idrev = ?");
$stmt->execute([$idrev]);
$revData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($revData) {
    $idUserDelRev = $revData['iduser'];

    // 2. Buscar su username
    $stmt = $db->prepare("SELECT username FROM users WHERE iduser = ?");
    $stmt->execute([$idUserDelRev]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $a = htmlspecialchars($userData['username']);

    header("Location: verPerfil.php?usuario=$a");
} 