<meta charset="UTF-8">
<?php
include("prevod.class.php");
$prevedi      =   "Hello";
$prevod       = new Prevod("en", "sr");

echo $prevod->Prevod($prevedi); //Cirilica
echo '<br/>';
echo $prevod->CirLat($prevod->Prevod($prevedi)); //Latinica

?>
