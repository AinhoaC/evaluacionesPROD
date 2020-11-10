//Variable global con las respuestas a las preguntas.
var arrRespuestas = new Array();

// FUNCION PARA ENVIAR EL CORREO ELECTRONICO
function enviarMail(idfila) {

    IdElementoNombre = "inputName1"; // Se coge siempre el nombre de la pesona Autoevaluada. Fila 1
    IdElementoEmail = "inputMail" + idfila;
    IdElementoUsr = "inputUsu" + idfila;
    IdElementoPwd = "inputPass" + idfila;

    campoEmail = document.getElementById(IdElementoEmail).value;
    campoNombre = document.getElementById(IdElementoNombre).value;
    campoUsuario = document.getElementById(IdElementoUsr).value;
    campoPasword = document.getElementById(IdElementoPwd).value;
    
    if(idfila == 1){
        iTipoMail = 1; // Mail para autoevaluado
    }else{ 
            iTipoMail=2; // Mail para resto
        }

    if (campoNombre.trim() == "") {
        // Mostrar avisos de que el nombre tener valor
        alert("El nombre y apellidos no puede estar vacío.");
    } else {
        if (emailValido(campoEmail)) {
            //Si es un email válido hay que llamar a la función componer correo()
            componerCorreo(campoEmail, campoNombre, campoUsuario, campoPasword, iTipoMail);
        } else {
            alert("La dirección de email es incorrecta.");
            document.getElementById(IdElementoEmail).focus();
        }
    }

}


// FUNCION PARA COMPONER EL CORREO ELECTRONICO CON LOS PARAMETOS INDICADOS
function componerCorreo(paramEmail, paramNombre, paramUsuario, paramPasword, iTipoMail) {

    sSaltoLinea = "%0A"
    sTextoMail = ""
    sFirmaMail = ""
    sFechaLimite = document.getElementById('inputDateHasta').value;
    sFechaLimite = sFechaLimite.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');

    
	if(iTipoMail!=1){
		sTextoAsunto = "Feedback 360° de " + paramNombre + "."
		sTextoMail ="Estimado/a participante del feedback 360°:"
		sTextoMail += sSaltoLinea + sSaltoLinea + "Bienvenido/a al Feedback 360° de " + paramNombre 
	}else{
			sTextoAsunto = "Autoevaluación de su Feedback 360"
			sTextoMail ="Estimado/a " + paramNombre + ":"
			sTextoMail += sSaltoLinea + sSaltoLinea + "Bienvenido/a a la Autoevaluación de su Feedback 360°."
	}
	
    sTextoMail += sSaltoLinea + "Para acceder al cuestionario de evaluación deberá seguir los siguientes pasos:"
	sTextoMail += sSaltoLinea + sSaltoLinea + "1. Ir a la página:"
	sTextoMail += sSaltoLinea + "     www.marianovilallonga.com"
	sTextoMail += sSaltoLinea + sSaltoLinea + "2. Clicar Banner: Acceso al Feedback 360°."
	sTextoMail += sSaltoLinea + sSaltoLinea + "3. Introducir sus claves en la web:"
	sTextoMail += sSaltoLinea + sSaltoLinea + "Sus claves de acceso son:"
	sTextoMail += sSaltoLinea + "     Usuario: " + paramUsuario
	sTextoMail += sSaltoLinea + "     Contraseña: " + paramPasword
	sTextoMail += sSaltoLinea + sSaltoLinea + "Una vez haya accedido al cuestionario, por favor, responda a las preguntas con total sinceridad, recuerde que para poder pasar de página es necesario haber respondido a todas las preguntas."
	sTextoMail += sSaltoLinea + "Si tiene dudas acerca de cómo responder alguna de las preguntas o desconoce la respuesta, valore dicha pregunta con la opción intermedia 'a veces'."	
	sTextoMail += sSaltoLinea + "Cuando haya finalizado la evaluación, esta quedará cerrada automáticamente y será imposible que nadie acceda a ella."
	sTextoMail += sSaltoLinea + sSaltoLinea + "Desde TopTenMS garantizamos la completa confidencialidad de sus valoraciones pues, en cuanto realice el ejercicio sus valoraciones pasan a formar parte de una media en el que participan numerosas personas. Lo que interesa es la visión de todo el entorno del evaluado."
	sTextoMail += sSaltoLinea + "Agradeceremos que lo realice lo antes posible."
	if(sFechaLimite!=""){sTextoMail += sSaltoLinea + "La fecha límite es el  " + sFechaLimite + " a las 24.00 h."}
	sTextoMail += sSaltoLinea + sSaltoLinea + "Muchas gracias y, si tiene alguna dificultad, agradeceremos que se ponga en contacto con nosotros."

	sFirmaMail = sSaltoLinea + "Mariano V."
	sFirmaMail += sFirmaMail + "www.marianovilallonga.com"
	sFirmaMail += sFirmaMail + "Móvil: 629 53 27 27"
	sFirmaMail += sFirmaMail + "Tf.Directo: 94 624 17 21"
	sFirmaMail += sFirmaMail + "______________________"
	sFirmaMail += sFirmaMail + "El presente mensaje y, en su caso, los ficheros que lleve adjuntos, va dirigido de manera exclusiva a su destinatario y puede contener información confidencial."
	sFirmaMail += sFirmaMail + "Si usted no es el destinatario de este mensaje (o la persona responsable de su entrega), considérese advertido de que lo ha recibido por error, así como de la prohibición legal de realizar cualquier tipo de uso, difusión, reenvío, impresión o copia del mismo.  Si ha recibido este mensaje por error, por favor notifíquelo al remitente y proceda a destruirlo inmediatamente."

	sCadenaCorreo = "mailto:" + paramEmail + "?subject=" + encodeURIComponent(sTextoAsunto) + "&body="+ sTextoMail //+ sFirmaMail
	window.location.href = sCadenaCorreo;

}

//FUNCION QUE VALIDA EL MAIL RECIBIDO SEA CORRECTO O NO. --- Devuelve True o False
function emailValido(email) {

    var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;

    if (!regex.test(email)) {
        return false;

    } else {
        return true;
    }
}


//FUNCION QUE CRA EL PARAMETRO "USUARIO"
function crearUsr(idFila) {
    
    IdElementoNombre = "inputName" + idFila;
    IdElementoUsr = "inputUsu" + idFila;
    IdElementoPwd = "inputPass" + idFila;

    campoNombre = document.getElementById(IdElementoNombre).value;
    campoEvaluacion = document.getElementById('inputEvaluacion').value;

    if (campoNombre.length > 1) {
        campoUsuario = campoNombre.toUpperCase().split(" ");

        if (campoUsuario.length > 1) {
            document.getElementById(IdElementoUsr).value = "USR" + campoUsuario[0].substr(0, 1) + campoUsuario[1].substr(0, 1) + campoEvaluacion.substr(1, 4);
        } else {
            document.getElementById(IdElementoUsr).value = "USR" + campoUsuario[0].substr(0, 2) + campoEvaluacion.substr(1, 4);
        }
        document.getElementById(IdElementoPwd).value = crearPassword(document.getElementById(IdElementoUsr).value);

    } else {
        if (campoNombre.trim() != "") {
            document.getElementById(IdElementoUsr).value = "";
            alert("Introduce un nombre y apellidos");
        }
    }

}

// FUNCION PARA CREAR CONTRASEÑA ALEATORIA
function crearPassword(Usuario) {

    var caracteres = Usuario + "abcdefghijkmnpqrtuvwxyz" + Usuario + "ABCDEFGHJKMNPQRTUVWXYZ2346789" + Usuario;
    var contraseña = "";

    for (i = 0; i < 10; i++) contraseña += caracteres.charAt(Math.floor(Math.random() * caracteres.length));

    return contraseña;
}

// FUNCION PARA LAS VALORACIONES
function cargarRespuestas() {
    // función que carga en el array de Respuestas los valores de todas las recogidas en la web.
    // Se contemplan bloques de 10 respuestas cada vez.
    var contPregunta = 1;
    var contRespuesta = 1;
    var contPreg = 10;
    
    for (contPregunta = 1; contPregunta <= contPreg; contPregunta++) {
        //console.log("VUELTAA ===>", contPregunta);
        for (contRespuesta = 1; contRespuesta <= 5; contRespuesta++) {
            //alert("respuesta " + contRespuesta + " marcada? : " + document.getElementById('radioP' + contPregunta + 'V' + contRespuesta).checked);
            //console.log("respuesta " + contRespuesta + " marcada? : " + document.getElementById('radioP' + contPregunta + 'V' + contRespuesta).checked)
            if (document.getElementById('radioP' + contPregunta + 'V' + contRespuesta).checked) {
                arrRespuestas[contPregunta] = contRespuesta;
                console.log("" + arrRespuestas[contPregunta]);                         
                
            }//cierre if
        }//cierre for contRespuesta
    }// cierre for conPregunta
}



function llevarPaginaPreg() {
    window.location.href = "preguntas.php";
}

// COMPROBAMOS SI LAS FECHAS AL CREAR LA EVALUACION SON CORRECTAS
function comprobarFechas() {
    fDesde = document.getElementById('inputDateDesde').value; // DEVUELVE -- 2020-10-01
    fHasta = document.getElementById('inputDateHasta').value;

}


function goConsultaEval() {
    window.location.href = "consultaEvaluacion.php";
}

function goConsultaPreg() {
    window.location.href = "consultaPreguntas.php";
}

function goNuevaEval() {
    window.location.href = "evaluacion.php";
}

function llevarPaginaIni() {
    cerrarSesion();    
}


// CIERRE DE SESION
function cerrarSesion() {
    var opcion = confirm ("¿Desea Salir?");
    if(opcion == true){
        window.location.href = "logout.php";    
        unset($_SESSION['usuario']);
    }

}


/*
// no vuelta atras
window.location.hash="no-back-button";
window.location.hash="Again-No-back-button";//esta linea es necesaria para chrome
window.onhashchange = function(){ window.location.hash="no-back-button"; }*/