<?php
require_once "funciones.php";  
    session_start();
    $conn=conexion();
    
    //recuperamos los datos del usuario logeado
    if(isset($_SESSION['usuario'])){
        $idUsu = $_SESSION['idUsuario'];
        $nombreUsu = $_SESSION['nombre'];
        $usu = $_SESSION['usuario']; //username
        $tipo = $_SESSION["rolUsu"]; 


    } else {

      header('Location: index.html');    
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- My style -->
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body id="fondo">
       <?php include 'menu.php' ?>
    <div class="container text-center" >
        <h2 class="tituloSarrera"> Panel de Administración </h2>
        <h4 class="subSarrera"> Seleccione tarea a realizar </h4>
    </div>

    <div class="container-fluid text-center mb-3">
        <div class="row">
            <div>
                <button class="btn botonS" onclick="goConsultaPreg();">MANTENIMIENTO PREGUNTAS </button>
            </div>
            <div>
                <button class="btn botonS" onclick="goConsultaEval();">CONSULTA EVALUACIONES </button>
            </div>
            <div>
                <button class="btn botonS" onclick="goNuevaEval();">NUEVA EVALUACIÓN </button>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="js/miscript.js"></script>
</body>

</html>
