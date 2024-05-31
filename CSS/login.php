<?php
    $email = $_POST['email'];
    $password = $_POST['password'];

    try{
        $conexion = new PDO('mysql:host=localhost:3308;dbname=cooperativa_bd' ,'root','root');

        $enunciado= $conexion->prepare("SELECT pass from login where correo = ?");
        $enunciado->bindParam(1,$email);
        $enunciado->execute();

        if($row = $enunciado->fetch()){
            $hash = $row['pass'];
            
            if(password_verify($password,$hash)){
                 // Inicio de sesión exitoso
        $_SESSION['usuario'] = $mail; // Asumiendo que estás usando el correo como nombre de usuario
        // Puedes guardar otros datos del usuario en la sesión si es necesario
        header("Location: PaginaMenu.html");
        exit();
            }else{
                header("Location: contraseña_incorrecta.html");
            }
            
        }else{
            header("Location: correo_incorrecto.html");
        }
        }
    catch(PDOException $e){
        echo "Error: " .$e->getMessage();
    }
?>