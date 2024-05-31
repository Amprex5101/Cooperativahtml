<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTRUCTURA HTML</title>
    <link rel="stylesheet" href="CSS/Metodo-Pagos.css">
    <script src="JS/Navegacion.js"></script>
</head>
<body>
    <div id="menu-container" class="menu-container"></div>
    <div class="Compras">
        <h1>¿Cómo deseas pagar....?</h1>
        <form method="post" action="">
            <div class="metodos_pagos">
                <input type="radio" id="debito" name="pago" value="debito">
                <div class="circulo">
                    <img src="img/metodo-de-pago.png" alt="metodopago">
                </div>
                <label for="debito">Tarjeta de Débito</label>
            </div>
            <div class="metodos_pagos">
                <input type="radio" id="efectivo" name="pago" value="efectivo" checked>
                <div class="circulo">
                    <img src="img/dinero-en-efectivo.png" alt="metodopago">
                </div>
                <label for="efectivo">Efectivo</label>
            </div>
            <div class="boton">
                <button type="submit">Pagar</button>
            </div>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $metodo_pago = $_POST['pago'];
        
        if ($metodo_pago == 'debito') {
            echo "<script>window.location.href = 'pagotarjeta.html';</script>";
        } elseif ($metodo_pago == 'efectivo') {
            echo "<script>window.location.href = 'QRefectivo';</script>";
        }
    }
    ?>
</body>
</html>
