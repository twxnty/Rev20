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
            "./../imagenes/lamborghini.png" => ["background" => "#1d1d1d", "busqueda" => "#ffffff", "txtRev" => "#000000"],
            "./../imagenes/audi.png" => ["background" => "#ffffff", "textColor" => "#292929", "romboColor" => "#dfdfdf", "btn" => "#000000", "busqueda" => "#000000", "txtRev" => "#ffffff"],
            "./../imagenes/revuelto.png" => ["background" => "#ff5e00", "romboColor" => "#ef3e00", "btn" => "#ffffff", "rev1" => "#000000", "btn-border" => "#ffffff", "busqueda" => "#ffffff", "txtRev" => "#000000"],
            "./../imagenes/porsche.png" => ["background" => "#b5b5b5", "romboColor" => "#979696", "btn" => "#ffffff", "btn-border" => "#ffffff", "busqueda" => "#ffffff", "txtRev" => "#000000"],
            "./../imagenes/ferrariR.png" => ["background" => "#ff3737", "romboColor" => "#f10202", "btn" => "ffffff", "btn-border" => "#ffffff", "rev1" => "#000000", "busqueda" => "#ffffff", "txtRev" => "#000000"],
            "./../imagenes/urus.png" => ["background" => "#ffbb3e", "romboColor" => "#e79e29", "btn" => "ffffff", "btn-border" => "#ffffff", "rev1" => "#000000", "busqueda" => "#ffffff", "txtRev" => "#000000"],
            "./../imagenes/verde.png" => ["background" => "#a6ff2d", "romboColor" => "#65ba07", "btn" => "ffffff", "btn-border" => "#ffffff", "rev1" => "#000000", "busqueda" => "#ffffff", "txtRev" => "#000000"],
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
        <title>Rev 20!</title>
        <link rel="stylesheet" href="./dashboard.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="icon" type="image/x-icon" href="./../imagenes/favicon.png">
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
            border: 2px solid <?php echo $carStyle['busqueda'];
            ?>;
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

        .textoDescrp {
            margin-bottom: 5px;
        }

        .tit {
            margin-bottom: 13px;
        }

        .tx {
            margin-bottom: 7px;
        }

        .edit-profile-form {
            display: flex;
            flex-direction: column;
        }

        .edit-profile-form textarea {
            width: 100%;
            resize: vertical;
            height: 200px;
        }

        .button-container {
            text-align: center;
        }

        .subirFotoCuadro {
            margin-bottom: 25px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
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

        .white {
            color: white;
        }

        .black {
            color: black;
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

        .holaa {
            width: 100%;
            background: #2b2b2b;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
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

        .custom-file-upload input[type="file"] {
            display: none;
        }

        .subirFotoCuadro {
            margin-bottom: 25px;
        }

        .navbar .nav-links {
            display: flex;
            gap: 15px;
        }

        .txtt {
            margin-bottom: 90px;
        }

        .so {
            color: <?php echo $carStyle['txtRev'];
            ?>;
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

                <a href="#" class="btn" id="abrirModal">Subir rev!</a>

                <div id="modalSubirRev" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 class="white tit">Sube tu Rev</h2>
                        <form action="procesarRev.php" method="POST" enctype="multipart/form-data"
                            class="edit-profile-form">
                            <label class="white" for="descripcion">Descripción:</label><br>
                            <textarea class="black holaa textoDescrp" name="descripcion" required></textarea><br>
                            <label class="custom-file-upload animated subirFotoCuadro" style="animation-delay: 0.4s;">
                                <input type="file" name="imagen">
                                📸 Subir nueva imagen
                            </label>
                            <button type="submit" class="btn btn-rev so">Subir Rev</button>
                        </form>
                    </div>
                </div>

            </div>

            <div class="home-img">
                <div class="rhombus">
                    <img src="<?php echo $randomCar; ?>">
                </div>
            </div>

            <div class="rhombus2"></div>
        </section>
        <script>
        const modal = document.getElementById("modalSubirRev");
        const abrir = document.getElementById("abrirModal");
        const cerrar = modal.querySelector(".close");

        abrir.onclick = () => modal.classList.add("show");
        cerrar.onclick = () => modal.classList.remove("show");
        window.onclick = (e) => {
            if (e.target === modal) modal.classList.remove("show");
        }
        </script>


    </body>

    </html>