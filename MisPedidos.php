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
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los pedidos agrupados por fecha y número de pedido
    $consulta = "
        SELECT p.pagocon, p.fecha, p.numerodepedido 
        FROM pedidos p
        JOIN login l ON p.usuario = l.usuario
        WHERE p.pagado = 1 
          AND p.cancelado = 0 
          AND l.correo = :correo 
          AND p.usuario = :usuario 
          AND p.fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND CURDATE()
        GROUP BY p.numerodepedido, p.fecha
        ORDER BY p.fecha DESC;
    ";

    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':usuario', $nombreUsuario);
    $stmt->execute();
    $pedidosAgrupados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Redirigir si no hay pedidos agrupados
    if (empty($pedidosAgrupados)) {
        header("Location: Web-sinpedidos.php");
        exit();
    }

    // Consulta para obtener los detalles de cada pedido
    $consultaDetalles = "
        SELECT p.numerodepedido, p.comida, p.opciones, SUM(p.cantidad) cantidad, SUM(p.total) total, p.pagocon, p.fecha
        FROM pedidos p
        JOIN login l ON p.usuario = l.usuario
        WHERE p.pagado = 1 
          AND p.cancelado = 0 
          AND l.correo = :correo 
          AND p.usuario = :usuario 
          AND p.fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND CURDATE()
        GROUP BY p.numerodepedido, p.comida, p.opciones, p.pagocon, p.fecha;
    ";

    $stmtDetalles = $conexion->prepare($consultaDetalles);
    $stmtDetalles->bindParam(':correo', $correo);
    $stmtDetalles->bindParam(':usuario', $nombreUsuario);
    $stmtDetalles->execute();
    $detallesPedidos = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

    // Separar los pedidos por método de pago
    $pedidosEfectivo = array_filter($pedidosAgrupados, function($pedido) {
        return $pedido['pagocon'] == 'efectivo';
    });

    $pedidosTarjeta = array_filter($pedidosAgrupados, function($pedido) {
        return $pedido['pagocon'] == 'tarjeta';
    });

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
    <?php
        $imagenes = array(
            'Chavindeca' => 'img/chavindeca.jpg', 'Hamburguesa' => 'img/hamburguesa.jpg',
            'Quesadillas D' => 'img/quesadilla dorada.jpg', 'Quesadilla' => 'img/quesadilla.jpg',
            'Sandwich' => 'img/sandwich.jpg', 'Sincronizada' => 'img/sincronizada.jpg',
            'Torta' => 'img/tortadeshebrada.jpg', 'Torta Doble' => 'img/TortaDoble.jpeg',
            'Tacos Dorados' => 'img/TacosDorados.jpeg', 'Morisqueta' => 'img/Morisqueta.jpeg',
            'Papas' => 'img/papas.jpeg', 'Tacos' => 'img/Tacos.jpeg'
        );

        function renderPedidos($pedidos, $detalles, $imagenes, $tipoPago) {
            foreach ($pedidos as $pedido) {
                $newDate = date("d/m/Y", strtotime($pedido['fecha']));
                echo '<section class="Pedidos">
                        <div class="Fecha">
                            <h1>'.$newDate.'</h1>
                            <div class="Metodo-Pago">
                                <p>Pago con '.$tipoPago.'</p>
                                <a href="'.($tipoPago == 'efectivo' ? 'QRefectivo.php' : 'QRtarjeta.php').'">Mostrar QR</a>
                            </div>
                        </div>';

                foreach ($detalles as $detalle) {
                    if ($detalle['pagocon'] == $tipoPago && $detalle['fecha'] == $pedido['fecha'] && $detalle['numerodepedido'] == $pedido['numerodepedido']) {
                        $imagen = isset($imagenes[$detalle['comida']]) ? $imagenes[$detalle['comida']] : 'img/blanco.jpg';
                        echo '<div class="imagenes">
                                <img src="'.$imagen.'" alt="">
                                <div class="datos">
                                    <p>'.$detalle['comida'].'</p>
                                    <div class="cantidad">
                                        <p>Cantidad: '.$detalle['cantidad'].'</p>
                                        <p>Tamaño: '.$detalle['opciones'].'</p>
                                    </div>
                                </div>
                              </div>';
                    }
                }
                echo '</section>';
            }
        }

        // Renderizar pedidos pagados con efectivo
        renderPedidos($pedidosEfectivo, $detallesPedidos, $imagenes, 'efectivo');

        // Renderizar pedidos pagados con tarjeta
        renderPedidos($pedidosTarjeta, $detallesPedidos, $imagenes, 'tarjeta');
    ?>
</body>
</html>
