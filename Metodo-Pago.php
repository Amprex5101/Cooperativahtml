<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTRUCTURA HTML</title>
    <link rel="stylesheet" href="CSS/Metodo-Pagos.css">
    <script src="JS/Navegacion.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <div id="menu-container" class="menu-container"></div>
    <div class="Compras">
        <h1>¿Cómo deseas pagar....?</h1>
        <form id="payment-form">
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

    <script>
        document.getElementById('payment-form').addEventListener('submit', function (event) {
            event.preventDefault();
            const metodoPago = document.querySelector('input[name="pago"]:checked').value;

            const formData = new FormData();
            formData.append('tipopago', metodoPago);

            if (metodoPago === 'efectivo') {
                fetch('pagohecho.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Pedido pagado correctamente') {
                        window.location.href = 'QRefectivo.php';
                    } else {
                        alert('Error al procesar el pago: ' + data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            } else if (metodoPago === 'debito') {
                window.location.href = 'pagotarjeta.php';
            }
        });
    </script>
</body>
</html>
