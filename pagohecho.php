<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$correo = $_SESSION['correo'] ?? '';
$usuario = $_SESSION['nombre'] ?? '';

// Establecer la zona horaria correcta
date_default_timezone_set('America/Mexico_City');

header('Content-Type: application/json');

$tipopago = $_POST['tipopago'] ?? '';

if ($tipopago) {
    try {
        $conexion = new PDO('mysql:host=localhost:3307;dbname=cooperativa_bd', 'root', '');
        $fecha = date('Y-m-d', strtotime('-1 day'));
        // Contar el número de pedidos
        $consulta2 = "SELECT COUNT(DISTINCT numerodepedido) AS num_pedidos FROM pedidos";
        $stmtconsulta2 = $conexion->prepare($consulta2);
        $stmtconsulta2->execute();
        $result = $stmtconsulta2->fetch(PDO::FETCH_ASSOC);
        $numerodepedido = $result['num_pedidos'] + 1;

        $consulta = "UPDATE pedidos p, login l 
                     SET p.pagado = 1, p.pagocon = :tipopago, p.numerodepedido = :numerodepedido, p.fecha = :fecha
                     WHERE p.pagado = 0 AND p.cancelado = 0 AND l.correo = :correo
                     AND p.usuario = :usuario";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':tipopago', $tipopago);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':numerodepedido', $numerodepedido);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Pedido pagado correctamente']);
        } else {
            echo json_encode(['message' => 'Error al pagar el pedido']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Hubo un error inesperado: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Datos no válidos']);
}
?>
