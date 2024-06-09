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
                    <input type="text" placeholder="Numero de tarjeta">
                </div>

                <div>
                    <p>Nombre y Apellido</p>
                    <input type="text" placeholder="Nombre y Apellido">
                </div>

                <div class="codi">
                    <div class="fecha">
                        <p>Fecha de vencimiento</p> 
                        <input type="text" id="fecha_vencimiento" name="fecha_vencimiento" placeholder="MM/AAAA" pattern="(0[1-9]|1[0-2])\/(20[2-9][0-9]|203[0-9])" title="Ingrese una fecha válida en el formato MM/AAAA" required>

                        <!-- Otros campos del formulario, como el nombre del titular, código de seguridad, etc., pueden agregarse aquí -->
                        
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
                        <input type="text" placeholder="CVV">
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
            <a href="QRtarjeta.php">Pagar</a>
        </div>
    </div>
</body>
</html>