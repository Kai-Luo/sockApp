<?php 
require("Game.php");

$juego=new Game();
echo $juego->firstLoad();
echo $juego->validate("4");


 ?>