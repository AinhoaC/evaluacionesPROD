<?php
  require_once "funciones.php";  
  session_start();

    if(isset($_SESSION['usuario'])){
        $usu=$_SESSION['usuario'];
    }

    $conn=conexion();

    define('$campoPregunta',[]);
    define('$campoCategoria',[]);
    define('$campoTipoCat',[]);
    $bActualizacionCorrecta = TRUE;
    
    // BOTON GUARDAR --- Guarda en la base de datos todas las preguntas con su categoria correspondiente.
    if (isset ($_POST['btnGuardar']) ) {
         //print ("<BR> ENTRAMOS POR GUARDAR. ");

        // Primero borramos lo que hubiera en la base de datos para darlo de alta de nuevo.

            $queryBorrar = mysqli_query($conn, "DELETE FROM preguntas where idTipoProceso=1");

            if ( $queryBorrar === TRUE ) {
                // ha funcionado la limpieza de los datos de las preguntas de tipo 1 y procedemos a insertar las nuevas
            
                //recorremos todas las preguntas y recuperamos lo que habia en el textarea
                $contPreguntas = 60;
                for ($iFila = 1; $iFila <= $contPreguntas; $iFila++) {

                    $campoCategoria = $_POST['cboCategoria' . $iFila];
                    $campoPregunta = $_POST['txtPregunta' . $iFila];
                    $campoTipoCat = $_POST['txtTipoCat' . $iFila];
                    //print ("<BR> . " . $campoCategoria);

                    $queryInsertar = mysqli_query($conn, "INSERT INTO preguntas (idPregunta, idTipoProceso, categoria, subCategoria, descripcion) VALUES ($iFila, 1 , $campoCategoria, $campoTipoCat, '$campoPregunta')");
                   
                    if ( $queryInsertar === FALSE ) {
                        echo "Error en la actualización de las preguntas de las evaluaciones: <br>" . $conn->error;
                        $bActualizacionCorrecta = false;
                    }
                }

            }else{
                echo "Error en el borrado de las preguntas existentes <br>" . $conn->error;
                $bActualizacionCorrecta = false;
            }

        if ($bActualizacionCorrecta){
            echo '<script type="text/javascript">alert("Información actualizada correctamente");</script>';
        }        
    } // cierre if isset
    
//////////////////////////////
//Fichero
/*
$nFichero = $_POST['fichero']; 
$rFichero = addslashes(file_get_contents($_FILES['fichero']['tmp_name']));

$query = "INSERT INTO evaluaciones(idEvaluacion, idTipoProceso, codEvaluacion, cifEmpresa, nombreEmpresa, fechaDesde, fechaHasta, plantillaExcel, resultadoPDF) VALUES ('') ";
*/
   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">

    <!-- icono de la pantalla-->
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>

<body id="fondo">
    <?php include 'menu.php'?>
    <br><br><br>
    <div class="container">
        <h1>Preguntas</h1>

        <form method="POST">
            <!--<div class="form-group">
                <span for="exampleFormControlFile1"> Plantilla informe: </span>
                <input type="file" accept=".xls,.xlsx,.xlsm" class="form-control-file" name="fichero" enctype="multipart/form-data">
            </div>

            <br><br><br>

            <h3>Preguntas: </h3> --><hr>

            <div class="container-fluid">
                <?php     
                //esta query automaticamente agrupa todas las preguntas con el mismo idCompetencia
                //$sqlquery = "SELECT preguntas.idPregunta, preguntas.descripcion, competencias.idCompetencia, competencias.desCompetencia FROM preguntas INNER JOIN competencias ON competencias.idCompetencia = preguntas.categoria" ;            
                
                //esta query muestra las preguntas tal y como estan en la base datos
                $sqlquery = "SELECT preguntas.idPregunta, preguntas.descripcion, competencias.idCompetencia, competencias.desCompetencia, preguntas.subCategoria FROM preguntas INNER JOIN competencias ON competencias.idCompetencia = preguntas.categoria ORDER BY idPregunta ASC" ;            
                
                $sql = mysqli_query( $conn, $sqlquery);
                $cont = 1;
                if ( mysqli_affected_rows( $conn ) != 0 ) {        
                    while( $data = mysqli_fetch_array( $sql ) ) {                 
                        
                        echo '<br><br> <strong><span id="preg';  echo $cont; echo '" name="preg'; echo $cont; echo '">Pregunta '; printf( $cont ); echo ':</span></strong> </br>';

                        echo '<textarea rows="2" cols="90" id="txtPregunta'; echo $cont; echo '" name="txtPregunta'; echo $cont; echo '">'; 
        
                        printf( $data[1] ); //desPregunta
                        echo '</textarea> &nbsp; &nbsp;';
                        echo '<input hidden id="txtTipoCat';  echo $cont; echo '" name="txtTipoCat'; echo $cont; echo '" value="'; echo $data[4] ; echo '">';
                           
                        // hacemos la select de todas las categorias y le ponemos la 
                        // categoria a la que pertenece por defecto
                        $query = mysqli_query($conn, "SELECT idCompetencia, claseCompetencia, desCompetencia FROM competencias");
                                  
                        if ( mysqli_affected_rows( $conn ) != 0 ) {
                            echo '<select class="cbCategoria" id="cboCategoria';  echo $cont; echo '" name="cboCategoria'; echo $cont; echo '">';
                            while ( $data2 =  mysqli_fetch_array( $query ) ) {
                                    if ($data[2] == $data2[0]) {
                                        echo '<option selected value="'; echo $data2[0]; echo '">' ; printf( $data2[2] ); echo '</option>';            
                                    } else {
                                        echo '<option value="'; echo $data2[0]; echo '">' ; printf( $data2[2] ); echo '</option>';            
                                    }     
                            } //cierre while
                            
                            echo '</select>';   
                        }//cierre if
                        
                        
                        $cont++;
                    }//cierre while     
                    
                    echo '<br><br> <div class="container text-center">
                            <button class="btnGuardar" id="btnGuardar" name="btnGuardar" value="Guardar"> GUARDAR </button>
                        </div> <br><br><br><br>';
                    
                } else {
                    echo '<h2>No se han encontrado datos</h2>';
                }//cierre if    
    
            ?>

            </div>
        </form>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="js/miscript.js"></script>



</body>

</html>
