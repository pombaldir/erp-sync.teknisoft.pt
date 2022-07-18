<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("settings.php");
use Medoo\Medoo;

if((isset($_GET['auth_userid']) &&  $_GET['auth_userid']=="$tokenAPI") || (isset($_POST['auth_userid']) && $_POST['auth_userid']=="$tokenAPI")) {
	 
	if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	}
	if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); }
	if(isset($_GET['token']))	{	$token=stripslashes($_GET['token']);	}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="checkweb" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$num=stripslashes($_POST['num']);	
$data = $database->update("Tbl_Gce_Familias", ["bitPortalWeb" => 1], ["Id" => $num]);
$output = array("success" => $data);
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="uncheckweb" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$num=stripslashes($_POST['num']);	 
$data = $database->update("Tbl_Gce_Familias", ["bitPortalWeb" => 0], ["Id" => $num]);
$output = array("success" => $data);
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="listaFamNiveis" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
	if(isset($_GET['term']) ){
	$term=stripslashes($_GET['term']);
	}  
	
	$lista = $database->select("Tbl_Gce_Familias", [
	"strDescricao(text)",
	"strCodigo(id)",
	"strCodTpFamilia"
	], [
		"strDescricao[~]" => ["$term"],
		"ORDER" => ["strDescricao"=>"ASC"]
	]);	
	
	$output=array("results"=>$lista);
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="view" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
	$num=stripslashes($_GET['num']);
	
	$lista = $database->select("Tbl_Gce_Familias", [
	"strDescricao"
	], [
		"Id" => $num
	]);	
	$output=$lista[0];
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="list" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Familias"; 
    /*
    * Columns
    */ 
	$aColumns = array("Id","strCodigo", "strDescricao","bitPortalWeb","strCodTpFamilia");      
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
    }
     
	
	
    $sWhere = "";
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
        $sWhere .= "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true")
        	{
            $sWhere .= "".$aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";		
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
           $sWhere .= " ".$aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
    }
	

  
	
	$sQuery = $database->select($sTable, $aColumns,
	Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY"));
	
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
  
    $iTotal = $database->count("$sTable", ["Id"],
	Medoo::raw("".$sWhere.""));	
       
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
             if ( $aColumns[$i] != ' ' )
            {
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
