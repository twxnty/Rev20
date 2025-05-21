<!-- perfiluser.php -->
<?php
session_start();
require_once('./../conexiónBD.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ./../login/index.php');
    exit;
}

$iduser = $_SESSION['usuario_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE iduser = ?");
$stmt->execute([$iduser]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nombreUsuario = htmlspecialchars($_SESSION['usuario'] ?? 'Invitado');

// Obtener datos del usuario
$stmt = $db->prepare("SELECT * FROM users WHERE iduser = ?");
$stmt->execute([$iduser]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$nombreUsuario = htmlspecialchars($user['username'] ?? 'Invitado');

// Obtener número de seguidores
$stmt = $db->prepare("SELECT COUNT(*) FROM follows WHERE following_id = ?");
$stmt->execute([$iduser]);
$totalSeguidores = $stmt->fetchColumn();

// Obtener número de seguidos
$stmt = $db->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = ?");
$stmt->execute([$iduser]);
$totalSiguiendo = $stmt->fetchColumn();

// Obtener revs del usuario
$stmt = $db->prepare("SELECT * FROM revs WHERE iduser = ? ORDER BY fecha DESC");
$stmt->execute([$iduser]);
$revs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$profileImagePath = "./../imagenes/profiles/" . ($user['profileImage'] ?? 'default.jpg');
if (!file_exists($profileImagePath) || empty($user['profileImage'])) {
    $profileImagePath = "./../imagenes/profiles/default.jpg";
}

// Asignamos estilos como en dashboard.php
$carImages = [
    "./../imagenes/lamborghini.png" => ["background" => "#1d1d1d"],
];
$randomCar = array_rand($carImages);    
$carStyle = $carImages[$randomCar];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo $nombreUsuario; ?> - Rev20!</title>
    <link rel="stylesheet" href="./dashboard.css">
    <style>
    body {
        background: <?php echo $carStyle['background'];
        ?>;
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

    /* CONTENEDOR DE MODAL: pantalla completa y oscura */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
    }

    /* MOSTRAR MODAL USANDO FLEXBOX */
    .modal.show {
        display: flex;
    }

    /* CAJA DEL MODAL */
    .modal-content {
        background-color: #1e1e1e;
        color: white;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 700px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.07);
        position: relative;
    }

    /* BOTÓN DE CERRAR (X) */
    .close {
        position: absolute;
        top: 12px;
        right: 20px;
        font-size: 28px;
        color: #aaa;
        cursor: pointer;
    }

    .close:hover {
        color: white;
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
    </style>
</head>

<body>
    <header class="header">
        <a href="dashboard.php" class="logo">Rev 20!</a>
        <nav class="navbar">
            <a href="#" style="--i:3;">Buscar</a>
            <a href="dashboard.php" style="--i:4;">Dashboard</a>
            <a href="logout.php" style="--i:5;">Cerrar sesión</a>
        </nav>
    </header>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-info">
                <img src="<?php echo $profileImagePath; ?>" alt="Foto de perfil" class="profile-pic">
                <h2><?php echo $nombreUsuario; ?></h2>
                <p>
                    <?php
                    if (!empty($user['bio'])) {
                        echo htmlspecialchars($user['bio']);
                    } else {
                        echo "(Este entusiasta de coches aún no ha tuneado su biografía.)";
                    }
                    ?>
                </p>
                <div class="stats">
                    <div>
                        <h3><?php echo count($revs); ?></h3>
                        <p>Rev’s</p>
                    </div>
                    <div>
                        <h3><?php echo $totalSeguidores; ?></h3>
                        <p>Seguidores</p>
                    </div>
                    <div>
                        <h3><?php echo $totalSiguiendo; ?></h3>
                        <p>Siguiendo</p>
                    </div>
                </div>
            </div>
            <button class="btn-edit">Editar perfil</button>

        </div>

        <div class="revs-section">
            <h2>Tus publicaciones</h2>
            <?php if (empty($revs)) : ?>
            <p class="feed-placeholder">No has publicado ningún rev... 🏋️</p>
            <?php else : ?>
            <?php foreach ($revs as $rev) : ?>
            <div class="rev-card">
                <?php if (!empty($rev['imagen'])) : ?>
                <img src="./../uploads/<?php echo htmlspecialchars($rev['imagen']); ?>" alt="Imagen del rev">
                <?php endif; ?>
                <p><?php echo htmlspecialchars($rev['descripcion']); ?></p>
                <small>Publicado el <?php echo date('d/m/Y H:i', strtotime($rev['fecha'])); ?></small>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- MUEVE EL MODAL AQUÍ FUERA -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close animated" style="animation-delay: 0.1s;">&times;</span>
            <h2 class="animated" style="animation-delay: 0.2s;">Editar perfil</h2>
            <form action="update_profile.php" method="post" enctype="multipart/form-data" class="edit-profile-form">
                <label class="custom-file-upload animated subirFotoCuadro" style="animation-delay: 0.4s;">
                    <input type="file" name="profileImage" />
                    📸 Subir nueva imagen
                </label>

                <label for="bio" class="animated tx" style="animation-delay: 0.5s;">Actualizar biografía:</label>
                <textarea name="bio" rows="4" class="animated textoDescrp"
                    style="animation-delay: 0.6s;"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>

                <div class="button-container animated" style="animation-delay: 0.7s;">
                    <button type="submit" class="btn">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>




    <script>
    const modal = document.getElementById("editModal");
    const btn = document.querySelector(".btn-edit");
    const span = document.querySelector(".close");

    // Abrir modal
    btn.onclick = () => {
        modal.classList.add("show");
    };

    // Cerrar modal
    span.onclick = () => {
        modal.classList.remove("show");
    };

    // Cerrar haciendo clic fuera
    window.onclick = (event) => {
        if (event.target === modal) {
            modal.classList.remove("show");
        }
    };
    </script>


</body>

</html>

<script>
document.querySelector('.btn-edit').addEventListener('click', () => {
    const form = document.getElementById('editProfileForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});
</script>