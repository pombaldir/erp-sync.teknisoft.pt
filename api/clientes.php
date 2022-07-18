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
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$data = json_decode(file_get_contents('php://input'), true);     
$Id=$data['Id'];
$intCodigo=$data['intCodigo'];      
   
$queryCliente = $database->select("Tbl_Clientes", ["Id"], ["intCodigo" => $intCodigo]);	      
     
if(is_array($queryCliente) && sizeof($queryCliente)>0){
    die(json_encode(array("success"=>0,"errormsg"=>"Código existente")));  
}
else if(!isset($data['intCodigo']) || $intCodigo==0 || $intCodigo==""){
    die(json_encode(array("success"=>0,"errormsg"=>"Código inválido")));  
}    
else {
    $database->insert("Tbl_Clientes", [
        "intCodigo" => $intCodigo,
        "strNome" =>$data['strNome'],
        "strNumContrib" =>$data['strNumContrib'],
        "bitConsumidorFinal" =>$data['bitConsumidorFinal'],
        "strTelefone" =>$data['strTelefone'],
        "strTelemovel" =>$data['strTelemovel'],
        "strEmail" =>$data['strEmail'],
        "strMorada_lin1" =>$data['strMorada_lin1'],
        "strMorada_lin2" =>$data['strMorada_lin2'],
        "strLocalidade" =>$data['strLocalidade'],
        "strPostal" =>$data['strPostal'],
        "strAbrevSubZona" =>$data['strAbrevSubZona'],
        "bitUseElectronicDocument" =>$data['bitUseElectronicDocument'],
        "strCodCondPag" =>$data['strCodCondPag'],
        "bitPortalWeb" =>$data['bitPortalWeb'],
        "intSinalTp" =>$data['intSinalTp'],
        "intCodCatEntidade" =>$data['intCodCatEntidade'],
        "bitAviso_vencimento" =>$data['bitAviso_vencimento'],
        "intCodVendedor" =>$data['intCodVendedor'],
        "dtmAbertura" =>date('Y-m-d H:i:s'),
        "dtmAlteracao" =>date('Y-m-d H:i:s')
    ]);
    $idRegisto = $database->id();
    
    if($idRegisto>0){    
    die(json_encode(array("success"=>1,"message"=>"Cliente criado","Id"=>$idRegisto)));   
    } else {
    die(json_encode(array("success"=>0,"errormsg"=>$database->error())));       
    }

} 
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="getDetail" && isset($_GET['num']) && $_SERVER['REQUEST_METHOD'] === 'GET'){
$numArtigo=$_GET['num'];
 $queryCliente = $database->select("Tbl_Clientes", [         
  "Id",
  "intCodigo",
  "strNome",
  "strNumContrib",
  "bitConsumidorFinal",
  "strTelefone",
  "strTelemovel",
  "strEmail",
  "strMorada_lin1",
  "strMorada_lin2",
  "strLocalidade",
  "strPostal",
  "strAbrevSubZona",   
  "bitUseElectronicDocument",   
  "strCodCondPag",   
  "bitPortalWeb",   
  "intSinalTp",   
  "intCodCatEntidade",   
  "bitAviso_vencimento",  
  "intCodVendedor",   
  "strObs",   
  "dtmAbertura",   
  "dtmAlteracao"   
 ], [
    "Id" => $numArtigo]);	 
 
if(sizeof($queryCliente)==0) {  
die(json_encode(array("success"=>0,"errormsg"=>"registo inexistente")));
    
} else {        
    $output =$queryCliente;
}
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    
    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    $sTable = "Tbl_Clientes"; 
    /*
    * Columns
    */ 
	$aColumns = array("Id","intCodigo", "strNome", "strNumContrib", "bitConsumidorFinal", "strTelefone", "strTelemovel", "strEmail", "strMorada_lin1", "strMorada_lin2", "strLocalidade","strPostal","strAbrevSubZona","bitUseElectronicDocument","strCodCondPag","bitPortalWeb","intSinalTp","intCodCatEntidade","bitAviso_vencimento","intCodVendedor","strObs","dtmAbertura","dtmAlteracao");    
    
    
        
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
        $sWhere .= "WHERE (";
        $sWhere .= "".$_GET['searchField']." = '".addslashes( $_GET['q'] )."'";	
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
