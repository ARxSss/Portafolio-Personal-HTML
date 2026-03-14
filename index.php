<?php
date_default_timezone_set('America/Caracas');

$conexion = new mysqli("localhost", "root", "", "portafoliodelta_db");
if ($conexion->connect_error) {
    die("Error de conexión a la Base de Datos: " . $conexion->connect_error);
}

$id_nota = 0;
$nombre = "";
$usuario = "";
$email = "";
$nota_texto = "";
$modo_edicion = false;

if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    $conexion->query("DELETE FROM notas WHERE id=$id_eliminar");
    header("Location: index.php#seccion_notas");
}

if (isset($_POST['guardar_nota'])) {
    $nombre = $conexion->real_escape_string($_POST['nombreyapellido']);
    $usuario = $conexion->real_escape_string($_POST['usuario']);
    $email = $conexion->real_escape_string($_POST['email']);
    $nota_texto = $conexion->real_escape_string($_POST['nota']);
    
    $fechanota = date("Y-m-d H:i:s"); 

    if (isset($_POST['id_nota']) && $_POST['id_nota'] > 0) {
        $id_actualizar = $_POST['id_nota'];
        $sql = "UPDATE notas SET nombreyapellido='$nombre', usuario='$usuario', email='$email', nota='$nota_texto' WHERE id=$id_actualizar";
        $conexion->query($sql);
    } else {
        $sql = "INSERT INTO notas (nombreyapellido, usuario, email, nota, fechanota) VALUES ('$nombre', '$usuario', '$email', '$nota_texto', '$fechanota')";
        $conexion->query($sql);
    }
    header("Location: index.php#seccion_notas");
}

if (isset($_GET['editar'])) {
    $id_nota = $_GET['editar'];
    $modo_edicion = true;
    $resultado = $conexion->query("SELECT * FROM notas WHERE id=$id_nota");
    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_array();
        $nombre = $fila['nombreyapellido'];
        $usuario = $fila['usuario'];
        $email = $fila['email'];
        $nota_texto = $fila['nota'];
    }
}

$todas_las_notas = $conexion->query("SELECT * FROM notas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/3159/3159073.png">
    <title>📺 DESDE LA GREEN ROOM | Portafolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="tv_header">
        <h1>BACKSTAGE: FABRIZIO MARCHIORO</h1>
        <nav>
            <ul>
                <li><a href="#about_me">► EL PROPIO</a></li>
                <li><a href="#hobbies">► LO QUE HACE LA CHAVIZA</a></li>
                <li><a href="#projects">► JUEGOS FAV</a></li>
                <li><a href="#seccion_notas">►  TELEVIDENTES</a></li> 
            </ul>
        </nav>
    </header>

    <main>
        <section id="about_me" class="tv_section">
            <h2>[01] EN EL CAMERINO (Sobre Mí)</h2>
            <div class="content_box profile_layout">
                <div class="profile_img_container">
                    <img src="assets/desspfp.png" alt="Foto de perfil de Fabrizio Marchioro" class="profile_img">
                </div>
                <div class="profile_text">
                    <p>Hola!11111!!1 Mi Nombre es Fabrizio Marchioro, soy estudiante de Ing. en Sistemas de la Universidad de Margarita y ya voy por mi 7mo trimestre de la carrera</p>
                    <p>Me gusta hablar con las personas y ser gentil con los demas</p>
                </div>
            </div>
        </section>

        <section id="hobbies" class="tv_section">
            <h2>[02] RUTINA DE ENSAYO (Intereses)</h2>
            <div class="content_box grid_layout">
                <div class="info_card">
                    <h3>🌍 Juegos</h3>
                    <p>Soy muy fan de los juegos de exploracion y mundo abierto, asi como juegos con gran historia</p>
                </div>
                <div class="info_card">
                    <h3>🎨 Hobbys</h3>
                    <p>Estoy en proceso de aprender a Dibujar por hobby, a la par, me gusta hacer ejercicio y deportes</p>
                </div>
                <div class="info_card">
                    <h3>💻 Bases de Datos</h3>
                    <p>Me encantaria especializarme en Bases de Datos en un futuro, me encantan y se me hacen muy entretenidas</p>
                </div>
            </div>
        </section>

        <section id="projects" class="tv_section">
            <h2>[03] ACTUACIONES PRINCIPALES (Gustos Mayores)</h2>
            <div class="content_box grid_layout">
                <div class="info_card project_card">
                    <h3>🗣️🔥 Series Fav</h3>
                    <p>Mis series favoritas son Neon Genesis Evangelion y Cyberpunk: Edgerunners. Son series que llevo con mucho carino</p>
                </div>
                <div class="info_card project_card">
                    <h3>🐒 Juegos Principales</h3>
                    <p>Amo y amare hasta la muerte a Destiny 2, quiero terminar Deltarune (valga la redundancia) y seguir divirtiendome en Fortnite. Deseo jugar Cyberpunk gracias a la serie</p>
                </div>
            </div>
        </section>

        <section id="seccion_notas" class="tv_section">
            <h2>[04] Cartas de los Televidentes (Notas)</h2>
            <div class="content_box">
                
                <form action="index.php" method="POST" class="tv_form">
                    <input type="hidden" name="id_nota" value="<?php echo $id_nota; ?>">
                    
                    <input type="text" name="nombreyapellido" placeholder="Nombre y Apellido" class="tv_input" required value="<?php echo $nombre; ?>">
                    
                    <input type="text" name="usuario" placeholder="Usuario (Opcional)" class="tv_input" value="<?php echo $usuario; ?>">
                    
                    <input type="email" name="email" placeholder="Correo Electrónico" class="tv_input" required value="<?php echo $email; ?>">
                    
                    <textarea name="nota" placeholder="Escribe tu nota aquí..." class="tv_input" rows="4" required><?php echo $nota_texto; ?></textarea>
                    
                    <?php if($modo_edicion): ?>
                        <button type="submit" name="guardar_nota" class="tv_btn" style="background-color: #4fc3f7;">Actualizar Nota</button>
                    <?php else: ?>
                        <button type="submit" name="guardar_nota" class="tv_btn">Enviar Nota</button>
                    <?php endif; ?>
                </form>

                <hr style="border: 1px solid var(--tv_neon_pink); margin: 30px 0;">

                <h3>► CARTAS RECIBIDAS:</h3>
                <br>
                
                <?php while($fila = $todas_las_notas->fetch_assoc()): ?>
                    <div class="nota_card">
                        <div class="nota_header">
                            <strong><?php echo htmlspecialchars($fila['nombreyapellido']); ?> 
                                <?php if($fila['usuario']) echo "(@".htmlspecialchars($fila['usuario']).")"; ?>
                            </strong>
                            <span class="nota_fecha">Enviado: <?php echo $fila['fechanota']; ?></span>
                        </div>
                        <div class="nota_cuerpo">
                            <p><?php echo nl2br(htmlspecialchars($fila['nota'])); ?></p>
                        </div>
                        <div class="nota_acciones">
                            <a href="index.php?editar=<?php echo $fila['id']; ?>#seccion_notas" class="btn_editar">[✏️ Editar]</a>
                            <a href="index.php?eliminar=<?php echo $fila['id']; ?>" class="btn_eliminar" onclick="return confirm('¿Borrar esta nota permanentemente?');">[🗑️ Eliminar]</a>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>
        </section>

    </main>

    <footer class="tv_footer">
        <p>Lo quiero mucho Profe</p>
    </footer>
</body>
</html>