<?php
    session_start();
    require_once('./../conexiónBD.php');

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ./../../login/index.php');
        exit;
    }

    $iduser = $_SESSION['usuario_id'];
    $stmt = $db->prepare("SELECT * FROM users WHERE iduser = ?");
    $stmt->execute([$iduser]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $carImages = [
        "./../imagenes/lamborghini.png" => ["background" => "#1d1d1d"],
        "./../imagenes/audi.png" => ["background" => "#ffffff", "textColor" => "#292929", "romboColor" => "#dfdfdf", "btn" => "#000000"],
        "./../imagenes/revuelto.png" => ["background" => "#ff5e00", "romboColor" => "#ef3e00", "btn" => "#ffffff", "rev1" => "#000000", "btn-border" => "#ffffff"],
        "./../imagenes/porsche.png" => ["background" => "#b5b5b5", "romboColor" => "#979696", "btn" => "#ffffff", "btn-border" => "#ffffff"],
        "./../imagenes/ferrariR.png" => ["background" => "#ff3737", "romboColor" => "#f10202", "btn" => "ffffff", "btn-border" => "#ffffff", "rev1" => "#000000"],
        "./../imagenes/urus.png" => ["background" => "#ffbb3e", "romboColor" => "#e79e29", "btn" => "ffffff", "btn-border" => "#ffffff", "rev1" => "#000000"],
        "./../imagenes/verde.png" => ["background" => "#a6ff2d", "romboColor" => "#65ba07", "btn" => "ffffff", "btn-border" => "#ffffff", "rev1" => "#000000"],
    ];
    // Elegir un coche aleatorio
    $randomCar = array_rand($carImages);  // Obtienes la clave de la imagen (la ruta)

    // Accedes a los estilos de ese coche
    $carStyle = $carImages[$randomCar];  // Obtienes los estilos de ese coche
    #"./../imagenes/bugatti.png"
?>

<!-- dashboard.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi perfil - Rev20!</title>
    <link rel="stylesheet" href="./dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Aquí metemos el fondo dinámico desde PHP -->
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
    </style>


</head>

<body>
    <header class="header">
        <a href="dashboard.php" class="logo">Rev 20!</a>
        <nav class="navbar">
            <a href="#" style="--i:3;">Buscar</a>
            <a href="perfilUser.php" style="--i:4;">Mi perfil</a>
            <a href="logout.php" style="--i:5;">Cerrar sesión</a>
        </nav>
    </header>
    <section class="home">
        <div class="home-content">
            <h1>¡Bienvenido <?php
                if (isset($_SESSION['usuario'])) {
                    echo htmlspecialchars($_SESSION['usuario']); // Protegemos contra XSS, por si acaso
                } else {
                    echo "invitado"; // Por si llega un colado sin iniciar sesión
                }
            ?>!</h1>
            <h3>Sube tu rev!</h3>
            <p>Los aventurados ganan insignias como trofeos de batalla,
                y sus perfiles se convierten en altares de velocidad y gloria.

                Presume. Corre. Vive.

                Haz que el motor grite lo que el corazón no puede.

                Hit diff!.
            </p>
            <a href="#" class="btn">Subir rev!</a>
        </div>

        <div class="home-img">
            <div class="rhombus">
                <img src="<?php echo $randomCar; ?>">
            </div>
        </div>

        <div class="rhombus2"></div>
    </section>
</body>

</html>