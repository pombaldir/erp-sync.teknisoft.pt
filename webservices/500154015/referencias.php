<?php //header('Access-Control-Allow-Origin: *');  
//header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT'); 
header('Content-type: application/json; charset=utf-8'); 
include("index.php");
use Medoo\Medoo;


$database->query("SET TEXTSIZE 2147483647;"); 
ini_set( 'mssql.textlimit' , '2147483647' );
ini_set( 'mssql.textsize' , '2147483647' );



if((isset($_GET['auth_userid']) &&  $_GET['auth_userid']=="$tokenAPI") || (isset($_POST['auth_userid']) && $_POST['auth_userid']=="$tokenAPI")) {
	 
	if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	}
	if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); }
	if(isset($_GET['token']))	{	$token=stripslashes($_GET['token']);	}


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && ($act_pst=="edit" || $act_pst=="quickedit") && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idartigo=stripslashes($_POST['idnum']);
$erp_familia=stripslashes($_POST['erp_familia']);
$strCodigo=stripslashes($_POST['strCodigo']);
$strDescricao=stripslashes($_POST['strDescricao']);
$descr=stripslashes($_POST['descr']);

if($idartigo>0){
if($act_pst=="quickedit"){
	
} else {
if(isset($_POST['bitInactivo']) &&  $_POST['bitInactivo']==1){
	$inativo=1;
} else {
	$inativo=0;	
}
$dataUpdt = $database->update("Tbl_Gce_referencias", ["strDescricaoCompl" => $descr,"bitInactivo" => $inativo], ["Id" => $idartigo]);
}


$sucesso=1;
$html_msg="Referência editada";

} else {
$sucesso=0; 	
$html_msg="Referência não editada";	
}


$output = array("success" => "$sucesso", "type" => "info", "message" => $html_msg, "log" => $database->log());
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="editFoto" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idartigo=stripslashes($_POST['idnum']);
$tipo=stripslashes($_POST['tipo']);
$imgContent=$_POST['file'];
    
if($imgContent==""){
     if(move_uploaded_file($_FILES['file_data']['tmp_name'], sys_get_temp_dir()."/".$_POST['fileId'])) {         
            $ficheiro=unpack("H*hex", file_get_contents(sys_get_temp_dir()."/".$_POST['fileId'],TRUE)); 
            $imgContent="0x".$ficheiro['hex'];	
     }
}    
  
$sucesso=0;	  
$html_msg="Foto editada";  
 
if($tipo=="mainfoto"){
	$datafile_0=base64_decode($imgContent); 
	$datafile_1= unpack("H*hex", base64_decode($imgContent)); 
	$datafile_2="0x".$datafile_1."";	 
  
  $dataUpdt = $database->update("Tbl_Gce_Referencias", 
  ["imgImagem" => Medoo::raw('CONVERT(varbinary(MAX), '.$imgContent.')')], 
  ["Id" => $idartigo]);
  
	$sucesso=$dataUpdt->rowCount();  
}
$output = array("success" => "$sucesso", "type" => "info", "message" => $html_msg, "log" => $database->log());
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="addFotobyUrl" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idartigo=stripslashes($_POST['idart']);  
$url=stripslashes($_POST['url']); 
$tipo=stripslashes($_POST['tipo']);
 
$sucesso=0;	  
$html_msg="Foto editada";  
 
if($tipo=="mainfoto"){ 
    
	$ficheiro=unpack("H*hex", file_get_contents($url,TRUE)); 
	$imgContent="0x".$ficheiro['hex'];	
  
  $dataUpdt = $database->update("Tbl_Gce_Referencias", ["imgImagem" => Medoo::raw('CONVERT(varbinary(MAX), '.$imgContent.')')], ["Id" => $idartigo]);
  
$sucesso=$dataUpdt->rowCount();  
	
}
$output = array("success" => "$sucesso", "type" => "info", "message" => $html_msg, "log" => $database->log(), "url" => $url);
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="delFoto" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idAtt=stripslashes($_POST['idAtt']);
$tipo=stripslashes($_POST['tipo']);	
$idartigo=stripslashes($_POST['idartigo']);
$sucesso=1;	 
$html_msg="Foto removida";  
 
if($tipo=="mainfotoRef"){  
	$dataUpdt = $database->update("Tbl_Gce_Referencias", ["imgImagem" => Medoo::raw("NULL")], ["Id" => $idartigo]);
	$sucesso=$dataUpdt->rowCount();
	$html_msg="Foto principal removida";  
}

$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg");
}    
    
       
    
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="grelhasCab" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
		
	$list = $database->select("Tbl_Gce_Grelhas_Cab", [
	"strCodigo",
	"strDescricao",
	"Id"
	],[
		"ORDER" => ["strDescricao"=>"ASC"]
	]);	
	
	$output=$list;
}	
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="view" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
		
	$num=stripslashes($_GET['num']);
	$Tbl_Gce_Artigos = array();
	$Tbl_Gce_ArtigosPrecos = array();
	$Gce_stk_real = array();
	$Tbl_Gce_ArtigosFamilias = array();
	$Tbl_Gce_ArtigosIdiomas = array();
	
    
	$Tbl_Gce_Artigos = $database->select("Tbl_Gce_Referencias", [
	"strCodigo",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"bitInactivo",
	"imgImagem"
	], [
		"Tbl_Gce_Referencias.Id" => $num,
		"LIMIT" => 1
	]);
	
	
    
	# # FAMILIA
	$Tbl_Gce_ArtigosFamilias = $database->select("Tbl_Gce_ReferenciasFamilias", [
	"strCodFamilia",
	"strCodTpNivel"
	], [
		"strcodReferencia" => $Tbl_Gce_Artigos[0]['strCodigo']
	]);		
		
	# # IDIOMAS
	$Tbl_Gce_ArtigosIdiomas = $database->select("Tbl_Gce_ReferenciasIdiomas", [
	"strDescricao",
	"strDescricaoCompl",
	"strExcerto",
	"strCodIdioma"
	], [
		"strcodReferencia" => $Tbl_Gce_Artigos[0]['strCodigo']
	]);	
    //print_r($database->error()); 
    

 	$output= array("strCodigo"=>$Tbl_Gce_Artigos[0]['strCodigo'],"strDescricao"=>htmlentities($Tbl_Gce_Artigos[0]['strDescricao']),"strDescricaoCompl"=>$Tbl_Gce_Artigos[0]['strDescricaoCompl'],"bitInactivo"=>$Tbl_Gce_Artigos[0]['bitInactivo'],"strObs"=>$Tbl_Gce_Artigos[0]['strObs'],"familias"=>$Tbl_Gce_ArtigosFamilias,"idiomas"=>$Tbl_Gce_ArtigosIdiomas,"imgImagem"=>base64_encode($Tbl_Gce_Artigos[0]['imgImagem']));
	 
}
        
    
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="list" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Referencias"; 
    /*
    * Columns
    */ 
	$aColumns = array('Id','strCodReferencia','strDescricaoReferencia', 'IdRef', 'strCodArtigo', 'strDescricao','strCodGrelhaHorLin','strCodGrelhaVerLin','QuantStock','bitPortalWeb','bitPortalWebRef', 'strCodCategoria', 'imgImagem');  
    $sColumns = array('Id','strCodReferencia','Tbl_Gce_Referencias.strDescricao',  'Tbl_Gce_Referencias.Id',  'Tbl_Gce_ArtigosReferencias.strCodArtigo', 'Tbl_Gce_Referencias.strDescricao','strCodGrelhaHorLin','strCodGrelhaVerLin','QuantStock','Tbl_Gce_Artigos.bitPortalWeb', 'Tbl_Gce_Referencias.bitPortalWeb', 'strCodCategoria', 'imgImagem');  
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */     
    /*
     * Ordering 
    */
    $sOrder = "ORDER BY strCodArtigo ASC";
    if ( isset( $_GET['iSortCol_0'] ) )
    {
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                $sOrder .= $sColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".addslashes( $_GET['sSortDir_'.$i] ) .", ";
            }
        }
         
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" )
        {
            $sOrder = "";
        }
    }
     
	 
	 
    $sWhere = "WHERE Tbl_Gce_Artigos.bitInactivo=0";
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
        $sWhere .= "AND (";
        for ( $i=0 ; $i<count($sColumns) ; $i++ )
        {
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true")
        	{
            $sWhere .= "".$sColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";		
			}
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }	
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";  
            }
           $sWhere .= " ".$sColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
    }
     			
	
	
	if(isset($_GET['Familia']) && $_GET['Familia']!=""){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_ArtigosFamilias.strCodFamilia='".$_GET['Familia']."'";  
        $nivel=$_GET['nivFam'];
	} 
	
	if(isset($_GET['Marca']) && $_GET['Marca']!=""){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_ArtigosFamilias.strCodFamilia='".$_GET['Marca']."'"; 
        $nivel=$_GET['nivMarca'];
	}
	
	if(isset($_GET['bitDispWeb']) && $_GET['bitDispWeb']!="" && $_GET['bitDispWeb']!="0"){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_Artigos.bitPortalWeb='".$_GET['bitDispWeb']."'";  
	}
	 
    $queryJoin="LEFT JOIN Tbl_Gce_Referencias ON Tbl_Gce_ArtigosReferencias.strCodReferencia = Tbl_Gce_Referencias.strCodigo 
                INNER JOIN Tbl_Gce_Artigos ON Tbl_Gce_ArtigosReferencias.strCodArtigo = Tbl_Gce_Artigos.strCodigo 
                LEFT JOIN Gce_stk_real ON Tbl_Gce_Artigos.strCodigo = Gce_stk_real.strCodArtigo";
    if((isset($_GET['Marca']) && $_GET['Marca']!="") || (isset($_GET['Familia']) && $_GET['Familia']!="") ){
                $queryJoin.=" INNER JOIN Tbl_Gce_ArtigosFamilias ON Tbl_Gce_Artigos.strCodigo=Tbl_Gce_ArtigosFamilias.strCodArtigo and Tbl_Gce_ArtigosFamilias.strCodTpNivel='".$nivel."'";
    }
    
    $queryRef="SELECT Tbl_Gce_Artigos.Id, Tbl_Gce_Artigos.bitPortalWeb, Tbl_Gce_Referencias.bitPortalWeb as bitPortalWebRef, Tbl_Gce_Artigos.strCodCategoria,Tbl_Gce_Artigos.strDescricao,CASE WHEN Tbl_Gce_Artigos.imgImagem IS NULL THEN 0 ELSE 1 END as imgImagem, Tbl_Gce_ArtigosReferencias.strCodReferencia,Tbl_Gce_Referencias.strDescricao as strDescricaoReferencia,Tbl_Gce_Referencias.Id as IdRef, Tbl_Gce_ArtigosReferencias.strCodArtigo, 
                      Tbl_Gce_ArtigosReferencias.strCodGrelhaVerLin, Tbl_Gce_ArtigosReferencias.strCodGrelhaVer, Tbl_Gce_ArtigosReferencias.strCodGrelhaHorLin, 
                      Tbl_Gce_ArtigosReferencias.strCodGrelhaHor, Tbl_Gce_ArtigosReferencias.strCodGrelhaVerLin,Tbl_Gce_ArtigosReferencias.strCodGrelhaHorLin,
                      CAST(QuantStock AS INT) AS QuantStock
                      FROM Tbl_Gce_ArtigosReferencias"; 
    
	$sQuery = $database->query("".$queryRef." ".$queryJoin." ".$sWhere."".$sOrder." OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY")->fetchAll();
    

    
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
 
	
	$data = $database->query("SELECT COUNT(Tbl_Gce_Artigos.Id) as total FROM Tbl_Gce_ArtigosReferencias ".$queryJoin." ".$sWhere." ")->fetchAll();
	
	$iTotal=$data[0]['total'];


    //	die(var_dump( $database->error() ));
    //	die(var_dump($database->log()));

       
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iTotal,
        "aaData" => array()
    );
      

	foreach($sQuery as $aRow)
	{	
        $row = array();
		// Add the row ID and class to the object
		$row['DT_RowId'] = 'row_'.$aRow['Id'];
		//$row['DT_RowClass'] = 'grade'.$aRow['strCodigo'];
		
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {           
             if ( $aColumns[$i] == 'strCodGrelhaHorLin' )
             {
                $queryGH=$database->select("Tbl_Gce_Grelhas_Lin",["strDescricao"], ["strCodGrelha" => $aRow['strCodGrelhaHor'],"strCodigo" => $aRow[ $aColumns[$i]]]);  
                $row[] = $queryGH[0]['strDescricao']; 
             }
             else if ( $aColumns[$i] == 'strCodGrelhaVerLin' )
             {
                $queryGV=$database->select("Tbl_Gce_Grelhas_Lin",["strDescricao"], ["strCodGrelha" => $aRow['strCodGrelhaVer'],"strCodigo" => $aRow[ $aColumns[$i]]]);  
                $row[] = $queryGV[0]['strDescricao']; 
             }
            
             else if ( $aColumns[$i] != ' ' )
            {
                /* General output */
                $row[] = $aRow[ $aColumns[$i]];
            }
        }
        $output['aaData'][] = $row;
    }
   }  
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
   if(isset($output)){
    echo json_encode($output );
   }
}
