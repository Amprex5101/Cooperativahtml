<?php
function obtenerConexion() {
    try {
        $conexion = new PDO('mysql:host=localhost:3307;dbname=cooperativa_bd', 'root', '');
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Error de conexión: ' . $e->getMessage()]);
        exit();
    }
}
?>
