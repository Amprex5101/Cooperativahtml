<?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit();
    }

    $nombreUsuario = $_SESSION['nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maquetación</title>
    <link rel="stylesheet" href="CSS/Cooperativa.css">
    <link href="https://fonts.cdnfonts.com/css/tw-cen-mt" rel="stylesheet">
    <script src="JS/Navegacion.js?v=<?php echo time(); ?>"></script>
    <script src="JS/selector.js?v=<?php echo time(); ?>"></script>
</head>

<body>
    <div id="menu-container" class="menu-container"></div>
   
    <div class="slider-wrapper">
        <div class="slider-container">
            <div class="slider">
                <img class="slide" src="img/quesadilla.jpg" alt="">
                <img class="slide" src="img/aporreadillo.jpg" alt="">
                <img class="slide" src="img/mole.png" alt="">
            </div>
        </div>
    </div>

    <!-- Adding a margin between the slider and images menu -->
    <div class="margen"></div>

    <div class="imagenes_menu">
        <?php
            try {
                $conexion = new PDO('mysql:host=localhost:3308;dbname=cooperativa_bd', 'root', 'root');
                $productos = array('Chavindeca', 'Hamburguesa', 'Quesadillas D', 'Quesadilla', 'Sandwich', 'Sincronizada', 'Torta', 'Torta Doble', 'Tacos Dorados', 'Morisqueta', 'Papas', 'Tacos');
                $imagenes = array('img/chavindeca.jpeg', 'img/hamburguesa.jpg', 'img/quesadilla dorada.jpg', 'img/quesadilla.jpg', 'img/sandwich.jpg', 'img/sincronizada.jpg', 'img/tortadeshebrada.jpg', 'img/Tortadoble.jpg', 'img/TacosDorados.jpeg', 'img/Morisqueta.jpeg', 'img/papas.jpeg', 'img/Tacos.jpeg');
                $i = 0;
                foreach ($productos as $producto) {
                    $consulta2 = "
                        SELECT opciones, precio
                        FROM menu 
                        WHERE comida = :comida
                        GROUP BY opciones, precio
                    ";
                    $stmt = $conexion->prepare($consulta2);
                    $stmt->execute([':comida' => $producto]);
                    $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($opciones)) {
                        echo '<div class="producto">
                            <img src="'.$imagenes[$i].'" alt="">
                            <div class="texto-menu">
                                <p class="nombre">'.$producto.'</p>
                                <p class="precio">$'.$opciones[0]['precio'].'</p>
                                <div class="cantidad-producto">
                                    <input type="number" class="input-cantidad" value="1" min="1" max="10">
                                    <button class="btn-sumar">+</button>
                                </div>
                                <select class="combo_tamaño">';
                        
                        foreach ($opciones as $opcion) {
                            echo '<option data-precio="'.$opcion['precio'].'">'.$opcion['opciones'].'</option>';
                        }
                        
                        echo '  </select>
                                <button class="btn-añadir">Añadir</button> 
                                </div>
                                <div class="mensaje-carrito">Se ha añadido al carrito</div>
                        </div>';
                    }
                    $i++;
                }
            } catch (PDOException $e) {
                echo 'Hubo un error inesperado: '.$e->getMessage();
            }
        ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.btn-añadir');
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const productoDiv = button.closest('.producto');
                    const nombre = productoDiv.querySelector('.nombre').innerText;
                    const precio = parseFloat(productoDiv.querySelector('.combo_tamaño').selectedOptions[0].dataset.precio);
                    const cantidad = parseInt(productoDiv.querySelector('.input-cantidad').value);
                    const opcion = productoDiv.querySelector('.combo_tamaño').value;
                    const total = precio * cantidad;

                    const nombreUsuario = '<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>';

                    const data = {
                        comida: nombre,
                        opciones: opcion,
                        cantidad: cantidad,
                        total: total,
                        usuario: nombreUsuario
                    };

                    fetch('procesar_pedido.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });

                   
                    const mensaje = productoDiv.querySelector('.mensaje-carrito');
                    mensaje.classList.add('mostrar');
                    setTimeout(() => {
                        mensaje.classList.remove('mostrar');
                    }, 2000);

                    console.log('Nombre:', nombre);
                    console.log('Precio:', precio);
                    console.log('Cantidad:', cantidad);
                    console.log('Opción:', opcion);
                    console.log('Total:', total);
                });
            });
        });
    </script>
</body>
</html>
