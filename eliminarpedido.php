<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$correo = $_SESSION['correo'] ?? '';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $usuario = $data['usuario'];
    $comida = $data['comida'];
    $opciones = $data['opciones'];
    $cantidad = $data['cantidad'];
    
    include 'conexion.php';
    try {
        $conexion = obtenerConexion();
        $consulta = "UPDATE pedidos p, login l 
                     SET p.cancelado=1 
                     WHERE p.pagado = 0 AND p.cancelado = 0 AND l.correo = :correo 
                     AND p.usuario = :usuario AND p.comida = :comida
                     AND p.cantidad = :cantidad AND p.opciones = :opciones";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':comida', $comida);
        $stmt->bindParam(':opciones', $opciones);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':correo', $correo);
        
        if ($stmt->execute()) {
            // Obtener el nuevo total después de la eliminación
            $consulta2 = "SELECT SUM(p.total) total 
                          FROM pedidos p, login l 
                          WHERE p.pagado=0 AND p.cancelado=0 AND l.correo=:correo AND p.usuario=:usuario;";
            $stmt = $conexion->prepare($consulta2);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            echo json_encode(['message' => 'Pedido eliminado correctamente', 'total' => $total]);
        } else {
            echo json_encode(['message' => 'Error al eliminar el pedido']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Hubo un error inesperado: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Datos no válidos']);
}
?>
