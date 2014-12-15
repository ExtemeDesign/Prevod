Prevod
======

Php prevod sa engleskog na srpski

Simple usage


<?php
$prevedi      =   "Hello";
$prevod       = new Prevod("en", "sr");

echo $prevod->Prevod($prevedi); //Cirilica
echo '<br/>';
echo $prevod->CirLat($prevod->Prevod($prevedi)); //Latinica

?>
