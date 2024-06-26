<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="CSS/inicio-sesion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="contenedor-formulario contenedor">
        <div class="fondo">
            <img class="fondoinicio" src="img/recetas.jpeg" alt="">
        </div>
        <form action="login.php" method="post" class="formularioo">
            <div>
                <div class="texto-formulario">
                    <h3>DÀN</h3>
                    <p>Please log in or sign up for an account</p>
                </div>
                <?php if (!empty($errores)): ?>
                    <p style="color: red;"><?php echo $errores; ?></p>
                <?php endif; ?>
                <label for="email">Correo</label>
                <input type="email" placeholder="name@domain.com" id="email" name="email" required>
                <label for="password">Contraseña</label>
                <input type="password" placeholder="contraseña" id="password" name="password" required>
                <div class="texto-abajo">
                    <div class="recordar">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#">Forgot password</a>
                </div>
                <div class="botones">
                    <button type="submit" class="Login">Log in</button>
                    <button type="button" class="Signup" onclick="window.location.href='RegistroPrincipal.php'">Sign up</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
