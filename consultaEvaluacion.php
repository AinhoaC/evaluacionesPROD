<?php
require_once "funciones.php";

session_start();

if(isset( $_SESSION['usuario']) ){
    $usu = $_SESSION['usuario'];
    $nombreUsu = $_SESSION['nombre'];
    $tipo = $_SESSION["rolUsu"];
}

$conn = conexion();


// RECOGEMOS LOS POSIBLES ESTADOS DE UNA EVALUACION
function cargaEstados ( $conn ) {

    $sql = mysqli_query( $conn, "SELECT idEstado, nombreEstado FROM estados" );

    $filas = mysqli_num_rows( $sql );

    if ( $filas != 0 ) {
        echo '<option value="0" > Todas </option>';
        while( $data = mysqli_fetch_array( $sql ) ) {
           echo '<option id="inputEstado"  value="'; echo $data[0]; echo '">' ; printf( $data[1] ); '</option>';   
        } // CIERRE WHILE       
        
    } else {
        echo '<script type="text/javascript">alert("Error al obtener los estados");</script>';
    }//CIERRE IF
}

    $campoNombreEmpresa = "";
    $campoFechaDesde =  "";
    $campoFechaHasta =  "";
    $campoEstado = "";

    if ( isset( $_POST['buscar'] ) ) {

        $campoNombreEmpresa = $_POST['inputEmpresa'];
        $campoFechaDesde = date( 'Y-m-d', strtotime( $_POST['inputDesde'] ) );        
        $campoFechaHasta = date( 'Y-m-d', strtotime( $_POST['inputHasta'] ) );
        
        if ($campoFechaDesde == "1970-01-01" ) {
             $campoFechaDesde =  "";
        }
        if($campoFechaHasta == "1970-01-01" ) {
            $campoFechaHasta =  "";
        }
        
    }
 ?>

<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title> Consulta Evaluación </title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- mi estilo -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- icono de la pantalla-->
    <link rel="shortcut icon" href="#" />
</head>

<body>
    <?php include 'menu.php'?>
    <div class="container"> <br>
        <h1>Consulta Evaluaciones</h1> <br>
        <form method="post">
            <div class="row justify-content-around">
                <div class="form-group col-md-4">
                    <strong> <label for="inputEmpresa">Empresa:</label> </strong>
                    <input type="text" class="form-control" name="inputEmpresa" id="inputEmpresa" maxlength="30" placeholder="Nombre Empresa" value="<?php echo $campoNombreEmpresa; ?>">
                </div>

                <div class="form-group col-md-4">
                    <!-- Example split danger button -->
                    <strong> <label for="inputEstado">Estado:</label> </strong>
                    <select name="inputEstado" id="inputEstado">
                        <?php cargaEstados( $conn ); 
                          ?>
                    </select>
                </div>
            </div>

            <!-- fila 2 -->
            <div class="row justify-content-around">
                <div class="form-group col-md-4">
                    <strong> <label for="inputDesde">Fecha desde:</label>  </strong>
                    <input type="date" class="form-control" id="inputDesde" name="inputDesde" value="<?php echo $campoFechaDesde; ?>">
                </div>

                <div class="form-group col-md-4">
                    <strong> <label for="inputHasta">Fecha hasta:</label> </strong>
                    <input type="date"  class="form-control" id="inputHasta" name="inputHasta" value="<?php echo $campoFechaHasta; ?>">
                </div>
            </div>

            <div class="row justify-content-around">
                <div class="form-group col-md-4">
                    <input class="btn btnBuscar" id="submit" type="submit" name="buscar" value="BUSCAR">
                </div>
            </div>
        </form>
    </div>
    
    <?php
        if ( isset( $_POST['buscar'] ) ) {
            $empresa = $_POST['inputEmpresa'];
            $estado = $_POST['inputEstado'];
            $fDesde = date( 'Y-m-d', strtotime( $_POST['inputDesde'] ) );
            $fHasta = date( 'Y-m-d', strtotime( $_POST['inputHasta'] ) );
            $estado = $_POST['inputEstado'];

            $sqlquery = "SELECT evaluaciones.idEvaluacion, evaluaciones.idTipoProceso, evaluaciones.codEvaluacion, evaluaciones.nombreEmpresa, evaluaciones.fechaDesde, evaluaciones.fechaHasta, estados.nombreEstado, evaluaciones.idEstadoEval
            FROM evaluaciones 
            INNER JOIN estados on estados.idEstado = evaluaciones.idEstadoEval
            WHERE 1=1" ;            
                
            $empresa = trim( $empresa );
                
            if ( $empresa != "" ) {
                $sqlquery .=  " AND evaluaciones.nombreEmpresa LIKE '%$empresa%'";
            }

            if ( $fDesde != "1970-01-01" ) {

                if ( $fHasta != "1970-01-01" ) {
                    $sqlquery .= " AND evaluaciones.fechaDesde >= '$fDesde' AND evaluaciones.fechaHasta <= '$fHasta'";

                } else {
                    $sqlquery .= " AND evaluaciones.fechaDesde >= '$fDesde'";
                }                 
                           
            } else if($fHasta != "1970-01-01" &&  $fDesde == "1970-01-01") {
                $sqlquery .= " AND evaluaciones.fechaHasta <= '$fHasta'";
            }
            
             
            
            
            if ($estado != 0) {
                $sqlquery.= " AND evaluaciones.idEstadoEval = $estado";
            } 
            
            $sqlquery .= " ORDER BY evaluaciones.idEvaluacion ASC";
            
            //print ($sqlquery);
                
            $sql = mysqli_query( $conn, $sqlquery );

            if ( mysqli_affected_rows( $conn ) != 0 ) {
                echo '<form action="evaluacion.php" method="POST" id="formResultado">                    
                        <div class = "container">
                            <table class = "table table-hover">
                                <thead>
                                    <tr>                                                   
                                        <th scope="col"> Evaluación </th>
                                        <th scope="col"> Empresa </th>
                                        <th scope="col"> Fecha desde </th>
                                        <th scope="col"> Fecha hasta </th>
                                        <th scope="col"> Estado </th>
                                        </tr>
                                </thead>';
                                echo '<tbody>';
                    $cont = 1;
                    while( $data = mysqli_fetch_array( $sql ) ) {
                                            
                        
                            echo '<tr onclick="fConsultarEvaluacion('; echo $data[0]; echo')" style="cursor:pointer;">';
                                echo '<td> <span>'; echo $data[2]; echo '</span> </td>';
                                echo '<td> <span>'; echo $data[3]; echo '</span> </td>';
                                echo '<td> <span>'; if($data[4]!=""){echo date( 'd-m-Y', strtotime($data[4]));} echo '</span> </td>';
                                echo '<td> <span>'; if($data[5]!=""){echo date( 'd-m-Y', strtotime($data[5]));} ; echo '</span> </td>';
                                echo ' <td title="'; echo  $data[6]; echo '" >';
                    
                                if ( $data[7] == 1 ) {
                                    echo '<img src = "img/red.png" alt = "'; echo  $data[6]; echo '" name="no iniciada">';
                                } else if ( $data[7] == 2 ) {
                                    echo '<img src = "img/yellow.png" alt = "'; echo  $data[6]; echo '" name="iniciada">';
                                } else if ( $data[7] == 3 ) {
                                    echo '<img src = "img/green.png" alt = "'; echo  $data[6]; echo '" name="Finalizada">';
                               } 
                                
                            echo ' </td></tr>';
                       
                        $cont ++;
                    } //cierre while
                 echo '</tbody>';
            echo '</table>
            </div> 
            <input type="hidden" id="idEvalSeleccionada" name="idEvalSeleccionada" value=""/>
            <input type="hidden" id="consulta" name="consulta" value="consulta"/>
            </form>';

                
        // hay que poner el atributo de selected en el option
        $campoEstado = $_POST['inputEstado'];
        echo '<script type="text/javascript">document.getElementById("inputEstado").selectedIndex  = ' . $campoEstado . ' ;</script>';
                
    } else {
        echo '<h1 class="text-center"> No hay resultados </h1>';
        
    }//cierre if

}  ?>
    
    <script>
        
            function fConsultarEvaluacion(IdEval){
                //Llamar a la ventana de consulta de evaluaciones
                document.all.idEvalSeleccionada.value = IdEval
                document.getElementById("formResultado").submit();
                //window.location.href = "usuarioEval.php?idEval=" + IdEval                
            }
        
    </script>    

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/miscript.js"></script>
</body>

</html>
