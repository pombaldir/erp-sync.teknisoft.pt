<?php header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Headers: : x-requested-with');
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
if(isset($_GET['act_g']) && $act_get=="list" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Referencias"; 
    /*
    * Columns
    */ 
	$aColumns = array('Id','strCodReferencia',  'strCodArtigo', 'strDescricao','strCodGrelhaHorLin','strCodGrelhaVerLin','QuantStock','bitPortalWeb', 'strCodCategoria', 'imgImagem');  
    $sColumns = array('Id','strCodReferencia',  'Tbl_Gce_ArtigosReferencias.strCodArtigo', 'Tbl_Gce_Referencias.strDescricao','strCodGrelhaHorLin','strCodGrelhaVerLin','QuantStock','Tbl_Gce_Artigos.bitPortalWeb', 'strCodCategoria', 'imgImagem');  
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
    
    $queryRef="SELECT Tbl_Gce_Artigos.Id, Tbl_Gce_Artigos.bitPortalWeb, Tbl_Gce_Artigos.strCodCategoria,Tbl_Gce_Artigos.strDescricao,CASE WHEN Tbl_Gce_Artigos.imgImagem IS NULL THEN 0 ELSE 1 END as imgImagem, Tbl_Gce_ArtigosReferencias.strCodReferencia+' - '+ Tbl_Gce_Referencias.strDescricao as strCodReferencia, Tbl_Gce_ArtigosReferencias.strCodArtigo, 
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
