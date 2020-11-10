
/**********************************
//            SELECT
*********************************/

SELECT estadosevaluaciones.idEvaluacion, usuarios.nombreUsu , estados.nombreEstado, evaluaciones.fechaDesde, evaluaciones.fechaHasta  
FROM estadosevaluaciones
INNER JOIN usuarios ON usuarios.idUsuario = estadosevaluaciones.idUsuario
INNER JOIN estados ON estados.idEstado = estadosevaluaciones.idEstado
INNER JOIN evaluaciones ON evaluaciones.idEvaluacion = estadosevaluaciones.idEvaluacion 

    
    
    
    
//
SELECT evaluaciones.idEvaluacion, evaluaciones.idTipoProceso, evaluaciones.codEvaluacion, evaluaciones.nombreEmpresa, evaluaciones.fechaDesde, evaluaciones.fechaHasta, usuarios.idUsuario, usuarios.nombreUsu, usuarios.emailUsu, usuarios.username, usuarios.password 
FROM evaluaciones 
    inner join usuarios on evaluaciones.idEvaluacion = usuarios.idEvaluacion 
            WHERE evaluaciones.idEvaluacion = $evaluacion



//ACTUALIZAR LAS RESPUSTAS DE LA EVALUACION

UPDATE `respuestaseval` SET `idRespuestaEval`=[value-1],`idEvaluacion`=[value-2],`idPregunta`=[value-3],`idUsuario`=[value-4],`resultado`=[value-5] WHERE idUsuario = $usu AND idEvaluacion = $idEval AND idPregunta = $idPreg;




//usuarios + idEval + estados

SELECT evaluaciones.idEvaluacion, evaluaciones.idTipoProceso, evaluaciones.codEvaluacion, evaluaciones.nombreEmpresa, evaluaciones.fechaDesde, evaluaciones.fechaHasta, usuarios.idUsuario, usuarios.nombreUsu, usuarios.emailUsu, usuarios.username, usuarios.password, tipousuarios.nombreTipo, estados.nombreEstado
FROM evaluaciones 
inner join usuarios on evaluaciones.idEvaluacion = usuarios.idEvaluacion 
inner join tipousuarios on tipousuarios.idTipoUsuario = usuarios.rolUsu
inner join estados on estados.idEstado = evaluaciones.idEstado