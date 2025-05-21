<?php
session_start();
require_once './../conexiónBD.php'; // ¡Usa tu conexión correcta!

$iduser = $_SESSION['usuario_id'] ?? null;
if (!$iduser) {
    header('Location: ./../login/index.php');
    exit;
}

$bio = trim($_POST['bio'] ?? '');

// Manejo de la imagen de perfil
$profileImage = null;

if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array(strtolower($ext), $allowed)) {
        $newFileName = uniqid("profile_", true) . "." . $ext;
        $uploadDir = __DIR__ . "/../imagenes/profiles/";
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadPath)) {
            $profileImage = $newFileName;
        }
    }
}

// Armamos la query dinámica
$query = "UPDATE users SET bio = ?";
$params = [$bio];

if ($profileImage) {
    $query .= ", profileImage = ?";
    $params[] = $profileImage;
}

$query .= " WHERE iduser = ?";
$params[] = $iduser;

$stmt = $db->prepare($query);
$stmt->execute($params);

// Volvemos al perfil
header("Location: perfilUser.php");
exit;