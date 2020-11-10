<?php

function InicializarParametros(){
    
//FUNCIÓN QUE RECOGE LOS PARÁMETROS GENERALES DE LA APLICACIÓN.
    
    // Ruta fichero generación informe (con código java dentro)
    $RutaPlantilla = "/var/www/html/evaluaciones/Informes/plantilla.xlsm";
    $_SESSION["RutaPlantilla"] = $RutaPlantilla;
    
    $RutaInforme = "/var/www/html/evaluaciones/Informes/";
    $_SESSION["RutaInforme"] = $RutaInforme;
    

    $NombreInforme = 'InformeEval';
    $_SESSION["NombreInforme"] = $NombreInforme;

    
    // Nombre del fichero de generación del infome
    $GeneradorInforme = "/var/www/html/evaluaciones/Informes/GeneradorInforme.sh";
    $_SESSION["GeneradorInforme"] = $GeneradorInforme;
    
    // Ruta JAR generador
    $JarInforme = "/var/www/html/evaluaciones/Informes/GenDocClient-v.1.0.0-SNAPSHOT.jar";
    $_SESSION["JarInforme"] = $JarInforme;
    
    // Ruta del informe generado
    $RutaInformeExpuesto = "http://62.99.79.163:9000/evaluaciones/Informes/";
    $_SESSION["RutaInformeExpuesto"] = $RutaInformeExpuesto;
    //http://intranet.4set.es:9000/evaluaciones/Informes/InformeEval_1.xlsm
    //http://104.168.1.34:84/evaluaciones/Informes/InformeEval_1.xlsm

}




function conexion () {

    //$conn = new mysqli( 'localhost', 'root', '', 'feedback' );
    $conn = new mysqli( '104.168.1.34', 'root', 'reverse', 'feedback' );

    if ( mysqli_connect_errno() ) {

        printf( "Falló la conexión: ", mysqli_connect_errno() );

        exit();

    }
    return $conn;
}


function login ( $conn, $usu, $pass ) {

    // COMPROBAMOS QUE EXISTE ESE USUARIO EN LA TABLA
    
        $existe = mysqli_query( $conn, " SELECT usuarios.idUsuario, nombreUsu, emailUsu, rolUsu, username, password
    FROM usuarios  WHERE usuarios.username = '$usu' AND usuarios.password = '$pass' ");

    
    
    if ( mysqli_num_rows( $existe ) != 0 ) {
        //SI EXISTEN DATOS
        $data = mysqli_fetch_array( $existe );
  
        if ( $usu == $data['username'] && $pass == $data['password'] ) {
            //COMRPOBAR QUE EL USUARIO Y CONTRASEÑA INTRODUCIDOS POR EL USUARIO SON IGUALES AL DE LA BBDD
            $nombreUsu = $data['nombreUsu'];
            $idUsu=$data[0];
            $_SESSION["usuario"] = $usu;
            $_SESSION["nombre"] = $nombreUsu;
            $_SESSION["idUsuario"] = $idUsu;            
            $_SESSION["rolUsu"] = $data['rolUsu'];

            //comprobar estado para saber si ha finalizado la evaluación
            
            
            $existe = mysqli_query( $conn, " SELECT usuarios.idUsuario, estadosevaluaciones.idEstado 
            FROM usuarios 
            INNER JOIN estadosevaluaciones ON estadosevaluaciones.idUsuario = usuarios.idUsuario WHERE usuarios.idUsuario = $idUsu ");
            
            
            $data = mysqli_fetch_array( $existe );
        
            
            if ($data[1] != 3) {
                
           
                /////
                if ( $_SESSION["rolUsu"] == 1 ) {

                    header( 'Location: sarrera.php' );

                } elseif ( $_SESSION["rolUsu"] == 2 || $_SESSION["rolUsu"] == 3 || $_SESSION["rolUsu"] == 4 ) {

                    $existeEnEvaluacion = mysqli_query( $conn, " SELECT usuarios.idEvaluacion, nombreUsu, emailUsu, rolUsu, username, password,  evaluaciones.fechaDesde, evaluaciones.fechaHasta
                    FROM usuarios  
                    INNER JOIN evaluaciones ON evaluaciones.idEvaluacion = usuarios.idEvaluacion 
                    WHERE usuarios.username = '$usu' AND usuarios.password = '$pass' 
                    AND ((evaluaciones.fechaDesde <= CURDATE() OR evaluaciones.fechaDesde is null) 
                    AND (evaluaciones.fechaHasta >= CURDATE() OR evaluaciones.fechaHasta is null))");        
                    $datosEval = mysqli_fetch_array( $existeEnEvaluacion );

                    //
                    $eval = $datosEval[0];
                    $_SESSION["idEvaluacion"] = $eval;

                    //SI EL USUARIO NO ESTA DENTRO DE LAS FECHAS NO ACCEDE
                    if ( mysqli_num_rows( $existeEnEvaluacion ) != 0 ) {
                        # code...
                        header( 'Location: bienvenido.php' );
                    } else {
                        echo'<script type="text/javascript">               
                                alert("La evaluacion ha sido finalizada");  
                                window.location.href="index.html";  
                            </script>';   
                    }     

                } else {

                    header( 'Location: sarrera.php' );
                } // cierre if existeEneval
            } else{
                echo'<script type="text/javascript">                
                        alert("El usuario ya ha completado la evaluación.");  
                        window.location.href="index.html";  
                    </script>'; 
            }
            
            // Si todo ha ido correctamente, cargar los parámetros generales de la aplicación.
            InicializarParametros();
            
        } else{
            echo'<script type="text/javascript">                
                    alert("El usuario no existe en estos momentos, intentelo más adelante.");  
                    window.location.href="index.html";  
                </script>';       


        }// cierre if $usu
    } else {// si no hay ningun resultado

        echo'<script type="text/javascript">                
                alert("Usuario / Password incorrecto.");  
                window.location.href="index.html";  
            </script>';       

    }//CIERRE IF $filas
} //cierre function login

?>