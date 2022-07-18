<?php 

include("index.php");
//$rDocExiste = $database->count("Mov_Encomenda_Cab", ["strNumExterno" => "459","strAbrevTpDoc" => $EncstrAbrevTpDoc,"strCodSeccao" => $strCodSeccao]);


$rDocExiste = $database->count("Mov_Encomenda_Cab", [
	"strNumExterno" => "458",
    "strAbrevTpDoc" => $EncstrAbrevTpDoc,
    "strCodSeccao" => $strCodSeccao
]);


 
/*
echo "Debug";

print_r($database->log());

echo "<br>";


print_r($rDocExiste);
*/
    

 