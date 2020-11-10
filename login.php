<?php
  require_once "funciones.php";  
  session_start();

/* CONECTAMOS CON LA BBDD*/

$conn=conexion();

if (isset($_POST['login'])) {
  
  //RECOJER DATOS INTRODUCIDOS POR EL USUARIO  
  
  $usu=$_POST["usuario"];
  $pass=$_POST["passwd"];

  
  login($conn, $usu, $pass);


if (substr(php_uname(), 0, 7) == "Windows"){
    //borrarmos los archivo .xlsm **Pendiente
    //pclose(popen("start /B " , "r")); 

}else {
    exec("rm -f ". $_SESSION["RutaInforme"] ."InformeEval*.xlsm");
}  

    
}//CIERRA SI HA PRESIONADO EL BOTON LOGIN

if (isset($_GET['logout'])=="Si") {//CERRAR SESION DESTRUYENDO LA VARIABLE

  unset($_SESSION['usuario']);
  session_destroy();
  header('Location: index.html');

}
?>