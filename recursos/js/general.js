/*
 * f_comunes.js
 *
 */


function set_title_attr(objeto) {
	
    var preArroba = "jequeto";
    var postArroba = "gmail.com";
    objeto.title = preArroba + String.fromCharCode(4*16)+postArroba + "  Jesús Mª de Quevedo, profesor de informática del IES Palomeras-Vallecas de Madrid";
	
}






/**
 * Envía una petición por post para ocultar parámetros a usuario y evitar que juegue con
 * ellos modificando la URI mostrada .
 * 
 * @param string action
 * @param strin id
 * @returns {undefined}
 */
function submit_post_request_form(action, id) {
	$("#post_request_form").attr("action",action);
	$("#id").attr("value", id);
	$("#post_request_form").submit();
	// alert("post_request_form.submit("+$("#post_request_form").attr("action")+" , "+$("#id").val()+")");
}

