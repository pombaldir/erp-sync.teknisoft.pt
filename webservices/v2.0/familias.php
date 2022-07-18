<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");
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
if(isset($_POST['act_p']) && $act_pst=="addFamilia" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$descricao=stripslashes($_POST['descricao']);
$nivel=stripslashes($_POST['nivel']);
$parentNivel=$nivel-1;
$output=1; 

if(isset($_POST['topo'])){
$topo=stripslashes($_POST['topo']);
} else { $topo=str_pad($nivel,2,"0",STR_PAD_LEFT); }


$nivelFormat=str_pad($nivel,2,"0",STR_PAD_LEFT);

//$maskara="".$nivelFormat."".$parentNivel."".str_repeat("#", $nivel+1)."";	// 01##

$maskara="$topo".str_repeat("#", $nivel+1)."";	// 01##

$cReferencia=str_replace("#","",$maskara,$asteriks);						// 01

$nResults = $database->select("Tbl_Gce_Familias", [
	"strCodigo",
	],[
		"strCodigo[~]" => "".$cReferencia."%",
		"strCodTpFamilia" => $nivel,
		"ORDER" => ["Id"=>"DESC"]
	]);	
$nRowsCode=sizeof($nResults);

$codFamIns=$cReferencia."".str_pad($nRowsCode+1,$asteriks,"0",STR_PAD_LEFT);
$agora=date('Y-m-d 00:00:00');

$database->insert("Tbl_Gce_Familias", [
	"strCodigo" => "$codFamIns",
	"strDescricao" => "$descricao",
	"strLigCte" => "",
	"strGranelQtdPrefixo" => "",
	"strGranelPrecoPrefixo" => "",
	"strCodFichaRepart" => "",
	"dtmAbertura" => $agora,
	"dtmAlteracao" => $agora,
	"strCodTpFamilia" => $nivel
]);
 
$familiaId = $database->id();/**/

$msg="Família criada com sucesso";

$output = array("success" => $output, "msg"=>"$msg","nrows"=>$nRowsCode,"codigo"=>$codFamIns,"mask"=>$maskara,"id"=>$familiaId);
}


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="getSubFam" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
	$codigo=stripslashes($_GET['codigo']); 	// Nivel 1 => 01001
	$nivel=stripslashes($_GET['nivel']);	// 1
	
	 
	//$codpesq=str_pad(substr($codigo, 0, 2)+1,2,"0",STR_PAD_LEFT);
		
	$list = $database->select("Tbl_Gce_Familias", [
	"strCodigo",
	"strDescricao",
	"strCodTpFamilia",
	"Id"
	],[
		"strCodigo[~]" => "".$codigo."%",
		"strCodTpFamilia" => $nivel+1,
		"ORDER" => ["strDescricao"=>"ASC"]
	]);	
	
	$output=array("total"=>sizeof($list),"strCodTpFamilia"=>$list[0]['strCodTpFamilia'],"lista"=>$list);
}
 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="listaTipos_Familias" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
	$res1 = $database->select("Tbl_Gce_Tipos_Familias", [
	"strCodigo",
	"strDescricao",
	"intNivel",
	"Id"
	],[
		"ORDER" => ["intNivel"=>"ASC"]
	]);	
	
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
  
if(is_array($res1) && sizeof($res1)>0){
	foreach($res1 as $val){
	$out = $database->select("Tbl_Gce_Familias", [
	"strCodigo",
	"strDescricao",
	"Id"
	],[
		"strCodTpFamilia" => $val['strCodigo'],
		"ORDER" => ["strDescricao"=>"ASC"]
	]);	
	$output[$val['strCodigo']]=array("legenda"=>$val['strDescricao'],"lista"=>$out);
	}	 	
} else  {
	$output=array();
}


}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="lista_FamCompl" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
	$res = $database->select("Tbl_Gce_Familias", [
	"strCodigo",
	"strDescricao",
	"strCodTpFamilia",
	"Id"
	],[
		"ORDER" => ["strDescricao"=>"ASC"]
	]);	
	
	foreach($res  as $val){
	$output[$val['strCodTpFamilia']][]=array("strCodigo"=>$val['strCodigo'],"strDescricao"=>$val['strDescricao']);	
	}
	
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="listaFamNiveis" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
	if(isset($_GET['term']) ){
	$term=stripslashes($_GET['term']);
	}  
	$tpnivel=stripslashes($_GET['tpnivel']);
	$lista = $database->select("Tbl_Gce_Familias", [
	"strDescricao(text)",
	"strCodigo(id)",
	"strCodTpFamilia"
	], [
		"strDescricao[~]" => ["$term"],
		"strCodTpFamilia" => ["$tpnivel"],
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
	

	if(isset($_GET['nivel']) && $_GET['nivel']!=""){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "$sTable.strCodTpFamilia='".$_GET['nivel']."'";  
	}  else {
		$sWhere .= " WHERE ($sTable.strCodTpFamilia='' OR $sTable.strCodTpFamilia='1')";  
	}
	
	$sQuery = $database->select($sTable, $aColumns,
	Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY"));
	
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
  
    $iTotal = $database->count("$sTable", ["Id"],
	Medoo::raw("".$sWhere.""));	
	
	//$iTotal = sizeof($sQuery );  
       
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
