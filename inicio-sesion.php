<?php
session_start();

$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$errorescontraseña = '';
$errorescorreo = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conexion = new PDO('mysql:host=localhost:3308;dbname=cooperativa_bd', 'root', 'root');
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $enunciado = $conexion->prepare("SELECT pass FROM login WHERE correo = ?");
        $enunciado->bindParam(1, $email);
        $enunciado->execute();

        if ($row = $enunciado->fetch()) {
            $hash = $row['pass'];

            if (password_verify($password, $hash)) {
                // Inicio de sesión exitoso
                $_SESSION['usuario'] = $email; // Usar email como nombre de usuario
                // Puedes guardar otros datos del usuario en la sesión si es necesario
                header("Location: PaginaMenu.html");
                exit();
            } else {
                $errorescontraseña = '<p class="error">La contraseña es incorrecta</p>';
            }
        } else {
            $errorescorreo = '<p class="error">El correo es incorrecto</p>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="inicio-sesion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        .error {
            color: red;
        }
    </style>
    <script src="rememberme.js" defer></script>
</head>
<body>
    <div class="contenedor-formulario contenedor">
        <div class="fondo">
            <img class="fondoinicio" src="img/recetas-de-comida-típicas-de-Oaxaca-11-1.jpeg" alt="">
        </div>
        <form id="loginForm" action="inicio-sesion.php" method="post" class="formularioo">
            <div>
                <div class="texto-formulario">
                    <h3>DÀN</h3>
                    <p>Please log in or sign up for an account</p>
                </div>
                <label for="email">Correo</label>
                <input type="email" placeholder="name@domain.com" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <?php if (!empty($errorescorreo)) { echo $errorescorreo; } ?>
                <label for="password">Contraseña</label>
                <input type="password" placeholder="contraseña" id="password" name="password">
                <?php if (!empty($errorescontraseña)) { echo $errorescontraseña; } ?>
                <div class="texto-abajo">
                    <div class="recordar">
                        <input type="checkbox" placeholder="recordatorio" id="rememberMe">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#">Forgot password</a>
                </div>
                <div class="botones">
                    <button type="submit" class="Login">Log in</button>
                    <button type="button" class="Signup" onclick="window.location.href='registro.html'">Sign up</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
