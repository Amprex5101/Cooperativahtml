<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Efectivo</title>
    <link rel="stylesheet" href="CSS/QRefectivo.css">
    <script src="JS/Navegacion.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <div id="menu-container" class="menu-container"></div>
    <div class="Pago">
        <h1>Tu QR</h1>
        <div class="imagenes">

            <img src="img/blanco.jpg" alt="">

            <div class="datos">
                    <p>Fecha:</p>
                    <p>Hora:</p>
                    <p>Caduca en:</p>
                    <p>Pagar en:</p>
            </div>
        </div>

        <div class="qr">
            <p>Este código puede ser usado solo una vez y antes de la fecha de caducidad</p>
        </div>

        <div class="boton">
            <a href="MisPedidos.php">Volver a Mis Pedidos</a>
        </div>
    </div>
</body>
</html>