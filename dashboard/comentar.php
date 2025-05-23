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
    $stmt = $db->prepare("INSERT INTO comentarios (idrev, iduser, comentario) VALUES (?, ?, ?)");
    $stmt->execute([$idrev, $iduser, $comentario]);
}

header("Location: perfilUser.php");
exit;