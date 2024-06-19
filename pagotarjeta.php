<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Tarjeta</title>
    <link rel="stylesheet" href="CSS/pagotarjeta.css">
    <script src="JS/Navegacion.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <div id="menu-container" class="menu-container"></div>
    <div class="pagos">
        <h1>Ingrese los datos de la tarjeta con la que deseas pagar tu orden</h1>
        <div class="imagenes">
            <div class="datos">
                <div class="numero">
                    <p>Numero de tarjeta</p>
                    <input type="text" placeholder="Numero de tarjeta" required>
                </div>
                <div>
                    <p>Nombre y Apellido</p>
                    <input type="text" placeholder="Nombre y Apellido" required>
                </div>
                <div class="codi">
                    <div class="fecha">
                        <p>Fecha de vencimiento</p> 
                        <input type="text" id="fecha_vencimiento" name="fecha_vencimiento" placeholder="MM/AAAA" pattern="(0[1-9]|1[0-2])\/(20[2-9][0-9]|203[0-9])" title="Ingrese una fecha válida en el formato MM/AAAA" required>
                        <script>
                        document.getElementById('fecha_vencimiento').addEventListener('input', function(e) {
                            var input = e.target;
                            if (input.value.length === 2 && !input.value.includes('/')) {
                                input.value += '/';
                            }
                        });
                        </script>
                        <div class="let">
                            <p>mes/año</p>
                        </div>
                    </div>            
                    <div class="codigo">
                        <p>Codigo de seguridad:</p>
                        <input type="text" placeholder="CVV" required>
                        <div class="letra">
                            <p>CVV</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tarjeta">
                <img src="img/tarjeta-bancaria.png" alt="" class="tarj">
            </div>
        </div>
        <div class="boton">
            <button id="pay-button" onclick="window.location.href='QRtarjeta.php'">Pagar</button>
        </div>
    </div>

    <script>
        document.getElementById('pay-button').addEventListener('click', function (event) {
            event.preventDefault();

            const formData = new FormData();
            formData.append('tipopago', 'debito');

            fetch('pagohecho.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Pedido pagado correctamente') {
                    window.location.href = 'QRtarjeta.php';
                } else {
                    alert('Error al procesar el pago: ' + data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
