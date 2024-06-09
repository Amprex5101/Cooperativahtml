<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/Registro.css">
</head>
<body>
    <div class="contenedor-formulario contenedor">
        <div class="fondo">
            <img class="fondoinicio" src="img/recetas.jpeg" alt="">
        </div>
        <form action="registro.php" method="POST" class="formularioo">
            <div>
                <div class="texto-formulario">
                    <h3>DÀN</h3>
                   
                </div>
                <label for="username">Nombre de Usuario</label>
                <input type="text" placeholder="nombre de usuario" id="username" name="name">
                <label for="email">Correo</label>
                <input type="email" placeholder="name@domain.com" id="email" name="email">
                <label for="password">Contraseña</label>
                <input type="password" placeholder="contraseña" id="password" name="password">
                <div class="texto-abajo">
                </div>
                <div class="botones">
                    <button class="Signup">Sign up</button>
                </div>
            </div>
        </form>

    </div>
</body>
</html>