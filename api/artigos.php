<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");
use Medoo\Medoo;
//print_r($_GET);

if($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['act']))	{	$accao="list";	}
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['act']))	    {	$accao=$_GET['act'];	}

$limit=isset($_GET['limit']) && $_GET['limit']!="" ? $_GET['limit'] : 100;
$offset=isset($_GET['offset']) && $_GET['offset']!="" ? $_GET['offset'] : 0;




# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
$data = json_decode(file_get_contents('php://input'), true);
$idRegisto=$_GET['num'];
$strCodigo=$data['strCodigo']; 
    

$tblUpdate = $database->update("Tbl_Gce_Artigos", [
        //"strCodigo" => $strCodigo,
        "intCodInterno" =>$data['intCodInterno'],
        "strCodBarras" =>$data['strCodBarras'],
        "strAbreviatura" =>$data['strAbreviatura'],
        "strCodCategoria" =>$data['strCodCategoria'],
        "strTpArtigo" =>$data['strTpArtigo'],
        //"strDescricao" =>$data['strDescricao'],
        "fltPCReferencia" =>$data['fltPCReferencia'],
        "intCodTaxaIvaVenda" =>$data['intCodTaxaIvaVenda'],
        "intCodTaxaIvaCompra" =>$data['intCodTaxaIvaCompra'],
        "strAbrevMedStk" =>$data['strAbrevMedStk'],
        "strAbrevMedVnd" =>$data['strAbrevMedVnd'],
        "strAbrevMedCmp" =>$data['strAbrevMedCmp'],
        "bitNaoMovStk" =>$data['bitNaoMovStk']
    ], [
	"Id" => $idRegisto]);  
$errorBD=$database->error();   
    
if($tblUpdate->rowCount()==1){
    die(json_encode(array("success"=>1,"message"=>"Artigo editado","Id"=>$idRegisto)));        
}
else if($errorBD[0]!="00000"){
    die(json_encode(array("success"=>0,"errormsg"=>$errorBD[2]))); 
} else {
    die(json_encode(array("success"=>0,"errormsg"=>"Artigo não encontrado")));  
}
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$data = json_decode(file_get_contents('php://input'), true);
$Id=$data['Id'];
$strCodigo=$data['strCodigo'];      
    
   // die(var_dump($data['precos']));
    
$queryArtigo = $database->select("Tbl_Gce_Artigos", ["Id"], ["strCodigo" => $strCodigo]);	      
    
if(is_array($queryArtigo) && sizeof($queryArtigo)>0){
    die(json_encode(array("success"=>0,"errormsg"=>"Artigo existente")));  
}
else {
    $database->insert("Tbl_Gce_Artigos", [
        "strCodigo" => $strCodigo,
        "intCodInterno" =>$data['intCodInterno'],
        "strCodBarras" =>$data['strCodBarras'],
        "strAbreviatura" =>$data['strAbreviatura'],
        "strCodCategoria" =>$data['strCodCategoria'],
        "strTpArtigo" =>$data['strTpArtigo'],
        "strDescricao" =>$data['strDescricao'],
        "fltPCReferencia" =>$data['fltPCReferencia'],
        "intCodTaxaIvaVenda" =>$data['intCodTaxaIvaVenda'],
        "intCodTaxaIvaCompra" =>$data['intCodTaxaIvaCompra'],
        "strAbrevMedStk" =>$data['strAbrevMedStk'],
        "strAbrevMedVnd" =>$data['strAbrevMedVnd'],
        "strAbrevMedCmp" =>$data['strAbrevMedCmp'],
        "bitNaoMovStk" =>$data['bitNaoMovStk']
    ]);
    
    $idRegisto = $database->id();
    if($idRegisto>0){

    if(is_array($data['precos'])){    
        foreach($data['precos'] as $preco){
            $database->insert("Tbl_Gce_Artigosprecos", [ "strCodArtigo" => $strCodigo, "intNumero" => $preco['intNumero'],"fltPreco" =>$preco['fltPreco'],"intTpIVA" => $preco['intTpIVA'],"strAbrevMoeda" => "EUR","dtMData" => date('Y-m-d H:i:s')        
        ]);	 
        }}

        if(is_array($data['familias'])){    
        foreach($data['familias'] as $familia){
            $database->insert("Tbl_Gce_ArtigosFamilias", [ "strCodArtigo" => $strCodigo, "strCodFamilia" => $familia['strCodFamilia'],"strCodTpNivel" =>$familia['strCodTpNivel']   
        ]);	 
        }}

        die(json_encode(array("success"=>1,"message"=>"Artigo criado","Id"=>$idRegisto)));   
    } else {
        die(json_encode(array("success"=>0,"errormsg"=>$database->error())));      
        }
} 
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="getDetail" && isset($_GET['num']) && $_SERVER['REQUEST_METHOD'] === 'GET'){
$numArtigo=$_GET['num'];
    
 $queryArtigo = $database->select("Tbl_Gce_Artigos", [         
  "Id",
  "strCodigo",
  "intCodInterno",
  "strCodBarras",
  "strAbreviatura",
  "strCodCategoria",
  "strTpArtigo",
  "strDescricao",
  "fltPCReferencia",
  "intCodTaxaIvaVenda",
  "intCodTaxaIvaCompra",
  "strAbrevMedStk",
  "strAbrevMedVnd", 
  "strAbrevMedCmp" ,
  "bitNaoMovStk",
 ], [
    "Id" => $numArtigo]);	     

if(sizeof($queryArtigo)==0) {  
die(json_encode(array("success"=>0,"errormsg"=>"registo inexistente")));
    
} else {         
$queryPrecos=$database->select("Tbl_Gce_Artigosprecos",["intNumero","fltPreco","intTpIVA"], ["strCodArtigo" =>  $queryArtigo[0]['strCodigo']]);  
$queryFam=$database->select("Tbl_Gce_ArtigosFamilias",["strCodFamilia","strCodTpNivel"], ["strCodArtigo" =>  $queryArtigo[0]['strCodigo']]);  
if($queryArtigo[0]['bitNaoMovStk']==1){ $stocks=array(); }   else  {  
$stocks=$database->select("Tbl_Gce_ArtigosArmLocalLote",["strCodArmazem","fltStockQtd","fltStockReservado"], ["strCodArtigo" =>  $queryArtigo[0]['strCodigo']]); 
}
    $output = array_merge($queryArtigo[0],array("precos"=>$queryPrecos),array("familias"=>$queryFam),array("stocks"=>$stocks)); 
}
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    
    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Artigos"; 
    /*
    * Columns
    */ 
	$aColumns = array("Id","strCodigo", "intCodInterno", "strCodBarras", "strAbreviatura", "strCodCategoria", "strTpArtigo", "strDescricao", "fltPCReferencia", "intCodTaxaIvaVenda", "intCodTaxaIvaCompra","strAbrevMedStk","bitNaoMovStk","bitEVSujeito","bitCusto","bitProveito");    
    if(isset($CA_Artigos)){
        $aColumns=array_merge($aColumns,$CA_Artigos); 
    } 
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */     
    /*
     * Ordering
    */
    $sOrder = "";
    if ( isset( $_GET['iSortCol_0'] ) )
    {
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".addslashes( $_GET['sSortDir_'.$i] ) .", ";
            }
        }
         
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" )
        {
            $sOrder = "";
        }
    } else {
        $sOrder = "ORDER BY $sIndexColumn DESC"; 
    }   
     
	
	
    $sWhere = "";
    if ( isset($_GET['q']) && $_GET['q'] != "" && in_array($_GET['searchField'],$aColumns))
    {
        $termo=addslashes( $_GET['q']);
        if ( isset($_GET['srchType']) && $_GET['srchType'] != ""){
            switch ($_GET['srchType']) {
                case "equal":
                    $qsrchType=" = '".$termo."'";
                    break;
                case "not-equal":
                    $qsrchType=" <> '".$termo."'";
                    break;
                case "endswith":
                    $qsrchType=" LIKE '%".$termo."'";
                case "startswith":
                    $qsrchType=" LIKE '".$termo."%'";                
                break;
                default:
                    $qsrchType=" = '".$termo."'";
            }        
        } else {
                $qsrchType=" = '".$termo."'";
        }
        
        $sWhere .= "WHERE (";
        $sWhere .= "".$_GET['searchField']." $qsrchType";	
        $sWhere .= ') ';
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
           $sWhere .= " ".$aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
    }

	
	$sQuery = $database->select($sTable, $aColumns,
	Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".($offset*$limit)." ROWS FETCH NEXT ".$limit." ROWS ONLY"));
	
    //var_dump($database->log());	
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
  
    $iTotal = $database->count("$sTable", ["Id"], Medoo::raw("".$sWhere.""));	
	       
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iTotal,
        "aaData" => array()
    ); 
     

	foreach($sQuery as $aRow)
	{	
        $row = array();
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            
             if ( $aColumns[$i] == 'bitEVSujeito')
            {
                $queryPreco = $database->select("Tbl_Gce_Artigosprecos", [
                "intNumero",
                "fltPreco",   
                "intTpIVA",      
                ], [
                    "strCodArtigo" => $aRow[ $aColumns[1]],
                    "ORDER" => ["intNumero"=>"ASC"]
                ]);	                 
                $row['precos'] = $queryPreco[0];
            }
             else if ( $aColumns[$i] == 'bitCusto')
            {
                $queryFam = $database->select("Tbl_Gce_ArtigosFamilias", [
                "strCodFamilia",
                "strCodTpNivel",   
                ], [
                    "strCodArtigo" => $aRow[ $aColumns[1]],
                    "ORDER" => ["strCodTpNivel"=>"ASC"]
                ]);	                 
                $row['familias'] = $queryFam[0];
            }
             else if ( $aColumns[$i] == 'bitProveito')
            {   if($aRow['bitNaoMovStk']==1){ $row['stocks']=array(); }   else  {           
                $row['stocks']=$database->select("Tbl_Gce_ArtigosArmLocalLote",["strCodArmazem","fltStockQtd","fltStockReservado"], ["strCodArtigo" =>  $aRow[ $aColumns[1]]]);  
            }
            }
             else if ( $aColumns[$i] != ' ' )
            {
                $row[$aColumns[$i]] = $aRow[ $aColumns[$i]];
            }
        }
        $output['aaData'][] = $row;
    }
   }   
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($output)){
    echo json_encode($output );
}
