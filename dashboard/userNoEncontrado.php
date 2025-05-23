<?php
session_start();
require_once('./../conexiónBD.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ./../login/index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Rev 20!</title>
    <link rel="stylesheet" href="./dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="./../imagenes/favicon.png">
    <style>
    body {
        background: #1d1d1d;
    }

    * {
        color: <?php echo $carStyle['textColor'];
        ?>;
    }

    .logo {
        color: <?php echo $carStyle['textColor'];
        ?>;
    }

    .navbar a {

        color: <?php echo $carStyle['textColor'];
        ?>;

    }

    .home .rhombus2 {
        background: <?php echo $carStyle['romboColor'];
        ?>;
    }

    .home-img .rhombus {
        border: 25px solid <?php echo $carStyle['romboColor'];
        ?>;

        background: <?php echo $carStyle['background'];
        ?>;
    }

    .btn {

        background: <?php echo $carStyle['btn'];
        ?>;
        color: <?php echo $carStyle['background'];
        ?>;
        border: 2px solid <?php echo $carStyle['btn-border'];
        ?>;
    }

    .btn-Guardar {
        background: <?php echo $carStyle['btn'];
        ?>;
        color: <?php echo $carStyle['background'];
        ?>;
        border: 2px solid <?php echo $carStyle['btn-border'];
        ?>;
    }

    .btn:hover {
        border: 2px solid <?php echo $carStyle['background'];
        ?>;
        color: <?php echo $carStyle['btn'];
        ?>;
    }

    .home-content h3 {
        color: <?php echo $carStyle['rev1'];
        ?>;
    }

    .profile-container {
        max-width: 1000px;
        margin: 120px auto 0;
        padding: 20px;
        animation: slideTop 1s ease forwards;
        opacity: 0;
        transform: translateY(50px);
    }

    .profile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        border-bottom: 2px solid <?php echo $carStyle['textColor'];
        ?>22;
        padding-bottom: 20px;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info h2 {
        font-size: 32px;
        margin-bottom: 10px;
        animation: slideRight 1s ease forwards;
        animation-delay: 0.5s;
        opacity: 0;
    }

    .profile-info p {
        font-size: 16px;
        opacity: 0.7;
        animation: slideLeft 1s ease forwards;
        animation-delay: 0.7s;
        opacity: 0;
    }

    .stats {
        display: flex;
        gap: 30px;
        margin-top: 10px;
        animation: zoomOut 1s ease forwards;
        animation-delay: 0.9s;
        opacity: 0;
    }

    .stats div {
        text-align: center;
    }

    .btn-edit {
        @extend .btn;
        /* Si usas Sass (opcional) */

        background: <?php echo $carStyle['btn'];
        ?>;
        color: black;
        border: 2px solid <?php echo $carStyle['btn-border'];
        ?>;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
        font-weight: bold;
        text-align: center;
    }

    .btn-edit:hover {
        border: 2px solid <?php echo $carStyle['background'];
        ?>;
        color: white;
        background: <?php echo $carStyle['background'];
        ?>;
    }

    .feed-placeholder {
        margin-top: 40px;
        text-align: center;
        opacity: 0.5;
        animation: slideBottom 1s ease forwards;
        animation-delay: 1.3s;
        opacity: 0;
    }

    .profile-pic {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 20px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 0 0 transparent;
    }

    .profile-pic:hover {
        border-color: white;
        box-shadow: 0 0 15px white;
    }

    .edit-profile-form {
        padding: 20px;
        border-radius: 12px;
        margin-top: 15px;
        color: #eee;
    }

    .edit-profile-form textarea {
        width: 100%;
        background: #2b2b2b;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 8px;
    }

    .colorBio {

        color: black;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(40px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animated {
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
    }

    .edit-profile-form {
        display: flex;
        flex-direction: column;
    }

    .edit-profile-form textarea {
        width: 100%;
        resize: vertical;
    }

    .button-container {
        text-align: center;
    }

    .subirFotoCuadro {
        margin-bottom: 25px;
    }

    .textoDescrp {
        margin-bottom: 35px;
    }

    .tx {
        margin-bottom: 7px;
    }

    .custom-file-upload {
        display: inline-block;
        padding: 10px 20px;
        cursor: pointer;
        border: 2px dashed #666;
        color: #fff;
        background-color: #2b2b2b;
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .custom-file-upload:hover {
        background-color: #444;
        border-color: #aaa;
    }

    .custom-file-upload input[type="file"] {
        display: none;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
        padding: 10px 20px;
        flex-wrap: wrap;
    }

    .navbar .nav-links {
        display: flex;
        gap: 15px;
    }

    .navbar .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 6px 12px;
        border-radius: 4px;
        transition: background 0.3s;
    }

    .navbar .nav-links a:hover {
        background: #333;
    }

    .search-form {
        display: flex;
        align-items: center;
        border: 2px solid white;
        border-radius: 6px;
        padding: 3px 8px;
        background-color: transparent;
        margin-left: auto;
    }

    .search-form input {
        background: transparent;
        border: none;
        outline: none;
        color: <?php echo $carStyle['busqueda'];
        ?>;
        font-size: 16px;
    }

    .search-form button {
        background: none;
        border: none;
        cursor: pointer;
        color: white;
        font-size: 18px;
        margin-left: 5px;
    }

    .mensaje-error {
        text-align: center;
        background-color: #2b2b2b;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 25px rgb(255 255 255 / 1%);
        animation: aparecer 1s ease-in-out;
    }

    @keyframes aparecer {
        0% {
            transform: translateY(30px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .mensaje-error h2 {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .mensaje-error p {
        font-size: 18px;
        opacity: 0.7;
    }
    </style>
</head>

<body>
    <header class="header">
        <a href="dashboard.php" class="logo">Rev 20!</a>
        <nav class="navbar">
            <form action="verPerfil.php" method="GET" class="search-form">
                <input type="text" name="usuario" placeholder="Buscar usuario..." required>
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
            <a href="perfilUser.php" style="--i:4;">Mi Perfil</a>
            <a href="logout.php" style="--i:5;">Cerrar sesión</a>
        </nav>
    </header>

    <div class="profile-container">
        <div class="mensaje-error">
            <h2>🚫 Usuario no encontrado</h2>
            <p>El usuario que buscas no existe, no es un aficionado de coches verdadero, no le hables más.</p>
        </div>
    </div>

</body>

</html>