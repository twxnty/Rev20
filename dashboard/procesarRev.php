<?php
session_start();
require_once('./../conexiónBD.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ./../../login/index.php');
    exit;
}

$iduser = $_SESSION['usuario_id'];
$descripcion = $_POST['descripcion'];
$imagenNombre = $_FILES['imagen']['name'];


$tmpRuta = $_FILES['imagen']['tmp_name'];
$nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
$rutaDestino = "./../imagenes/revs/" . $nombreArchivo;

if (!file_exists("./../imagenes/revs")) {
    mkdir("./../imagenes/revs", 0777, true);
}

if (redimensionarImagen($tmpRuta, $rutaDestino, 800, 800)) {
    // Redimensionado y guardado correctamente, ahora guarda en la base de datos
    $stmt = $db->prepare("INSERT INTO revs (iduser, descripcion, imagen) VALUES (?, ?, ?)");
    $stmt->execute([$iduser, $descripcion, $nombreArchivo]);

    echo "<script>
        alert('Rev subido correctamente!');
        window.location.href = 'dashboard.php';
    </script>";
} else {
    echo "Error al redimensionar la imagen.";
}


function redimensionarImagen($rutaOrigen, $rutaDestino, $anchoMax, $altoMax) {
    list($ancho, $alto, $tipo) = getimagesize($rutaOrigen);

    $proporcion = min($anchoMax / $ancho, $altoMax / $alto);
    $nuevoAncho = $ancho * $proporcion;
    $nuevoAlto = $alto * $proporcion;

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $origen = imagecreatefromjpeg($rutaOrigen);
            break;
        case IMAGETYPE_PNG:
            $origen = imagecreatefrompng($rutaOrigen);
            break;
        case IMAGETYPE_GIF:
            $origen = imagecreatefromgif($rutaOrigen);
            break;
        default:
            return false;
    }

    $imagenNueva = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
    imagecopyresampled($imagenNueva, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

    // Guardar la nueva imagen
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            imagejpeg($imagenNueva, $rutaDestino, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($imagenNueva, $rutaDestino);
            break;
        case IMAGETYPE_GIF:
            imagegif($imagenNueva, $rutaDestino);
            break;
    }

    imagedestroy($origen);
    imagedestroy($imagenNueva);

    return true;
}   
?>