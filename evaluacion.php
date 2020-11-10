<?php
require_once "funciones.php";

session_start();

if ( isset( $_SESSION['usuario'] ) ) {
    $usu = $_SESSION['usuario'];
    $idUsuario = $_SESSION['idUsuario'];
} else {

    header('Location: index.html');    
}

$conn = conexion();


$campoNombreEmpresa = "";
$campoFechaDesde =  "";
$campoFechaHasta =  "";
$campoCodEvaluacion = ultimaEvaluacion ( $conn );

define('$idUsu',[]);
define('$campoNombreUsu',[]);
define('$campoMailUsu',[]);
define('$campoUsername',[]);
define('$campoPassword',[]);
define('$rolUsu',[]);
define('$campoEstado',[]);

    for ( $i = 1; $i <= 12; $i++ ) {
        //Inicializar los valores de los campos de texto a mostrar
        $idUsu[$i] = "";
        $campoNombreUsu[$i] =  "";
        $campoMailUsu[$i] =  "";
        $campoUsername[$i] =  "";
        $campoPassword[$i] =  "";
        $rolUsu[$i] =  "";
        $campoEstado[$i] = "";
    }


function ActualizarValoresUsuarios($conn, $NumEvaluacion) {
 //Eliminar usuarios para el caso de actualizar una evaluación   
   
     for ( $i = 1; $i <= 12; $i++ ) {
            //recogemos de cada fila todos los datos
            $idUsu= $_POST[ 'IdUsuario'.$i];
            $campoName = $_POST[ 'inputName'.$i];
            $campoMail = $_POST['inputMail'.$i ];
            $campoUsu = $_POST[ 'inputUsu'.$i ];
            $campoPasswd = $_POST[ 'inputPass'.$i ];        
         
            //comprobamos que el campo NAME no este vacio
            if ( $campoName != "" ) {
                if ( $i == 1 ) {
                    // AUTOEVALUACION
                    $campoRolUsu = "2";

                } elseif ( $i >= 2  &&  $i <= 6 ) {
                    // NO SUBORDINADO
                    $campoRolUsu = "3";

                } elseif ( $i >= 7 && $i <= 12 ) {
                    // SUBORDINADO
                    $campoRolUsu = "4";

                }
                
                //ACTUALIZAMOS LOS USUARIOS                
                if($idUsu=="" || $idUsu == null){                
                    //Se ha creado un nuevo usuario en la parrilla y hay que insertarlo.            
                    //recogemos el ultimo idUsuario
                    $sql = mysqli_query( $conn, "SELECT idUsuario FROM usuarios ORDER BY idUsuario DESC LIMIT 1" );

                    if ( mysqli_num_rows( $sql ) != 0 ) {
                        $data = mysqli_fetch_array( $sql );

                        $idUsu = $data[0] + 1;
                    }else{ // Es el primer usuario a insertar.
                        $idUsu = 1;
                    }

                            
                  $sql = "INSERT INTO usuarios (idUsuario, idEvaluacion, nombreUsu, rolUsu, emailUsu, username, password) VALUES 
                    ($idUsu, $NumEvaluacion,'$campoName', $campoRolUsu, '$campoMail', '$campoUsu', '$campoPasswd')" ;
                  $sqlqueryEstados = "INSERT INTO estadosevaluaciones(idEvaluacion, idUsuario, idEstado) VALUES 
                    ($NumEvaluacion, $idUsu, 1)" ;
                    
                    
                }else{
                    //Ya existe el usuario y se tiene que modificar                
                    $sql = "UPDATE usuarios SET nombreUsu='$campoName', rolUsu='$campoRolUsu', emailUsu='$campoMail', username='$campoUsu', password='$campoPasswd' WHERE idUsuario='$idUsu' AND idEvaluacion = '$NumEvaluacion'";
                    $sqlqueryEstados="";
                }
              
            }else{
                //delete en caso de que esté vacio este campo y antes hubiese un usuario
                 if($idUsu!="" && $idUsu != null){
                    $sql = "DELETE FROM usuarios WHERE idUsuario='$idUsu' AND idEvaluacion = '$NumEvaluacion'";
                    $sqlqueryEstados = "DELETE FROM estadosevaluaciones WHERE idEvaluacion=$NumEvaluacion AND idUsuario=$idUsu" ;
                 }
                

                
            }//cierre if
         
                if ($sql!=""){
                    //Si hay algo que ejecutar, se ejecuta
                     $sql3 = mysqli_query( $conn, $sql);
                }
                if ($sqlqueryEstados!=""){
                    //Si hay algo que ejecutar, se ejecuta
                     $sql4 = mysqli_query( $conn, $sqlqueryEstados ); 
                }
                
         
            if ( $sql3 === TRUE ) {
               
                echo '<script type="text/javascript">
                        alert("Evaluación Actualizada correctamente"); 
                         window.location.href = "sarrera.php";
                     </script>';
            } else {
                echo "Error en la actualización de la Parrilla de Usuarios: <br>" . $conn->error;
            }

         
            //Inicializamos las Querys para la siguiente vuelta    
            $sql ="";
            $sqlqueryEstados="";
         
      } //cierre for   
    
    
}
    
function insertarValoresUsuarios($conn, $NumEvaluacion) {
    
      for ( $i = 1; $i <= 12; $i++ ) {
            //recogemos de cada fila todos los datos
            $campoName = $_POST[ 'inputName'.$i];
            $campoMail = $_POST['inputMail'.$i ];
            $campoUsu = $_POST[ 'inputUsu'.$i ];
            $campoPasswd = $_POST[ 'inputPass'.$i ];        

            //comprobamos que el campo NAME no este vacio
            if ( $campoName != "" ) {
                if ( $i == 1 ) {
                    // AUTOEVALUACION
                    $campoRolUsu = "2";

                } elseif ( $i >= 2  &&  $i <= 6 ) {
                    // NO SUBORDINADO
                    $campoRolUsu = "3";

                } elseif ( $i >= 7 && $i <= 12 ) {
                    // SUBORDINADO
                    $campoRolUsu = "4";

                }
                //recogemos el ultimo idUsuario
                $sql = mysqli_query( $conn, "SELECT idUsuario FROM usuarios ORDER BY idUsuario DESC LIMIT 1" );

                if ( mysqli_num_rows( $sql ) != 0 ) {
                    $data = mysqli_fetch_array( $sql );

                    $idUsu = $data[0] + 1;
                }else{ // Es el primer usuario a insertar.
                    $idUsu = 1;
                }

                $sqlquery = "INSERT INTO usuarios(idUsuario, idEvaluacion, nombreUsu, rolUsu, emailUsu, username, password) VALUES 
                 ($idUsu, $NumEvaluacion,'$campoName', $campoRolUsu, '$campoMail', '$campoUsu', '$campoPasswd')" ;

                 $sql2 = mysqli_query( $conn, $sqlquery );


                 $sqlqueryEstados = "INSERT INTO estadosevaluaciones(idEvaluacion, idUsuario, idEstado) VALUES 
                 ($NumEvaluacion, $idUsu, 1)" ;

                 $sql3 = mysqli_query( $conn, $sqlqueryEstados ); 

            }//cierre if
      } //cierre for   
}


function ultimaEvaluacion ( $conn ) {
    // recuperamos la ultima evaluacion de la bbdd
    $sql = mysqli_query( $conn, "SELECT idEvaluacion, codEvaluacion FROM evaluaciones ORDER BY idEvaluacion DESC LIMIT 1" );

    if ( mysqli_num_rows( $sql ) != 0 ) {
        $data = mysqli_fetch_array( $sql );        
        
        if ( $data[0] < 9 ) {
            $sPrefijoEval = 'P000' ;
        } else if ( $data[0] >= 9 &&  $data[0] < 99 ) {
            $sPrefijoEval =  'P00' ;
        } else if ( $data[0] >= 99 &&  $data[0] < 999 ) {
            $sPrefijoEval =  'P0' ;
        } elseif ( $data[0] >= 999) {
            $sPrefijoEval =  'P' ;
        }

        return $sPrefijoEval . strval($data[0] + 1);

    } else {
       return 'P0001'; // En este caso es la primera evaluación.

    }
    //CIERRA IF

}

if ( isset( $_POST['guardar']) || isset($_POST['actualizar']) ) {
    //SI VENIMOS DESDE NUEVA EVALUACIÓN, EJECUTAMOS ESTE CODIGO PARA GUARDAR LOS DATOS
    $evaluacion = $_POST['inputEvaluacion'];
    $empresa = $_POST['inputEmpresa'];
    $fecha_actual = date("Y-m-d");
    $fDesde = date( 'Y-m-d', strtotime( $_POST['inputDateDesde'] ) );
    $fHasta = date( 'Y-m-d', strtotime( $_POST['inputDateHasta'] ) );
    $numEval = str_split( $evaluacion, 3 );
	
    if ( $fDesde != "1970-01-01" ) {
        
        if($fecha_actual >= $fDesde){
            $estadoEvaluacion = 2; //Estado En Curso
            
            if ( $fHasta != "1970-01-01" ) {
                
                if($fecha_actual <= $fHasta){
                    $estadoEvaluacion = 2; //Estado En Curso
                } else{
                    $estadoEvaluacion = 3; //Estado Cerrado
                }

            } else{
                $estadoEvaluacion = 2; //Estado En Curso
            }

        } else{
            $estadoEvaluacion = 1; //Estado No Iniciada
        }
    } else{
        $estadoEvaluacion = 1; //Estado No Iniciada
    }
    //SI VENIMOS DESDE CONSULTA EVALUACION Y VAMOS HA ACTUALIZAR LOS DATOS
    if (isset( $_POST['actualizar'])) {
        
         // query que actualiza la evaluacion
            $query = "UPDATE evaluaciones SET idTipoProceso=1, codEvaluacion='$evaluacion', nombreEmpresa='$empresa', idEstadoEval = '$estadoEvaluacion', ";

            if ($fDesde == date( 'Y-m-d', strtotime("1970-01-01")) ) {
               $query .= "fechaDesde = null, ";
            }else{
                $query .= "fechaDesde='" . $fDesde . "', ";
            }

            if ($fHasta == date( 'Y-m-d', strtotime("1970-01-01")) ) {
               $query .= "fechaHasta = null ";
            }else{
                $query .= "fechaHasta='" . $fHasta . "' ";
            }    
        
         $query .= " WHERE idEvaluacion = '$numEval[1]'";
        
        if ( $conn->query( $query ) === TRUE ) {
            
            ActualizarValoresUsuarios($conn, $numEval[1]); 
              
        } // query
            
        
    }else{
    
            // query que inserta la nueva evaluacion
            $query = "INSERT INTO evaluaciones(idEvaluacion, idTipoProceso, codEvaluacion, cifEmpresa, nombreEmpresa, idEstadoEval, fechaDesde, fechaHasta ) VALUES ('$numEval[1]', 1,'$evaluacion', null, '$empresa', $estadoEvaluacion, ";

            if ($fDesde == date( 'Y-m-d', strtotime("1970-01-01")) ) {
               $query .= "null, ";
            }else{
                $query .= "'" . $fDesde . "', ";
            }

            if ($fHasta == date( 'Y-m-d', strtotime("1970-01-01")) ) {
               $query .= "null)";
            }else{
                $query .= "'" . $fHasta . "')";
            }    


            if ( $conn->query( $query ) === TRUE ) {
                insertarValoresUsuarios($conn, $numEval[1]); 
               
                echo '<script type="text/javascript">
                        alert("Evaluación guardada correctamente"); 
                         window.location.href = "sarrera.php";
                     </script>';
        } else {
        echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
}


 # Generamos el informe excel en base a las preguntas de la base de datos
    if ( isset( $_POST['informe'] ) ) {
        
        $evaluacion = $_POST['inputEvaluacion'];
        $_SESSION['idEvalSeleccionada'] = $evaluacion;
        $evaluacion = intval(substr($evaluacion, 1));

       //Primero hay que cerrar la fecha hasta de la evaluación
        $updateFechEval = mysqli_query( $conn, "UPDATE evaluaciones set evaluaciones.fechaHasta = CURDATE() - 1 , idEstadoEval = 3 WHERE evaluaciones.idEvaluacion = $evaluacion");
        
         
        //A continuación se genera el infome de la evaluación
        
        $evalPregUsu = mysqli_query( $conn, "SELECT respuestaseval.idEvaluacion, usuarios.idUsuario, usuarios.rolUsu, respuestaseval.idPregunta, respuestaseval.resultado, preguntas.categoria, preguntas.subCategoria,  usuarios.nombreUsu
                FROM respuestaseval 
                INNER JOIN usuarios on usuarios.idUsuario = respuestaseval.idUsuario
                INNER JOIN estadosevaluaciones on estadosevaluaciones.idUsuario =  respuestaseval.idUsuario
                INNER join preguntas on preguntas.idPregunta = respuestaseval.idPregunta
                WHERE respuestaseval.idEvaluacion = $evaluacion
                AND preguntas.idTipoProceso = 1
                AND estadosevaluaciones.idEstado = 3
                ORDER BY respuestaseval.idEvaluacion, usuarios.idUsuario, usuarios.rolUsu, preguntas.categoria, preguntas.subCategoria, respuestaseval.idPregunta ASC");
        
        if ( mysqli_num_rows( $evalPregUsu ) != 0 ) {

            $iContPregunta= 3;
            $iContUserSub = 4;
            $iContUserNoSub = 10;
            $iContBloque = 0;
            $CadenaJSON = "";
            

            while( $data = mysqli_fetch_array( $evalPregUsu ) ) {
                if($CadenaJSON==""){

                    if (substr(php_uname(), 0, 7) == "Windows"){
                        $CadenaJSON = "java -jar " . $_SESSION["JarInforme"] . " '[";       
                    }
                    else {
                        $CadenaJSON = "#!/bin/bash\n java -jar " . $_SESSION["JarInforme"] . " '[";

                    }  
                    //Ponemos el nombre del evaluado en el infome. Solo la primera vez
                    
                    $CadenaJSON .= "{\'page\':\'Datos\',\'row\':0,\'col\':3,\'data\':\'". $data[7]."\'},"; 
                }
                
                if($data[2]==2){ //SOLO ENTRA PARA AUTOEVALUADOS
                    if ($iContPregunta<=74){
                        //AUTOEVALUADO COL 4, del ROW 4 al 74. Cada 5 meter uno vacio.
                        $CadenaJSON .= "{\'page\':\'Datos\',\'row\':".$iContPregunta.",\'col\':3,\'data\':\'". $data[4]."\'},"; 

                    }else{
                        $iContPregunta = 1;
                        $iContBloque=0;
                    }
                }

                 if($data[2]==3){ //SOLO ENTRA PARA SUBORDINADOS                   
                    if ($iContPregunta<=74){
                        ///SUB COL del 5 al 10, del ROW 4 al 74. Cada 5 meter uno vacio.
                        $CadenaJSON .= "{\'page\':\'Datos\',\'row\':".$iContPregunta.",\'col\':".$iContUserSub.",\'data\':\'".$data[4]."\'},"; 
                        
                    }else{
                        $iContPregunta = 3;
                        $iContBloque=0;
                        $iContUserSub++;
                    }
                }       



                 if($data[2]==4){ //SOLO ENTRA PARA NO SUBORDINADOS
                    if ($iContPregunta<=74){
                        //NOSUB COL del 11 al 15, del ROW 4 al 74. Cada 5 meter uno vacio.
                        $CadenaJSON .= "{\'page\':\'Datos\',\'row\':".$iContPregunta.",\'col\':".$iContUserNoSub .",\'data\':\'".$data[4]."\'},"; 
                    }else{
                        $iContPregunta = 3;
                        $iContBloque=0;
                        $iContUserNoSub++;
                    }
                }       

                $iContPregunta++;
                $iContBloque++;
                if ($iContBloque==5){ 
                    $iContBloque=0;
                    if ($iContPregunta<74){
                        $iContPregunta++;
                    }else{ 
                        $iContPregunta=3;
                            if($data[2]==3){ //SOLO ENTRA PARA SUBORDINADOS
                                $iContUserSub++;
                            }elseif($data[2]==4){ //SOLO ENTRA PARA NO SUBORDINADOS
                                    $iContUserNoSub++;
                                  }
                    }
                }

                
            } // cierre while data

            $CadenaJSON = substr($CadenaJSON, 0, -1); 

            $CadenaJSON .= "]' '" . $_SESSION["RutaPlantilla"] ."' '" . $_SESSION["RutaInforme"] . $_SESSION["NombreInforme"] . '_' .$evaluacion . ".xlsm'";
            $CadenaJSON = str_replace("'", '"', $CadenaJSON);
        
//printf("<br> Cadena JSON: " . $CadenaJSON);
          
            $archivo = fopen($_SESSION["GeneradorInforme"], "w+");
           

            //abrimos el ficchero .bat para añadir la cadena de jSON
            $cadena = file_get_contents($_SESSION["GeneradorInforme"]);
            
            $cadena .= $CadenaJSON;

            file_put_contents($_SESSION["GeneradorInforme"], $cadena);
            
            fflush($archivo); // forzamos a que escriba los valores
            fclose($archivo); //cerramos el achivo 
            

    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $_SESSION["GeneradorInforme"] , "r")); 
    }
    else {
        exec($_SESSION["GeneradorInforme"] . " > /dev/null &");  
    }  

    //guardamos el nombre del archivo en una variable de sesion 
    $rutaCompleta = $_SESSION["NombreInforme"] . "_" .$evaluacion . ".xlsm";
   
   
    echo '<script type="text/javascript">
            
                
            setTimeout(function(){ window.open("'; echo   $_SESSION["RutaInformeExpuesto"]  . $rutaCompleta; echo '") }, 5000);
            setTimeout(window.alert("Informe generado correctamente.\nEspere unos segundos antes de pulsar aceptar para descargarlo."), 3000);
            
            window.location.href="sarrera.php";

        </script>';

            
        } else {            
           echo '<script type="text/javascript">
                        alert("No hay registros para generar informe"); 
                        window.location.href = "sarrera.php";
                     </script>';
        }
    }


if ( isset( $_POST['consulta'] ) ) {
    //SI VENIMOS DESDE CONSULTA EVALUACIÓN, EJECUTAMOS ESTE CODIGO

    $evaluacion = $_POST['idEvalSeleccionada'];
    $_SESSION['idEvalSeleccionada'] = $evaluacion;

    $query = mysqli_query( $conn, "SELECT evaluaciones.idEvaluacion, evaluaciones.idTipoProceso, evaluaciones.codEvaluacion, evaluaciones.nombreEmpresa, evaluaciones.fechaDesde, evaluaciones.fechaHasta, usuarios.idUsuario, usuarios.nombreUsu, usuarios.emailUsu, usuarios.username, usuarios.password, usuarios.rolusu, estados.nombreEstado, estadosevaluaciones.idEstado
        FROM evaluaciones 
        inner join usuarios on usuarios.idEvaluacion = evaluaciones.idEvaluacion
        inner join estadosevaluaciones on usuarios.idUsuario = estadosevaluaciones.idUsuario
        inner join estados on estados.idEstado = estadosevaluaciones.idEstado
        WHERE evaluaciones.idEvaluacion = $evaluacion 
        ORDER BY usuarios.idusuario ASC ");
    
    $contFila = 1;
    $contNSub = 2; 
    $contSub = 7;

    while( $data = mysqli_fetch_array( $query ) ) {
        $numeroFilas = mysqli_num_rows( $query );
        $campoCodEvaluacion =  $data[2];
        $campoNombreEmpresa = $data[3];
        $campoFechaDesde =  $data[4];
        $campoFechaHasta =  $data[5];            

        for ( $i = 1; $i <= 12; $i++ ) {   
            if($data[11] == 2){
                // Usuario Autoevaluacion
                   $contFila = 1;
            } elseif($data[11] == 3){
                // Usuario No Subordinado
                $contFila = $contNSub; // comienza en la fila 2 del grid de pantalla
                $contNSub++;
            } else{
               // Usuario Subordinado   
                $contFila = $contSub; // comienza en la fila 7 del grid de pantalla
                $contSub++;
            }
            
            $campoNombreUsu[$contFila] = $data[7];
            $campoUsername[$contFila] = $data[9];
            $campoMailUsu[$contFila] = $data[8];
            $campoPassword[$contFila] = $data[10];
            $rolUsu[$contFila] = $data[11];
            $campoEstado[$contFila] = $data[13];      
            $DescEstado[$contFila] = $data[12];      
            $idUsu[$contFila] = $data[6]; 
               
            $contFila++;
            break;    
            
        }
    }
} 
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title> FeedBack 360 </title>
    <!-- icono de la pantalla-->
    <link rel="shortcut icon" href="#" />
    <!-- mi estilo -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

</head>

<body>
    <!-- incluir menu -->
    <?php include 'menu.php'?>
    <div class="container-fluid">
        <h1>Nueva Evaluación</h1> <br>
        <form method="post">
            <div class="row justify-content-around">
                <div class="form-group col-sx-3">
                    <strong> <label for="inputEvaluacion">Evaluación:</label> </strong>
                    <input type="text" class="form-control" id="inputEvaluacion" name="inputEvaluacion" placeholder="Evaluación" maxlength="5" value="<?php echo $campoCodEvaluacion; ?>" readonly>
                </div>
                <div class="form-group col-sx-3">
                    <strong> <label for="inputDateDesde">Fecha Desde:</label> </strong>
                    <input type="date" class="form-control" id="inputDateDesde" name="inputDateDesde" value="<?php echo $campoFechaDesde; ?>" required>
                </div>
            </div>
            <!-- fila 2 -->
            <div class="row justify-content-around">
                <div class="form-group col-sx-3">
                    <strong> <label for="inputEmpresa">Empresa: </label> </strong>
                    <input type="text" class="form-control" id="inputEmpresa" name="inputEmpresa" placeholder="Nombre empresa" maxlength="25" value="<?php echo $campoNombreEmpresa; ?>" required>
                </div>
                <div class="form-group col-sx-3">
                    <strong> <label for="inputDateHasta">Fecha Hasta: </label> </strong>
                    <input type="date" class="form-control" id="inputDateHasta" name="inputDateHasta" onblur="comprobarFechas();" value="<?php echo $campoFechaHasta; ?>">
                </div>
            </div> <br>
            <div class="table-responsive-sm text-center">
                <table class="table table-borderless" width="100%" cellspacing="0" >
                    <thead>
                        <tr>
                            <th scope="col" style="width:2%"> </th>
                            <th scope="col" style="width:23%"> Nombre y Apellido</th>
                            <th scope="col" style="width:23%"> Mail </th>
                            <th scope="col" style="width:15%"> Rol </th>
                            <th scope="col" style="width:12%"> Usuario </th>
                            <th scope="col" style="width:12%"> Contraseña </th>
                            <th scope="col" style="width:10%"> Enviar mail</th>
                            <?php 
                            if ( isset( $_POST['consulta'] )  ) {
                                echo '<th scope="col"> Estado </th>';
                            }?>

                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $contNSub = 1; 
                            $contSub = 1;

                            for ($iFila = 1; $iFila <= 12 ; $iFila++) { 
                                if ($iFila == 1 ) {
                                    $colorFila = "table-info";
                                    $tipoEvaluacion = "Autoevaluación";  
                                    $campoRequerido = "required";
                                } elseif ($iFila >= 2 && $iFila <= 6) {
                                    $colorFila = "table-success";
                                    $tipoEvaluacion = "No Subordinado";
                                    $tipoEvaluacion .= $contNSub;
                                    $campoRequerido = "";
                                    $contNSub++;
                                } elseif ($iFila >= 7 && $iFila <=12){      
                                    $colorFila = "table-danger";
                                    $tipoEvaluacion = "Subordinado";
                                    $tipoEvaluacion .= $contSub; 
                                    $campoRequerido = "";
                                    $contSub++;
                                }          
                                echo '<!-- fila'; echo $iFila;  echo '--> ';
                                echo '<tr  class="'; echo $colorFila; echo'">';
                                    echo '<th scope="row">'; echo $iFila;  echo '<input hidden name="IdUsuario'; echo $iFila; echo '" id="IdUsuario'; echo $iFila; echo ' " value="'; echo $idUsu[$iFila]; echo '"></th>';
                                    echo '<td> <input class="clsInputNomMail " type="text"'; echo $campoRequerido; echo' name="inputName'; echo $iFila; echo '" id="inputName'; echo $iFila; echo '" onchange="crearUsr('; echo $iFila; echo ')"'; echo ' value="'; echo $campoNombreUsu[$iFila]; echo '"> </td>';
                                    echo '<td> <input class="clsInputNomMail" type="mail"'; echo $campoRequerido; echo' name="inputMail'; echo $iFila; echo '" id="inputMail'; echo $iFila; echo '" placeholder="example@gmail.com" value="'; echo $campoMailUsu[$iFila]; echo '"> </td>';
                                    echo '<td> <span type="text" name="inputRolUsu'; echo $iFila; echo '" id="inputRolUsu'; echo $iFila; echo '" >'; echo $tipoEvaluacion; echo '</span> </td>';
                                    echo '<td> <input class="clsInputUsrPwd" type="text" name="inputUsu'; echo $iFila; echo '" id="inputUsu'; echo $iFila; echo '" value="'; echo $campoUsername[$iFila]; echo '"> </td>';
                                    echo '<td> <input class="clsInputUsrPwd" type="text" name="inputPass'; echo $iFila;  echo '" id="inputPass'; echo $iFila;  echo '" readonly value="'; echo $campoPassword[$iFila];   echo '"> </td>';
                                    echo '<td> <button type="button" class="btn btn-primary" onclick="enviarMail('; echo $iFila; echo ')'; echo'"> <i class="far fa-envelope"> </i>'; echo ' </button> </td>';

                                    if ( isset( $_POST['consulta'] )  ) {
                                        if ($campoEstado[$iFila] != null){
                                            if ( $campoEstado[$iFila] == 1  ) {
                                                echo ' <td title="'; echo $DescEstado[$iFila]; echo '" > <img src = "img/red.png" alt = "no iniciada" name="no iniciada"> </td>';
                                            } else if ( $campoEstado[$iFila] == 2 ) {
                                                echo ' <td title="'; echo $DescEstado[$iFila]; echo '"> <img src = "img/yellow.png" alt = "iniciada" name="iniciada">  </td>';

                                            } else if ( $campoEstado[$iFila] == 3 ) {
                                                echo '<td title="'; echo $DescEstado[$iFila]; echo '"> <img src = "img/green.png" name="Finalizada"> </td>';
                                            } 
                                        } else {
                                                echo '<td></td>';
                                        }
                                    }
                                echo '</tr>';
                                
                            }?>



                    </tbody>
                </table>

                <?php
               if (isset( $_POST['consulta'])) { ?>
                <!-- BOTON DE ACTUALIZAR -->
                <div class="container-fluid">
                    <div class="form-group ">
                        <input class="boton" id="actualizar" type="submit" name="actualizar" value="ACTUALIZAR" title="Actualizar información">
            
                        <input class="boton" id="informe" type="submit" name="informe" value="INFORME y CIERRE" title="Generar informe y cerrar la evaluación">
                    </div>
                </div>

                <?php } else {  ?>

                <!-- BOTON DE GUARDAR -->
                <div class="container-fluid">
                    <div class="form-group ">
                        <input class="boton" id="guardar" type="submit" name="guardar" value="GUARDAR">
                    </div>
                </div>

                <?php }?>
            </div>
        </form>
    </div>


    <!-- ####################################################################################### -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/miscript.js"></script>

</body>

</html>
