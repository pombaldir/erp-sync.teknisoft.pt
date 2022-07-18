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


/* DB table to use */ 
$sTable = "Tbl_Gce_Referencias";   
/*
* Columns
*/  
$aColumns = array("Id","strCodigo", "strAbreviatura", "strCodCategoria", "strTpArtigo", "strDescricao", "strDescricaoCompl", "intTpComposicao", "bitInactivo", "intCodTaxaIvaVenda", "intCodTaxaIvaCompra", "dtmAbertura", "dtmAlteracao", "strAbrevMedStk", "strAbrevMedVnd", "bitNaoMovStk", "strObs", "strCodGrelhaHor", "strCodGrelhaVer","intTpPreco","bitCustoPadrao","intNumSerie","bitNaoExpAutoVnd");  


$aLinhas=array("Tbl_Gce_ArtigosReferencias.Id", "Tbl_Gce_Artigos.Id(IdArtigo)", "strCodArtigo", "strDescricao", "strCodGrelhaHorLin", "strCodGrelhaVerLin");

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$data = json_decode(file_get_contents('php://input'), true);     
$Id=$data['Id'];
 
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="getDetail" && isset($_GET['num']) && $_SERVER['REQUEST_METHOD'] === 'GET'){

    
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    
    $sIndexColumn = "Id";        
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
	
	$sQuery = $database->select($sTable, $aColumns,Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".($offset*$limit)." ROWS FETCH NEXT ".$limit." ROWS ONLY"));
    
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
            
             if ( $aColumns[$i] == 'bitCustoPadrao')
            {
                $queryLinha = $database->select("Tbl_Gce_ArtigosReferencias",[ 
                    "[>]Tbl_Gce_Artigos" => ["strCodArtigo" => "strCodigo"]], $aLinhas, [
                    "strCodReferencia" => $aRow['strCodigo'],
                    "ORDER" => ["strCodArtigo"=>"ASC"]
                ]);	                 
                $row['artigos'] = $queryLinha;
                foreach($queryLinha  as $kartgo=>$vartgo){
                    $row['artigos'][$kartgo]['stock']=getStockByCod($vartgo['strCodArtigo'],1);
                }
                 
            }   
             else if ( $aColumns[$i] == 'intNumSerie')
            {
                $queryFam = $database->select("Tbl_Gce_ReferenciasFamilias", [
                "strCodFamilia",
                "strCodTpNivel",   
                ], [
                    "strCodReferencia" => $aRow['strCodigo'],
                    "ORDER" => ["strCodTpNivel"=>"ASC"]
                ]);	                 
                $row['familias'] = $queryFam;
            }
            
            else if ( $aColumns[$i] == 'bitNaoExpAutoVnd')
            {
                $queryPrecos = $database->select("Tbl_Gce_ReferenciasPrecos", [
                "intNumero",
                "fltPreco",  
                "intTpIVA",     
                ], [
                    "strCodReferencia" => $aRow['strCodigo'],
                    "ORDER" => ["intNumero"=>"ASC"]
                ]);	                 
                $row['precos'] = $queryPrecos;
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
