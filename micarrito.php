<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = $_SESSION['nombre'] ?? '';
$correo = $_SESSION['correo'] ?? '';

try {
    $conexion = new PDO('mysql:host=localhost:3307;dbname=cooperativa_bd', 'root', '');
    $consulta = "SELECT p.usuario, p.comida, p.opciones, SUM(p.cantidad) cantidad, SUM(p.total) total 
                 FROM pedidos p, login l 
                 WHERE p.pagado=0 AND p.cancelado=0 AND l.correo=:correo AND p.usuario=:usuario 
                 GROUP BY p.comida, p.opciones ORDER BY comida ASC;";
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
    <link rel="stylesheet" href="CSS/micarrito.css">
    <script src="JS/Navegacion.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <div id="menu-container" class="menu-container"></div>
    <div class="Compras">
        <h1>Mi carrito</h1>

        <?php
        $imagenes = array(
            'Chavindeca' => 'img/chavindeca.jpeg', 'Hamburguesa' => 'img/hamburguesa.jpg',
            'Quesadillas D' => 'img/quesadilla dorada.jpg', 'Quesadilla' => 'img/quesadilla.jpg',
            'Sandwich' => 'img/sandwich.jpg', 'Sincronizada' => 'img/sincronizada.jpg',
            'Torta' => 'img/tortadeshebrada.jpg', 'Torta Doble' => 'img/TortaDoble.jpg',
            'Tacos Dorados' => 'img/TacosDorados.jpeg', 'Morisqueta' => 'img/Morisqueta.jpeg',
            'Papas' => 'img/papas.jpeg', 'Tacos' => 'img/Tacos.jpeg'
        );

        foreach ($opciones as $fila) {
            $imagen = isset($imagenes[$fila['comida']]) ? $imagenes[$fila['comida']] : 'img/blanco.jpg';

            echo '<div class="imagenes">
                    <div class="detalles">
                        <img src="'.$imagen.'" alt="" class="responsive-image">
                        <div class="datos">
                            <h3>'.$fila['comida'].'</h3>
                            <div class="cantidad">
                                <p>Cantidad:</p>
                                <p>'.$fila['cantidad'].'</p>
                                <p>Tamaño:</p>
                                <p>'.$fila['opciones'].'</p>
                            </div>
                            <div class="eliminarCelu">
                            <button class="btn-eliminar">Eliminar</button> 
                            <p class="precio">$'.$fila['total'].'</p>
                            </div>
                        </div>
                    </div>
                    <div class="eliminar">
                    <button class="btn-eliminar">Eliminar</button> 
                    <p class="precio">$'.$fila['total'].'</p>
                    </div>
                  </div>';
        }

        foreach ($total as $tot) {
            echo '<div class="total">
                    <h2>Total: </h2>
                    <h2 id="total-amount">'.$tot['total'].'</h2>
                  </div>';
        }
        ?>
        
        <div class="boton">
            <a href="Metodo-Pago.php">Pagar</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.btn-eliminar');
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const productoDiv = button.closest('.imagenes');
                    const nombre = productoDiv.querySelector('h3').innerText;
                    const cantidad = productoDiv.querySelectorAll('.cantidad p')[1].innerText;
                    const opcion = productoDiv.querySelectorAll('.cantidad p')[3].innerText;
                    
                    const nombreUsuario = '<?php echo $nombreUsuario; ?>';
                    const correo = '<?php echo $correo; ?>';

                    const data = {
                        usuario: nombreUsuario,
                        correo: correo,
                        comida: nombre,
                        cantidad: cantidad,
                        opciones: opcion
                    };

                    fetch('eliminarpedido.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        if (data.message === 'Pedido eliminado correctamente') {
                            // Remover el producto del DOM
                            productoDiv.remove();

                            // Actualizar el total
                            const totalElement = document.getElementById('total-amount');
                            totalElement.innerText = data.total;

                            // Redirigir a Web-sinpedidos.php si el total es 0
                            if (data.total == null) {
                                window.location.href = 'Web-sinpedidos.php';
                            }
                        } else {
                            alert('Error al eliminar el pedido: ' + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
</body>
</html>
