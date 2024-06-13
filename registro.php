<?php

$nombre_usuario = $_POST['name'];
$correo = $_POST['email'];
$password = $_POST['password'];


//comprobar que pass1 sea igual a pass2
if (empty($_POST['password'])) {
    echo "La contraseña no puede estar vacía";
} else {
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $conexion = new PDO('mysql:host=localhost:3308;dbname=cooperativa_bd', 'root', 'root');
        $enunciado = $conexion->prepare("INSERT INTO login VALUES(?,?,?)");
        $enunciado->bindParam(1, $nombre_usuario);
        $enunciado->bindParam(2, $correo);
        $enunciado->bindParam(3, $hashedPassword);

        if ($enunciado->execute()) {
            header("Location: index.php");
        } else {
            echo "Error al insertar datos: " . $enunciado->errorInfo()[2];
        }
    } catch (PDOException $e) {
        echo "ERROR: " . $e->getMessage();
    }
}

?>