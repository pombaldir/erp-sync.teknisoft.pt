<?php header('Content-Type: application/json');
$ds          = DIRECTORY_SEPARATOR;  //1
 
$storeFolder = '/Users/nelsonsantos/Sites/doc.organifacho.com/node_modules/gentelella/attachments';   //2
 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3             
      
    //$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
	$targetPath = $storeFolder . $ds;  //4
     
	 
	$fname=$_FILES['file']['name']; 
    $targetFile =  $targetPath. $fname;  //5
 
    move_uploaded_file($tempFile,$targetFile); //6
	
	
	$ficheiroCompl=$storeFolder. $ds . $fname;
	########
	
	//exec("java -jar ../pdfbox-app-2.0.7.jar ExtractImages -directJPEG $ficheiroCompl", $retorno);
	exec("java -jar ../pdfbox-app-2.0.7.jar PDFToImage -dpi 600 -color gray -imageType jpg $ficheiroCompl", $retorno);
	
	//$fout=str_replace(".pdf","1.jpg",$ficheiroCompl);
	//$fout=str_replace("../$fout","",$fout);
	//$fout=$storeFolder . $ds .$fout;
	
	//chdir($storeFolder); 
	 
	 $path = getenv('PATH');
	 putenv("PATH=$path:/usr/local/bin");
	 
	//exec("tesseract Homologacao1.jpeg output");  
	 
}


$output = array("mensagem" => "1", "htmlmsg" => "  ");

echo json_encode($output);
?>     