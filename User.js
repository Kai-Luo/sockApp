window.onload=connection;

var conn;

function connection(){

	conn = new WebSocket('ws://localhost:8080');
	conn.onopen = function(e) {
	    console.log("WebSocketConnection established!");
	};

	/*
	Tienes que configurar la respuesta del servidor, para que las
	respuestas sean como las establecidas en el selection().
	*/

	conn.onmessage = function(e) {
	   decode(e.data);
	   //var msg=JSON.parse(e.data);
	   //console.log(msg.answer);
	};
}

function decode(data){
	var msg=JSON.parse(data);
	selection(msg);
	
}

function selection(msg){

	switch(msg.id){
		case "response":
			loadResponse(msg);
		break;
		case "newlevel":
			setLevel(msg);
		break;
		default:
			error();
	}
}

function loadResponse(msg){

	if(msg.subId==="incorrecta"){
		incorrecta();
	}
	else if(msg.subId==="correcta"){

		loadExercise(msg.ex);
	}
}

function incorrecta(){
	///imprimir respuesta incorrecta en algun div
}
function loadExercise(ex){
	
	document.getElementById("ex").innerHTML=ex;

}
function answer(){

	var respuesta=document.getElementById("respuesta").value;
	conn.send('{"answer":'+respuesta+'}');
}