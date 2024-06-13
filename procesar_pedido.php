<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $usuario=$data['usuario'];
    $comida = $data['comida'];
    $opciones = $data['opciones'];
    $cantidad = $data['cantidad'];
    $total = $data['total'];

    try {
        $conexion = new PDO('mysql:host=localhost:3308;dbname=cooperativa_bd', 'root', 'root');
        $consulta = "
            INSERT INTO pedidos (id, usuario, comida, opciones, cantidad, total)
            VALUES (NULL, :usuario, :comida, :opciones, :cantidad, :total)
        ";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':usuario', $usuario); // Corregido el nombre del parámetro
        $stmt->bindParam(':comida', $comida); // Corregido el nombre del parámetro
        $stmt->bindParam(':opciones', $opciones);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':total', $total);

        

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Pedido insertado correctamente']);
        } else {
            echo json_encode(['message' => 'Error al insertar el pedido']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Hubo un error inesperado: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Datos no válidos']);
}
?>
