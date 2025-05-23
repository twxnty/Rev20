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

header("Location: perfilUser.php");
exit;