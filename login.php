<?php
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$errores = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dsn = 'mysql:host=localhost:3307;dbname=cooperativa_bd';
        $usuario = 'root';
        $contrasena = '';
        
        $conexion = new PDO($dsn, $usuario, $contrasena);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para obtener la contraseña y el nombre de usuario
        $enunciado = $conexion->prepare("SELECT pass, usuario, correo FROM login WHERE correo = ?");
        $enunciado->bindParam(1, $email);
        $enunciado->execute();

        if ($row = $enunciado->fetch()) {
            $hash = $row['pass'];
            $nombre = $row['usuario'];
            $correo = $row['correo'];

            if (password_verify($password, $hash)) {
                // Inicio de sesión exitoso
                $_SESSION['usuario'] = $email; // Almacena el correo en la sesión
                $_SESSION['nombre'] = $nombre; // Almacena el nombre de usuario en la sesión
                $_SESSION['correo']= $correo;
                header("Location: EstructuraProyecto.php");
                exit();
            } else {
                $errores = 'Correo o contraseña incorrectos';
            }
        } else {
            $errores = 'Correo o contraseña incorrectos';
        }
    } catch (PDOException $e) {
        $errores = "Error: " . $e->getMessage();
    }
}
?>
