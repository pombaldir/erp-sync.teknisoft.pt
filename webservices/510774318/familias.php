<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json');

if((isset($_GET['auth_userid']) && $_GET['auth_userid']=="qeyjp93ek5y6t47r") || (isset($_POST['auth_userid']) && $_POST['auth_userid']=="qeyjp93ek5y6t47r")) {
	include("conn_mssql.php"); 
	if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	}
	if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); }
	if(isset($_GET['token']))	{	$token=stripslashes($_GET['token']);	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if(isset($act_get) && $act_get=="view" && !isset($_POST['act_p'])){
		
	$num=$_GET['num'];
	
	$query = "SELECT strDescricao from Tbl_Gce_Familias where Id='$num'";
	$result = mssql_query($query) or die( print_r( mssql_get_last_message(), true));

	$output = mssql_fetch_assoc($result);		
			
	} 
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($_POST['act_p']) && $act_pst=="checkweb"){
	$num=$_POST['num'];	
	$rResult = mssql_query("UPDATE Tbl_Gce_Familias set bitPortalWeb=1 WHERE Id='$num'") or die("$sQuery: " . mssql_get_last_message());
	$output = array("success" => "1");
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($_POST['act_p']) && $act_pst=="uncheckweb"){
	$num=$_POST['num'];	
	$rResult = mssql_query("UPDATE Tbl_Gce_Familias set bitPortalWeb=0 WHERE Id='$num'") or die("$sQuery: " . mssql_get_last_message());
	$output = array("success" => "1");
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($_POST['act_p']) && $act_pst=="uncheckallweb"){
	$rResult = mssql_query("UPDATE Tbl_Gce_Familias set bitPortalWeb=0") or die("$sQuery: " . mssql_get_last_message());
	$output = array("success" => "1");
	}		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if(isset($act_get) && $act_get=="list" && !isset($_POST['act_p'])){

       $sIndexColumn = "Id"; 
     
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Familias"; 
 
    /*
    * Columns
    * If you don't want all of the columns displayed you need to hardcode $aColumns array with your elements.
    * If not this will grab all the columns associated with $sTable
    */ 
	/*	*/
	
$aColumns = array('Id','strCodigo', 'strDescricao','bitPortalWeb','strCodTpFamilia');      
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP server-side, there is
     * no need to edit below this line
     */


    /* 
     * Paging
     * How rows are limited depends on which database you're using. This will need to be altered depending on your database.
     */
 
  $sLimit = "";
  $sLimit = "TOP " . addslashes( $_GET['iDisplayLength'] ) .  " ";

  $sLimit2 = "";
 // $sLimit2 = "TOP " . addslashes( ((int)$_GET['iDisplayStart'] + (int)$_GET['iDisplayLength']) ) .  " ";
 $sLimit2 = "TOP " . addslashes( ((int)$_GET['iDisplayStart']) ) .  " ";   	  			             
     
//iDisplayStart= varia de 10 em 10, começa em zero na pág inicial
//iDisplayLength= sempre=10 nas paginas todas

     
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
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= "Tbl_Gce_Familias.".$aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";
			//$sWhere .= "Tbl_Gce_Artigos.strDescricao LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";
				 
        }
		//$sWhere .= "Tbl_Clientes.strNome LIKE '%".addslashes( $_GET['sSearch']  )."%'";  
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
           $sWhere .= "Tbl_Gce_Familias.".$aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
		   // $sWhere .= "Tbl_Gce_Artigos.strDescricao LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
		
    }
     	
     
	 if($sWhere==""){
		$sWhere="WHERE Tbl_Gce_Familias.Id<>'' "; 
	 }
 

	$sQuery = "SELECT " . $sLimit . " ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM $sTable  $sWhere   
    AND Id NOT IN 
    (
        SELECT Id  FROM
        (
			SELECT " . $sLimit2 . " Id FROM $sTable $sWhere 
			    
            $sOrder 
        ) 
        as [virtTable]
    )  $sOrder  ";
	
    $rResult=mssql_query($sQuery) or die( print_r( mssql_get_last_message(), true));
     
    /* Data set length after filtering */
    /* odbc_num_rows isn't supported by all ODBC drivers, so just run a count */
    /* This shouldn't need to be changed */
	
	
    $sQueryCnt = "SELECT count(*) as counter FROM $sTable $sWhere $sWhere2";
     
    $rResultCnt =mssql_query($sQueryCnt ) or die( print_r( mssql_get_last_message(), true));
    $aResultCnt = mssql_fetch_array( $rResultCnt);
    $iFilteredTotal = $aResultCnt['counter'];       
      
    /* Total data set length */
    /* odbc_num_rows isn't supported by all ODBC drivers, so just run a count */
    /* This shouldn't need to be changed */

    $sQuery = "SELECT COUNT(".$sIndexColumn.") as counter FROM   $sTable";

    $rResultTotal = mssql_query($sQuery ) or die( print_r( mssql_get_last_message(), true));
    $aResultTotal = mssql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal['counter']; 
         
    /*
     * Output
     * Unchanged
     */
 
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );
     
    while ( $aRow = mssql_fetch_array( $rResult ) )
    {
        $row = array();
		// Add the row ID and class to the object
		//$row['DT_RowId'] = 'row_'.$aRow['Id'];
		//$row['DT_RowClass'] = 'grade'.$aRow['strCodigo'];
		
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( $aColumns[$i] == "strCodTpFamilia" ) 
            {
            /* Special output formatting  */ 
            $row[] = "<a href=\"erp-familias/edit/".$aRow[$aColumns[0]]."\"><button class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> editar</button></a>";
            } 
			else  if ( $aColumns[$i] == "strDescricao" ) 
            {
            $row[] = "<a href=\"#\">".$aRow[ $aColumns[$i] ]."</a>";
            }
				
			else  if ( $aColumns[$i] == "bitPortalWeb" ) 
            {
				$valorweb=$aRow[ $aColumns[$i] ];
				if($valorweb==0){
				$row[] = "Não";
				}
				if($valorweb==1){
				$row[] = "Sim";
				}
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
   if(isset($output)){
	ob_clean();   
    echo json_encode($output );
   }
}
?>
