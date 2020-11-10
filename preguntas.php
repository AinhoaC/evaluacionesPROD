<?php
    require_once "funciones.php";  
    session_start();
    $conn=conexion();
    
    //recuperamos los datos del usuario logeado
    if(isset($_SESSION['usuario'])){
        $usu = $_SESSION['usuario'];
        $idUsu = $_SESSION['idUsuario'];
        $nombreUsu = $_SESSION['nombre'];
        $tipo = $_SESSION["rolUsu"];
        $idEval = $_SESSION["idEvaluacion"];

    } else {

      header('Location: index.html');    
    }


    //Declarar arrays 
    define('$campoValorRes',[]);
    

    // Indicamos que queremos bloques de 10 preguntas por página.
    $num_per_page = 10;
           

    // COMPROBAMOS SI EL USUARIO HA RESPONDIDO ALGUNA PREGUNTA Y SI ES ASÍ LAS CARGAMOS EN PANTALLA
    $query = mysqli_query( $conn, "SELECT idRespuestaEval, idEvaluacion, idPregunta, idUsuario, resultado FROM respuestaseval WHERE idUsuario = '$idUsu' ORDER BY idPregunta ASC");
    $numResult = mysqli_affected_rows( $conn );


//Movimientos en negativo serán paginaciones hacia atrás. En positivo serán hacia adelante.    
    if ( isset ( $_POST['txtMovimiento']) ) {
            if ( $_POST['txtMovimiento']>0 ) {
                $iNumPaginaCargar=intval($num_per_page * $_POST['txtMovimiento']);
            }else {
                $iNumPaginaCargar=$iNumPaginaCargar+intval($num_per_page * $_POST['txtMovimiento']);
            }
    }else {
         $iNumPaginaCargar =0;
    }

    //Inicializamos la página actual

    if ( isset ( $_POST['txtPagina']) ) {
        $iPaginaActual = $_POST['txtPagina'];
    } else {
        $iPaginaActual = $iNumPaginaCargar/$num_per_page;
    }



    $iPreg= 1;  // Debe dar el valor de la primera pregunta a mostrar en la página
    
    //inicializamos el array de respuestas con todos los valores a 0 por defecto
    for ($iCont = 1; $iCont <= 60; $iCont++) { 
        $campoValorRes[$iCont] = 0;
    }

    if ( $numResult != 0 ) {        
        while( $data = mysqli_fetch_array( $query ) ) {   
            # MARCAR LAS RESPUESTAS
            $campoValorRes[$iPreg]=$data[4];
//printf("<br>Marcamos la respuesta " . $iPreg . " ---- " . $data[4]);
            $iPreg++;                
        } //cierre while
    }//cierre numResult
    

    // cargamos la preguntas
    $selectPreg = mysqli_query($conn, "SELECT idPregunta, descripcion FROM preguntas LIMIT $iNumPaginaCargar, $num_per_page");


    //PAGINAR HACIA ADELANTE PROVOCA ACTUALIZAR LOS REGISTROS. DELETE Y UPDATE
    if (isset ($_POST['txtActualizar']) && ($_POST['txtActualizar']!="") ){  

        //Borramos los registros existentes en la tabla de respuestas

        $sqlBorrarResEval = "DELETE FROM respuestaseval WHERE idUsuario = $idUsu AND idPregunta BETWEEN " . intval($iNumPaginaCargar-$num_per_page+1) . " AND " . intval($iNumPaginaCargar) . ";";
 //printf("<br>Query" . $sqlBorrarResEval);
        
       $ResultBorrarResEval = mysqli_query($conn, $sqlBorrarResEval);
        if( $ResultBorrarResEval != 0 ) {
            //hacemos la insert en la tabla de respuestas
            //RECOGEMOS EL ULTIMO ID DE LA TABLA RESPUES TAS EVAL
            $sqlUltIdResEval = mysqli_query($conn, "SELECT idRespuestaEval FROM respuestaseval ORDER BY idRespuestaEval DESC LIMIT 1");
            $datoUltIdResEval = mysqli_fetch_array( $sqlUltIdResEval );


            $pregActualizar =  intval( $iNumPaginaCargar - $num_per_page + 1); //variable para sacar las preguntas actuales 
//printf("<br>pregActualizar " . $pregActualizar);               
            
            // recorrer las preguntas de la pagina actual e insertamos en la tabla
            for ($i = 1; $i <=10 ; $i++) {  
                
                // Recoger los valores del POST radioP99 siendo 99 el nº de la pregunta y el valor del post contiene la respuesta.
//printf("<br><br>Entramos al FOR a la vuelta " . $i);
                $RespuestaValor = intval( $_POST['radioP' . $pregActualizar ] );
//printf("<br>RespuestaValor " . $RespuestaValor);               
        
//printf("<br>Respuesta " . $RespuestaValor);               

      
//printf("<br>PREGUNTA ACTUALIZAR " .$pregActualizar);
//printf("<br>RespuestaValor " . $RespuestaValor);    

                $insertRes = "INSERT INTO respuestaseval (idRespuestaEval, idEvaluacion, idPregunta, idUsuario, resultado) VALUES (" . intval($datoUltIdResEval[0] + $i) .", $idEval, $pregActualizar, $idUsu, $RespuestaValor  );";
//printf("<br>insertRes " . $insertRes); 
                $insertarResEval =  mysqli_query($conn, $insertRes);

                    if ($insertarResEval != 0) { // Actualizamos estado del usuario a EN CURSO
                        $actualizarUsuEval = "UPDATE estadosevaluaciones SET idEstado = 2 WHERE idUsuario = $idUsu ";

                        $updateUsuEval = mysqli_query($conn, $actualizarUsuEval);

                    if ( $updateUsuEval == 0 ){ // Error en la actualización del estado del usuario 
                        echo "Error: " . $updateUsuEval . "<br>" . $conn->error;
                    }     
                    
                } else {
                     echo "Error: " . $insertarResEval . "<br>" . $conn->error;
                }
                
                $pregActualizar ++;
            }// cierre for 
       
            // Si llegamos a la última pantalla, salimos del cuestionario.
            if ($iPaginaActual>=6){
//printf("<br>Entramos por última página " . $iNumPaginaCargar);         
                // actualizamos el estado a finalizar de la evaluacion del usuario 
                
                // Comprobar que NO pueda haber respuestas con valor 0 en la tabla de respuestaseval para este usuario y evaluación
                $consultaUsuEval = mysqli_query($conn, "SELECT count(*) as TotalRegistros FROM `respuestaseval` WHERE idEvaluacion = $idEval and idUsuario = $idUsu and resultado = 0");
                
                $data = mysqli_fetch_array( $consultaUsuEval );        

                if ( $data[0] == 0 ) {
                    
                    $actualizarUsuEval = "UPDATE estadosevaluaciones SET idEstado = 3 WHERE idUsuario = $idUsu ";

                    $updateUsuEval = mysqli_query($conn, $actualizarUsuEval);

                    if ( $updateUsuEval != 0 ){
                        header('Location: final.html');
                    } else {
                        echo "Error: " . $updateUsuEval . "<br>" . $conn->error;
                    }     
                } else {
                    echo '<script type="text/javascript"> alert("Compruebe que todas las preguntas han sido respondidas adecuadamente."); </script> ';
                    header('Location: preguntas.php');
                    
                }

            }
        }  

        
    }//cierre $post pagSiguiente





?>

<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    
    <link rel="stylesheet" href="css/estilo.css">
    <title> FeedBack 360 </title>

    <!-- icono de la pantalla-->
    <link rel="shortcut icon" href="img/favicon.ico" />

    <script type="text/javascript">
        
        function Paginar(idMovimiento){
            // Función que realizará la paginación del formulario
            // si idMovimiento = -1 irá hacia atrás
            // si idMovimiento = 1 irá hacia adelante
            var idNumPaginaActual =0;
            idNumPaginaActual = parseInt(document.getElementById("txtPagina").value);
            

            if(idMovimiento == 1) {
                //en caso de ir ir Hacia Adelante, Primero comprobar si ha respondido a todas las preguntas de la página
                if(fHaRespondidoTodo()){
                    document.getElementById("txtPagina").value = parseInt(idNumPaginaActual)+1;
                    document.getElementById("txtMovimiento").value = parseInt(document.getElementById("txtMovimiento").value) + parseInt(idMovimiento);
                    document.getElementById("txtActualizar").value = "ACTUALIZAR"
                    document.getElementById("frmPreguntas").submit();
                }else alert("Debe responder a todas las preguntas de la página para poder avanzar.");
            }else{
                document.getElementById("txtPagina").value = parseInt(idNumPaginaActual)-1;
                document.getElementById("txtMovimiento").value = parseInt(document.getElementById("txtMovimiento").value) + parseInt(idMovimiento);
                document.getElementById("txtActualizar").value = ""
                document.getElementById("frmPreguntas").submit();
            }

        }

        
        function fHaRespondidoTodo(){
            //Función que comprueba el nº de respuestas que se han dado. Si son 10, es correcto.
            var iContRespondidas = 0;
            var iContPreg = <?php echo $iNumPaginaCargar ?> + 1 ;
            var iContPregMax = iContPreg+10;

            for ( iContPreg; iContPreg < iContPregMax; iContPreg++) {
                for (var iContResp = 1; iContResp <= 5; iContResp++) {                
                    if(document.getElementById("radioP"+iContPreg+"V"+iContResp).checked) iContRespondidas +=1;
                }
            }

            if(iContRespondidas==10){
                if(iContPreg<60){
                        document.getElementById('btnSiguiente').style.display = 'none';
                        document.getElementById('btnAtras').style.display = 'none';
                    }
                return true;
            } else { 
                return false;
            }


        }


        
    </script>    


</head>

<body>
    <?php include 'menu.php' ?>
    <!--<div>
        <button onclick="cargarRespuestas();"> CARGAR </button>
    </div>--> 
    <form id="frmPreguntas" name="frmPreguntas" method="POST">
        <!-- marco donde cargaremos las preguntas de 10 en 10 -->
        <input hidden name="txtPagina" id="txtPagina" value=" <?php echo $iPaginaActual;?>" >
        <input hidden name="txtMovimiento" id="txtMovimiento" value=" <?php if (isset ($_POST['txtMovimiento'])) { echo $_POST['txtMovimiento'];} else { echo 0;} ?>" >
        <input hidden name="txtActualizar" id="txtActualizar" value=" <?php if (isset ($_POST['txtActualizar'])) { echo $_POST['txtActualizar'];} else { echo "";} ?>" >
        <div class="container">

            <?php 
            
            $cont = $iNumPaginaCargar  + 1;

                while ( $row = mysqli_fetch_assoc($selectPreg)) {
            ?>

            <strong>
                <span style="font-size:1.2rem; "> <?php echo $row['idPregunta'] ?>. </span>
                <span style="font-size:1.2rem; "> <?php echo $row['descripcion'] ?> </span>
            </strong> <br>
            <?php 
                //cargamos las estrellas para la puntuaacion
                echo '<div class="input-group">
                         <div class="text-group-field">
                            <div class="inner-block">                
                                <input id="radioP'.$cont.'V1" name="radioP'.$cont.'" value="1" class="radio-custom input-group-field"  type="radio" ';
                                if ($campoValorRes[$cont] == 1){echo ' checked ';}
                                echo '>';
                                echo '<label for="radioP'.$cont.'V1"  class="radio-custom-label"> Muy bajo </label>
                            </div>
                        </div>                        <div class="text-group-field ">
                            <div class="inner-block">                
                                <input id="radioP'.$cont.'V2" name="radioP'.$cont.'" value="2" class="radio-custom input-group-field"  type="radio" ';
                                if ($campoValorRes[$cont] == 2){echo ' checked ';}
                                echo '>';
                                echo '<label for="radioP'.$cont.'V2"  class="radio-custom-label"> Bajo </label>
                            </div>
                        </div>

                        <div class="text-group-field ">
                            <div class="inner-block">                
                                <input id="radioP'.$cont.'V3" name="radioP'.$cont.'"  value="3" class="radio-custom input-group-field"  type="radio" ';
                                if ($campoValorRes[$cont] == 3){echo ' checked ';}
                                echo '>';
                                echo ' <label for="radioP'.$cont.'V3"  class="radio-custom-label"> Normal </label>
                            </div>
                        </div>


                        <div class="text-group-field">
                            <div class="inner-block">                
                                <input id="radioP'.$cont.'V4" name="radioP'.$cont.'" value="4" class="radio-custom input-group-field"  type="radio" ';
                                if ($campoValorRes[$cont] == 4){echo ' checked ';}
                                echo '>';
                                echo '<label for="radioP'.$cont.'V4"  class="radio-custom-label"> Destaca </label>
                            </div>
                        </div>

                        <div class="text-group-field ">
                            <div class="inner-block">                
                                <input id="radioP'.$cont.'V5" name="radioP'.$cont.'" value="5" class="radio-custom input-group-field"  type="radio" ';
                                if ($campoValorRes[$cont] == 5){echo ' checked ';}
                                echo '>';
                                echo '<label for="radioP'.$cont.'V5"  class="radio-custom-label text-center"> Excelente </label>
                            </div>
                        </div>
                    </div>';

                    $cont++; 
            } ?>
        </div>
        <br>
        <div class="container-fluid text-center">
            <?php 
                $pr_query = "SELECT idPregunta, descripcion FROM preguntas";
                $pr_result = mysqli_query($conn, $pr_query);
                $total_records = mysqli_num_rows($pr_result);       //echo $total_records;
                $total_page = ceil($total_records/$num_per_page) -1;  // echo $total_page;
                echo '<input class="botonP" type="button" id="btnAtras" name="btnAtras" onclick="Paginar(-1)" value="ATRAS"';
                if ( $iPaginaActual == 0 ) {
                     echo ' style="display:none"';
                } 
                echo '>';

                for ($i = 1; $i < $total_page; $i++ ) {
                    //echo "<a class='btn btn-primary' href='preguntas.php?page=".$i."'>$i </a>";
                }

                if  ( $i > $iPaginaActual ) {
                    //echo "<a type='submit' name='pagSiguiente' class='botonP' href='preguntas.php?page=".($page + 1)."'> Siguiente </a>";
                    echo '<input class="botonP" type="button" id="btnSiguiente" name="btnSiguiente" onclick="Paginar(1)" value="SIGUIENTE">';

                } elseif ($i == $total_page ) {
                    //25/10/2020 --> hay que comprobar que ha respondido a todas las preguntas y llevar a la pagina ---> "final.html"
                    echo '<input class="botonP" type="button" id="btnFinalizar" name="btnFinalizar" onclick="Paginar(1)" value="FINALIZAR">';  
                }      
            ?>
        </div>
    </form>
    <br>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/miscript.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
