
window.onload=connection;

///VARIABLES GLOBALES////
var tableLevel; //Array con todos los niveles.
var exercisesLevel; // Array con todos los ejercicios de un nivel.
var i=0;
var l=0;

//Conexión con el fichero json!
function connection(){

  var xhttp= new XMLHttpRequest();
  var url="pruebajson.txt";	
  xhttp.open('POST',url,true);
  xhttp.onreadystatechange=function(){
  if(xhttp.readyState == 4 && xhttp.status == 200){
  			tableLevel=JSON.parse(xhttp.responseText);

  			setLevel(i,tableLevel);
        loadExercise(); 		
  }
}

xhttp.send();
}


//Inicia el array exercisesLevel y lo actualiza.
function setLevel(i,arr) {

	for(var l = 0; l < arr[i].exercise.length; l++) {
    exercisesLevel=arr[i].exercise;
		console.log(arr[i].exercise[l].ex);
   }

}

//Esta función valida la respuesta,imprime el siguiente ejercicio y si es el ultimo, carga el siguiente nivel.
function validate(){
    //Devuelve true si la respuesta es correcta.
  var correct=exercisesLevel.some(respuestaCorrecta);

  if(correct){
        //Comprueba si es el último ejercicio del nivel
    if(lastExercise()){
      if(lastLevel()){ 
        //si no hay mas niveles sale de la funcion
        console.log("No hay mas niveles");
        return;
      }
      i++;//variable global de la array tableLevel;
      setLevel(i,tableLevel);//Carga el siguiente nivel!!!FALTA funcion para saber si es último nivel.
      l=-1;//SE iguala a -1 por que luego se hace un l++
    }//Comprobar si es la última posición o pasar al siguiente ejercicio

    console.log("La respuesta es correcta");
    l++;//variable global index de la array exercisesLevel.
    loadExercise();//Carga el siguiente ejercicio del nivel.
    
  }
  else{
    console.log("La respuesta no es correcta");

  }
}
///valida las respuestas
function respuestaCorrecta(el,index,arr){
    var respuesta=document.getElementById("respuesta").value;
    var exActual=document.getElementById("ex").innerHTML;
    return (el.ex==exActual&&el.res==respuesta);
      
    
}
//Devuelve true si es el último ejercicio del array.
function lastExercise(){
  var size=exercisesLevel.length-1;
  var actualIndex=l;
  if(size===actualIndex){
    return true;
  }
} 
//Devuelve true si es el ultimo nivel. 
function lastLevel(){
  var size=tableLevel.length-1;
  var actualIndex=i;
  if(size===actualIndex){
    return true;
}

}
//Carga el siguiente ejercicio en el div ex-0.
function loadExercise(){

  document.getElementById("ex").innerHTML=exercisesLevel[l].ex;


}

