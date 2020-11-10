<?php 
  require_once "funciones.php";  
  session_start();

    if(isset($_SESSION['usuario'])){
        $usu = $_SESSION['usuario'];
        $nombreUsu = $_SESSION['nombre'];
        $tipo = $_SESSION["rolUsu"];
    }

    $conn=conexion();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> Document</title>
    <link rel="shortcut icon" href="#" />
    <!-- mi estilo -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

</head>

<body id="fondo">  
   <?php include 'menu.php' ?>
    <div id="marcoBienvenido">
        <div class="container ">

            <span class="bienvenido">Bienvenido al feedback 360 de </span>
            <h4><?php echo $_SESSION["nombreAutoeval"]; ?> </h4>
           
            <p>Agradecemos que realice el siguiente cuestionario repondiendo con total honestidad.</p>
            <p>Tenga en cuenta que, para pasar de página, es necesario contestar a todas las preguntas.</p>
            
            <button class="btnIniciar" onclick="llevarPaginaPreg();"> Iniciar </button>       

        </div>
    </div>   
    
    <!-- ####################################################################################### -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/miscript.js"></script>
    


</body>

</html>
