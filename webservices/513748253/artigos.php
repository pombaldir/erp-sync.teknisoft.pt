<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("conn_mssql.php");
use Medoo\Medoo;


if((isset($_GET['auth_userid']) &&  $_GET['auth_userid']=="$tokenAPI") || (isset($_POST['auth_userid']) && $_POST['auth_userid']=="$tokenAPI")) {
	 
	if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	}
	if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); }

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="srchArtigo" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
		
	$term=$_GET['term'];
    $output=array();	
	
	$output = $database->select("Tbl_Gce_Artigos", [
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
    "[>]Tbl_Gce_ArtigosPrecos" => ["strCodigo" => "strCodArtigo"],    
	],[
	"strCodigo",
	"intCodInterno",
	"strCodBarras",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"QuantStock",
    "strAbrevMedVnd",
	"bitInactivo",
	"fltPCReferencia",
    "fltPreco",    
	"imgImagem"
	], [
        "OR" => [
		"Tbl_Gce_Artigos.strCodigo[~]" => "$term%",
        "Tbl_Gce_Artigos.strDescricao[~]" => "$term"	
        ],
        "Tbl_Gce_ArtigosPrecos.intNumero" => 1,
		
	]);
	
	
	 
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
	
	if(isset($_GET['Marca']) && $_GET['Marca']!=""){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_ArtigosFamilias.strCodFamilia='".$_GET['Marca']."'";  
	}
	
	if(isset($_GET['bitDispWeb']) && $_GET['bitDispWeb']!="" && $_GET['bitDispWeb']!="0"){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_Artigos.bitPortalWeb='".$_GET['bitDispWeb']."'";  
	}
	
	

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
	
	/*
	$sQuery = $database->select("Tbl_Gce_Artigos", [
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"],
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	], [
		"strCodigo" => Medoo::raw( "DISTINCT Tbl_Gce_Artigos.strCodigo"),
		"Tbl_Gce_Artigos.strDescricao",
		"Tbl_Gce_Artigos.bitPortalWeb",
		"Tbl_Gce_Artigos.Id",
		"QuantStock[Int]", 
		"strCodFamilia"
	],
	Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY"));
	*/
	
	$sQuery = $database->query("SELECT * FROM (SELECT Tbl_Gce_Artigos.strCodigo,Tbl_Gce_Artigos.strDescricao,Tbl_Gce_Artigos.bitPortalWeb,
	Tbl_Gce_Artigos.Id,CAST(QuantStock AS INT) AS QuantStock,strCodFamilia,
	ROW_NUMBER() OVER (PARTITION BY Tbl_Gce_Artigos.Id ORDER BY Tbl_Gce_Artigos.Id ASC) AS RowNumber
	FROM Tbl_Gce_Artigos 
	LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	LEFT JOIN [Gce_stk_real] ON [Tbl_Gce_Artigos].[strCodigo] = [Gce_stk_real].[strCodArtigo]
	".$sWhere." ".$sOrder."  OFFSET 0 ROWS) AS T where T.RowNumber = 1 ".$sOrder."
	OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY")->fetchAll();
	
	 
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
 
 	/*
	SELECT [Tbl_Gce_Artigos].[strCodigo],[Tbl_Gce_Artigos].[strDescricao],[Tbl_Gce_Artigos].[bitPortalWeb],[Tbl_Gce_Artigos].[Id],
	[Tbl_Gce_Artigos].[strTpArtigo],[Tbl_Gce_Artigos].[strCodCategoria] FROM [Tbl_Gce_Artigos] 
	LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	WHERE [Tbl_Gce_Artigos].[strDescricao] != '' AND [Tbl_Gce_Artigos].[bitInactivo] = 0 
	ORDER BY [Tbl_Gce_Artigos].[Id] DESC OFFSET 0 ROWS FETCH NEXT 5 ROWS ONLY
	*/ 
 
 /*
    $iTotal = $database->count("Tbl_Gce_Artigos", [
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"],
	], ["Tbl_Gce_Artigos.Id"],
	Medoo::raw("".$sWhere.""));
	*/
	
	$data = $database->query("SELECT COUNT(strCodigo) as total FROM (SELECT [Tbl_Gce_Artigos].[strCodigo],
	ROW_NUMBER() OVER (PARTITION BY Tbl_Gce_Artigos.Id ORDER BY Tbl_Gce_Artigos.Id DESC) AS RowNumber FROM [Tbl_Gce_Artigos] 
	LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	LEFT JOIN [Gce_stk_real] ON [Tbl_Gce_Artigos].[strCodigo] = [Gce_stk_real].[strCodArtigo]
	".$sWhere.") T WHERE  T.RowNumber=1")->fetchAll();
	
	$iTotal=$data[0]['total'];

	
	
//	die(var_dump( $database->error() ));
//	die(var_dump($database->log()));

	
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
