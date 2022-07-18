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
$id_artigo=stripslashes($_POST['id_artigo']);	

	$dataUpdt = $database->update("Tbl_Gce_Artigos", ["bitPortalWeb" => 1], ["Id" => $id_artigo]);
	
	if($dataUpdt==1){
	$post = $database->select("Tbl_Gce_Artigos", [
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	],[
	"strCodigo",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"QuantStock",
	"imgImagem"
	], [
		"Tbl_Gce_Artigos.Id" => $id_artigo,
		"LIMIT" => 1
	]);
		 
	# # IMAGEM
	if($post[0]['imgImagem']==""){
	$hasImage=0;
	$imgData="";	
	} else {
	$hasImage=1;	
	$imgData=base64_encode($post[0]['imgImagem']);		 
	} 
	# # STOCK
	$stock=number_format($post[0]['QuantStock'],2,".",""); 
	# # PREÇOS
	$qPrecos = $database->select("Tbl_Gce_ArtigosPrecos", [
	"intNumero",
	"fltPreco"
	], [
		"strcodArtigo" => $post[0]['strCodigo']
	]);	
	 
	foreach($qPrecos as $postprecos){
		$precos[$postprecos['intNumero']]=number_format($postprecos['fltPreco'],2,".","");
	}												

	# # FAMILIA
	$qFamilias = $database->select("Tbl_Gce_ArtigosFamilias", [
	"strCodFamilia",
	"strCodTpNivel"
	], [
		"strcodArtigo" => $post[0]['strCodigo']
	]);	
	$arrfam=array();
	foreach($qFamilias as $fam){
	$arrfam[$fam['strCodTpNivel']]=$fam['strCodFamilia'];	
	}
	//print_r($database->log());
	$detalhes=array("strCodigo"=>$post[0]['strCodigo'],"strCodCategoria"=>$post[0]['strCodCategoria'],"strDescricao"=>$post[0]['strDescricao'],"strDescricaoCompl"=>$post[0]['strDescricaoCompl'],"strObs"=>$post[0]['strObs'],"strCodFamilia"=>$post[0]['strCodFamilia'],"hasImage"=>$hasImage,"imgData"=>$imgData,"stock"=>$stock,"precos"=>$precos,"familias"=>$arrfam); 
	}

	if($html_msg==""){
	$sucesso=1;	$html_msg="Artigo atualizado no ERP";  
	} else {		$sucesso=0;		}
	$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg", "idartigo" => "$id_artigo", "artigo" => $detalhes);

}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="uncheckweb" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
	$id_artigo=$_POST['id_artigo'];
	$dataUpdt = $database->update("Tbl_Gce_Artigos", ["bitPortalWeb" => 0], ["Id" => $id_artigo]);
	if($dataUpdt==1){
	$sucesso=1;	$html_msg="Artigo atualizado no ERP";  
	} else {
	$sucesso=0;	$html_msg="ERRO SQL ".$database->log();  	
	}
	$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg", "idartigo" => "$id_artigo");

}
	
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="list" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "strCodigo"; 
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Artigos"; 
    /*
    * Columns
    */ 
	$aColumns = array('Id','strCodigo', 'strDescricao','QuantStock', 'bitPortalWeb', 'strCodCategoria');      
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
     
	
	
    $sWhere = "WHERE Tbl_Gce_Artigos.bitInactivo=0 ";
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
        $sWhere .= "AND (";
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
     			
	
	
	if(isset($_GET['Familia']) && $_GET['Familia']!=""){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_ArtigosFamilias.strCodFamilia='".$_GET['Familia']."'";  
	}
		

	//die($sWhere);
	/*
	$sQuery = $database->select("Tbl_Gce_Artigos", [
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"]
	],[
	"strCodigo",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"QuantStock",
	"strCodFamilia",
	"imgImagem"
	], [
		"Tbl_Gce_Artigos.Id" => 3537,
		"Tbl_Gce_ArtigosFamilias.strCodTpNivel" => "Niv 1"
	]);
	*/
	
	
	$sQuery = $database->select("Tbl_Gce_Artigos", [
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"],
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	], [
		"Tbl_Gce_Artigos.strCodigo",
		"Tbl_Gce_Artigos.strDescricao",
		"Tbl_Gce_Artigos.bitPortalWeb",
		"Tbl_Gce_Artigos.Id",
		"QuantStock[Int]", 
		"strCodFamilia"
	],
	Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY"));
	
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
 
 	/*
	SELECT [Tbl_Gce_Artigos].[strCodigo],[Tbl_Gce_Artigos].[strDescricao],[Tbl_Gce_Artigos].[bitPortalWeb],[Tbl_Gce_Artigos].[Id],
	[Tbl_Gce_Artigos].[strTpArtigo],[Tbl_Gce_Artigos].[strCodCategoria] FROM [Tbl_Gce_Artigos] 
	LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	WHERE [Tbl_Gce_Artigos].[strDescricao] != '' AND [Tbl_Gce_Artigos].[bitInactivo] = 0 
	ORDER BY [Tbl_Gce_Artigos].[Id] DESC OFFSET 0 ROWS FETCH NEXT 5 ROWS ONLY
	*/ 
 
    $iTotal = $database->count("Tbl_Gce_Artigos", [
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"],
	], ["Tbl_Gce_Artigos.strCodigo"],
	Medoo::raw("".$sWhere.""));
	
	 /* 
	 SELECT COUNT([Tbl_Gce_Artigos].[strCodigo]) FROM [Tbl_Gce_Artigos] 
	 LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	 WHERE [Tbl_Gce_Artigos].[strDescricao] != '' AND [Tbl_Gce_Artigos].[bitInactivo] = 0
	 */

       
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
		//$row['DT_RowId'] = 'row_'.$aRow['Id'];
		//$row['DT_RowClass'] = 'grade'.$aRow['strCodigo'];
		
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
             if ( $aColumns[$i] != ' ' )
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
