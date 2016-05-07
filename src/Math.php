<?php 
class Game{

	private $tableLevel;
	private $exercisesLevel;
	private $i=0;
	private $l=0;
	private $conn; //de momento no se está usando.


	function connection(){

	$openTxt=fopen("pruebajson.txt", "r") or die ("no SE puede abrir");
	$txt=fread($openTxt,filesize("pruebajson.txt"));

	$this->tableLevel=json_decode($txt);

	setlevel($this->i,$this->tableLevel);
	loadExercise();


	}

	function validate(){
	    //Devuelve true si la respuesta es correcta.
	  $correct=respuestaCorrecta($this->exercisesLevel);

	  if($correct){

	    //conn.send('{"arr":[{"msg":"Ejercicio Resuelto","user":"carlos"}]}');
	    echo("La respuesta es correcta");
	        //Comprueba si es el último ejercicio del nivel
	    if(lastExercise()){
	      if(lastLevel()){ 
	        //si no hay mas niveles sale de la funcion
	        echo("No hay mas niveles");
	        return;
	      }
	      $this->i++;//variable global de la array tableLevel;
	      setLevel($this->i,$this->tableLevel);//Carga el siguiente nivel!!!FALTA funcion para saber si es último nivel.
	      $this->l=-1;//SE iguala a -1 por que luego se hace un l++
	       //conn.send('{"msg":"Nivel resuelto","user":""}');
	    }//Comprobar si es la última posición o pasar al siguiente ejercicio
	    
	    $this->l++;//variable global index de la array exercisesLevel.
	    loadExercise();//Carga el siguiente ejercicio del nivel.
	    
	  }
	  else{
	    echo("La respuesta no es correcta");

	  }
	}

	function setLevel($t,$arr) {

		for($l = 0; $l < sizeof($arr[$t]->exercise); $l++) {
	    $this->exercisesLevel=$arr[$t]->exercise;
			//echo($arr[$t]->exercise[$l]->ex);
	   }

	}
	function loadExercise(){

	  echo();


	}
	function lastExercise(){
	  $size=sizeof($this->exercisesLevel)-1;
	  $actualIndex=$this->l;
	  if($size===$actualIndex){
	    return true;
	  }
	}
	function lastLevel(){
	  $size=sizeof($this->tableLevel)-1;
	  $actualIndex=$this->i;
	  if($size===$actualIndex){
	    return true;
	  }
	}

	function respuestaCorrecta($arr){
	    foreach ($arr as $el) {
	    	$exActual="5+4";//Añadir elemento del websocket
	    	$respuesta=9;//y Aquí tmb
	    	if($el->{'ex'}==$exActual&&$el->{'res'}==$respuesta){
	    		return true;
	    	}
	    }
	    
	    
	  
	}

}
 ?>
