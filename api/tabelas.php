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

$qParameters=array();

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    
    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    
    if($_GET['tipo']=="zonas"){
        $sTable = "Tbl_Zonas"; 
        $aColumns = array("Id","strAbreviatura", "strDescricao", "strAbrevPais");    
    }  
    else if($_GET['tipo']=="subzonas"){
        $sTable = "Tbl_SubZonas"; 
        $aColumns = array("Id","strAbreviatura", "strDescricao", "strAbrevPais", "strAbrevZona");    
    } 
    else if($_GET['tipo']=="taxasiva"){
        $sTable = "Tbl_Taxas_Iva"; 
        $aColumns = array("Id","intCodigo", "strDescricao", "fltTaxa");    
    }          
    else if($_GET['tipo']=="cat-entidades"){
        $sTable = "Tbl_Categoria_Entidade"; 
        $aColumns = array("Id","intCodigo", "strDescricao");    
    }      
    else if($_GET['tipo']=="numeradores"){
        $sTable = "Tbl_Numeradores"; 
        $aColumns = array("Id", "strAbrevTpDoc", "strCodSeccao", "strCodExercicio","intNum_Mes00"); 
        $qParameters=array($aColumns[1],$aColumns[2],$aColumns[3]);
    }    
    
    else if($_GET['tipo']=="familias"){
        $sTable = "Tbl_Gce_Familias"; 
        $aColumns = array("Id", "strCodigo", "strDescricao", "strCodTpFamilia"); 
    }  
    else if($_GET['tipo']=="tipofamilias"){
        $sTable = "Tbl_Gce_Tipos_Familias"; 
        $aColumns = array("Id", "strCodigo", "strDescricao", "intNivel"); 
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
     

     if (isset($_GET['order']) && $_GET['order'] != "" && !isset( $_GET['iSortCol_0']))
    {
        $sOrdem=explode(";",$_GET['order']); 
        if(in_array($sOrdem[0],$aColumns)) {
        $sOrder = "ORDER BY $sOrdem[0] $sOrdem[1]";  
        }
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
    
    if(sizeof($qParameters)>0){
        if($sWhere==""){ $sWhere .= "WHERE";   } else { $sWhere .= "AND "; }
        foreach($qParameters as $parm){
            if($_GET[''.$parm.'']!=""){
            $sWhere.=" $parm = '".$_GET[''.$parm.'']."' AND ";  
            }
        }
        $sWhere = substr_replace( $sWhere, "", -5);
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

	
	$sQuery = $database->select($sTable, $aColumns, Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".($offset*$limit)." ROWS FETCH NEXT ".$limit." ROWS ONLY"));
	
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
