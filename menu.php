 <?php 
//ALUMNOS
require_once "funciones.php";
$conn = conexion();

if(isset($_SESSION['usuario'])){
    $usu = $_SESSION['usuario'];
    $nombreUsu = $_SESSION['nombre'];
    $tipo = $_SESSION["rolUsu"];

} else {

  header('Location: index.html');    
}

$conn=conexion();

    // depende del tipo de usuario cargaremos un menu u otro
    if ( $tipo == 1 ) {
?>
 
        <nav class="navbar-expand-lg navbar-light bg-light" >
            <a class="navbar-brand" href="sarrera.php"> <?php echo $nombreUsu; ?> </a>
            <button class="navbar-toggler navbar-toggler-right" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <button type="button" class="btn btn-light" onclick="goConsultaPreg();"> Mantenimiento Preguntas </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-light" onclick="goConsultaEval();"> Consulta Evaluaciones </button>
                    </li>
                    <li class="nav-item active">
                        <button type="button" class="btn btn-light" onclick="goNuevaEval();"> Nueva Evaluación </button>
                    </li>
                </ul>
                <span class="navbar-text">
                    <button type="button" class="btn btn-light" onclick="cerrarSesion();"> Cerrar Sesión</button>
                </span>
            </div>
        </nav>
        
<?php } else { ?>
        
        <nav class="navbar navbar-light bg-light">
            <span class="navbar-brand" href="#"> <b>
            <!-- Si es tu evaluacion, se muestra tu nombre  -->
            <?php 

                 $eval = $_SESSION["idEvaluacion"];

                $usuAutoevaluado = mysqli_query($conn, "SELECT nombreUsu FROM usuarios INNER JOIN evaluaciones ON evaluaciones.idEvaluacion = usuarios.idEvaluacion
                WHERE evaluaciones.idEvaluacion = $eval AND usuarios.rolUsu = 2");
                $data = mysqli_fetch_array( $usuAutoevaluado );
                $nombreAutoeval = $data[0];
                $_SESSION["nombreAutoeval"] = $nombreAutoeval;


                if($tipo == 2){
                    echo $nombreUsu; 
                    } else {
                        echo $nombreUsu . "</b> realizando evaluación para: <b>" . $nombreAutoeval;
                    }
            ?>
            </b></span>  
            <button class="btn btn-outline-success my-2 my-sm-0" onclick="cerrarSesion();"> Cerrar Sesión</button>

        </nav>
  <?php  } ?>    
        
