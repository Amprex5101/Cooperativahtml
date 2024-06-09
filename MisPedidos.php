<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión si no está autenticado
    header("Location: login.php");
    exit();
}

$nombreUsuario = $_SESSION['nombre'] ?? '';
$correo = $_SESSION['correo'] ?? '';

try {
    $conexion = new PDO('mysql:host=localhost:3307;dbname=cooperativa_bd', 'root', '');
    $consulta = "SELECT p.usuario, p.comida, p.opciones, SUM(p.cantidad) cantidad, SUM(p.total) total 
                 FROM pedidos p, login l 
                 WHERE p.pagado=1 AND p.cancelado=0 AND l.correo=:correo AND p.usuario=:usuario 
                 GROUP BY p.comida, p.opciones;";
    $consulta2 = "SELECT SUM(p.total) total 
                  FROM pedidos p, login l 
                  WHERE p.pagado=0 AND p.cancelado=0 AND l.correo=:correo AND p.usuario=:usuario;";

    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':usuario', $nombreUsuario);
    $stmt->execute();
    $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conexion->prepare($consulta2);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':usuario', $nombreUsuario);
    $stmt->execute();
    $total = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($opciones)) {
        header("Location: Web-sinpedidos.php");
        exit();
    }
} catch (PDOException $e) {
    echo 'Hubo un error inesperado: '.$e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTRUCTURA HTML</title>
    <link rel="stylesheet" href="CSS/MisPedidos.css">
    <script src="JS/Navegacion.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <div id="menu-container" class="menu-container"></div>
    <header>
        <h2>Mis pedidos</h2>
        
        <div class="mispedidos">
            <div class="Filtrar">
                <img src="img/filtrar.png" alt="">
                <p>Todos</p>
            </div>
            <p>Cantidad de pedidos</p>
        </div>
    </header>
    <section class="Pedidos">
        <?php
            $imagenes = array(
                'Chavindeca' => 'img/chavindeca.jpg', 'Hamburguesa' => 'img/hamburguesa.jpg',
                'Quesadillas D' => 'img/quesadilla dorada.jpg', 'Quesadilla' => 'img/quesadilla.jpg',
                'Sandwich' => 'img/sandwich.jpg', 'Sincronizada' => 'img/sincronizada.jpg',
                'Torta' => 'img/tortadeshebrada.jpg', 'Torta Doble' => 'img/TortaDoble.jpeg',
                'Tacos Dorados' => 'img/TacosDorados.jpeg', 'Morisqueta' => 'img/Morisqueta.jpeg',
                'Papas' => 'img/papas.jpeg', 'Tacos' => 'img/Tacos.jpeg'
            );
            foreach ($opciones as $fila) {
                $imagen = isset($imagenes[$fila['comida']]) ? $imagenes[$fila['comida']] : 'img/blanco.jpg';

                echo '<div class="imagenes">
                        <div class="detalles">
                            <img src="'.$imagen.'" alt="">
                            <div class="datos">
                                <h3>'.$fila['comida'].'</h3>
                                <div class="cantidad">
                                    <p>Cantidad:</p>
                                    <p>'.$fila['cantidad'].'</p>
                                    <p>Tamaño:</p>
                                    <p>'.$fila['opciones'].'</p>
                                </div>
                            </div>
                        </div>
                        <p class="precio">$'.$fila['total'].'</p>
                    </div>';
            }

            foreach ($total as $tot) {
                echo '<div class="total">
                        <h2>Total: </h2>
                        <h2>'.$tot['total'].'</h2>
                    </div>';
            }
        ?>
        <div class="Fecha">
            <h1>Fecha</h1>
            <a href="QRefectivo.php">Mostrar QR</a>
        </div>

        
        <div class="imagenes">
            <img src="img/blanco.jpg" alt="">
            <div class="datos">
                <p>Nombre del producto</p>
                <div class="cantidad">
                    <p>Cantidad:</p>
                    <p>1</p>
                    <p>Tamaño:</p>
                    <p>Grande</p>
                </div>
            </div>
        </div>


        <div class="imagenes">
            <img src="img/blanco.jpg" alt="">
            <div class="datos">
                <p>Nombre del producto</p>
                <div class="cantidad">
                    <p>Cantidad:</p>
                    <p>1</p>
                    <p>Tamaño:</p>
                    <p>Grande</p>
                </div>
            </div>
        </div>
    </section>

    <div class="Pedidos">
        <div class="Fecha">
            <h1>Fecha</h1>
            <a href="QRtarjeta.php">Mostrar QR</a>
        </div>
        <div class="imagenes">
            <img src="img/blanco.jpg" alt="">
            <div class="datos">
                <p>Nombre del producto</p>
                <div class="cantidad">
                    <p>Cantidad:</p>
                    <p>1</p>
                    <p>Tamaño:</p>
                    <p>Grande</p>
                </div>
            </div>
        </div>
        <div class="imagenes">
            <img src="img/blanco.jpg" alt="">
            <div class="datos">
                <p>Nombre del producto</p>
                <div class="cantidad">
                    <p>Cantidad:</p>
                    <p>1</p>
                    <p>Tamaño:</p>
                    <p>Grande</p>
                </div>
            </div>
        </div>
        
    </div>

</body>
</html>