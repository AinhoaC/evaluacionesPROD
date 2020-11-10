<?php
/*$fileName = $_SESSION["rutaCom"];
$filePath = 'files/'.$fileName;
if(!empty($fileName) && file_exists($filePath)){
    // Define headers
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$fileName");
    header("Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12");
    header("Content-Transfer-Encoding: binary");
    
    // Read the file
    readfile($filePath);
    exit;
}else{
    echo 'The file does not exist.';
}
*/
?>



<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

    <a href="archivo.php?download_csv=1">Descargar archivo csv</a>

</body>
</html>

/* Here is the Download.php file to force download stuff */

<?php
include 'funciones.php';

InicializarParametros();
 /*   $evaluacion = $_SESSION['idEvalSeleccionada'];

print("<BR> evaluacion --- > ". $evaluacion );
    fileName = $_SESSION["RutaInforme"] . $evaluacion .'.xlsm' ;
    $filePath = 'files/'.$fileName;

print("<BR> FILENAME --- > ". $fileName );
print("<BR> FILEPATH --- > ". $filePath );
die();
    */
    //$fileName = $_SESSION["RutaInforme"] . $evaluacion .'.xlsm' ;
    $filePath = 'files/'. $_SESSION["RutaInforme"];

        $f= $_SESSION["RutaInforme"] . $evaluacion .'.xlsm' ;   

       $file = ($filePath. "/" .$f);

       $filetype=filetype($file);

       $filename=basename($file);

       header ("Content-Type: ".$filetype);

       header ("Content-Length: ".filesize($file));

       header ("Content-Disposition: attachment; filename=".$filename);

       readfile($file);

/*

(.xlsm)

  // file name for download
  $filename = "website_data_" . date('Ymd') . ".xls";*/

?>


<?php

       $f= $_SESSION["RutaInforme"] . $evaluacion .'.xlsm' ;   

       $file = ($filePath."/$f");

       $filetype=filetype($file);

       $filename=basename($file);

       header ("Content-Type: ".$filetype);

       header ("Content-Length: ".filesize($file));

       header ("Content-Disposition: attachment; filename=".$filename);

       readfile($file);
