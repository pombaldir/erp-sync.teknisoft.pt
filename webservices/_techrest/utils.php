<?php use Medoo\Medoo; include('index.php'); 
##########################################################################################################
if($act=="updateSync"){

    $techval=filter_var($_POST['techval'], FILTER_SANITIZE_ADD_SLASHES);
    $tp=filter_var($_POST['tp'], FILTER_SANITIZE_ADD_SLASHES);
    $val=$_POST['val'];
    $success=0;
   
    
    if($tp=="families" || $tp=="subfamilies" || $tp=="paymentsconditions" || $tp=="typesdocsclients" || $tp=="typesdocsproviders" || $tp=="typesdocsreceiptsclients" || $tp=="typesdocsreceiptsproviders" || $tp=="vatstaxs" || $tp=="exceptionsreasons" || $tp=="employees" || $tp=="units" || $tp=="paymentsmethods" || $tp=="warehouses" || $tp=="inventoryTypes" || $tp=="productTypes"){

        $dataCount = $database->count("USR_sync_taxonomy", ['id'], ["techval" => $techval, "tipo"=> $tp,"shop_number"=>$TECHAPI_shop_number]);
        if($dataCount==0 && $val!=""){   
             $database->insert("USR_sync_taxonomy", ["techval"=>$techval, "erpval"=>$val, "tipo"=>$tp,"dtasync"=>date('Y-m-d H:i:s'),"shop_number"=>$TECHAPI_shop_number]);
             syncLog(1,$tp,'insert',$techval,"Taxonomia criada"); 
        } 
        if($dataCount==1){   
            if($val!=""){ 
                $database->update("USR_sync_taxonomy", ["erpval"=>$val,"dtasync"=>date('Y-m-d H:i:s')], ["techval" => $techval, "tipo"=> $tp,"shop_number"=>$TECHAPI_shop_number]);
                syncLog(1,$tp,'update',$techval,"Taxonomia editada"); 
            } else {
                $database->delete("USR_sync_taxonomy", ["AND" => ["techval" => $techval,"tipo" => $tp, "shop_number"=>$TECHAPI_shop_number]]);
            }
        }   
        $success=1;
    }
    $output=array("success"=>$success, "act"=>$act, "loja"=>$TECHAPI_shop_number, "tp"=>$tp,"val"=>$val,"log"=>$database->error());

}

##########################################################################################################
if($act=="updatesettings"){

    $params=serialize($_POST['param']);

    foreach($_POST['param']['loja']  as $k=>$v){
        $shop_number=$v['shop_number'];
        $seccao=$v['seccao'];
        $sync=$v['sync'];

        $dataCount = $database->count("USR_sync_config", ['id'], ["shop_number" => $shop_number]);
        if($dataCount==0){  
            $database->insert("USR_sync_config", ["params"=>$params, "shop_number" => $shop_number,"sync" => $sync, "seccao"=>$seccao]);
        } else {
            $database->update("USR_sync_config", ["params"=>$params, "shop_number" => $shop_number,"sync" => $sync, "seccao"=>$seccao], ["shop_number" =>$shop_number]);
        }
    }
 
    $output=array("success"=>1,"type"=>"info","message"=>"Dados Guardados");
}

##########################################################################################################
if($act=="updateSyncTax"){
    $techval=filter_var($_POST['techval'], FILTER_SANITIZE_ADD_SLASHES);
    $tp=filter_var($_POST['tp'], FILTER_SANITIZE_ADD_SLASHES);
    $val=filter_var($_POST['val'], FILTER_SANITIZE_ADD_SLASHES);

    $database->update("USR_sync_taxonomy", ["sync"=>$val], ["tipo" =>$tp,"techval"=>$techval,"shop_number"=>$TECHAPI_shop_number]);

$output=array("success"=>1,"type"=>"info","message"=>"Dados Guardados", "log"=>$database->debug());

}

##########################################################################################################
if($act=="resettypesdocsclients"){
    

    $entity=@$_POST['entity']; 
    $strCodExercicio="2023";
    $strCodSeccao="LJ2";  

    $hiddenEntity=$_POST['hiddenEntity']; 

    $arrayEntityGeral=array('families','subfamilies','products','clients');

    if(is_array($entity) && sizeof($entity)>0 && !in_array($entity,$arrayEntityGeral)){
    $database->delete("Mov_Stock_Lin",  ["strAbrevTpDoc" => $entity, "strCodExercicio"=>$strCodExercicio,"strCodSeccao" => $strCodSeccao]);
    $database->delete("Mov_Stock_Cab",  ["strAbrevTpDoc" => $entity, "strCodExercicio"=>$strCodExercicio,"strCodSeccao" => $strCodSeccao]);
    $database->delete("Mov_Venda_Lin",  ["strAbrevTpDoc" => $entity, "strCodExercicio"=>$strCodExercicio,"strCodSeccao" => $strCodSeccao]);
    $database->delete("Mov_Venda_Cab",  ["strAbrevTpDoc" => $entity, "strCodExercicio"=>$strCodExercicio,"strCodSeccao" => $strCodSeccao]);
    $database->delete("Tbl_SAFT_UniqueKey",  ["strAbrevTpDoc" => $entity, "strCodExercicio"=>$strCodExercicio,"strCodSeccao" => $strCodSeccao]);
    $database->update("Tbl_Numeradores",["intNum_Mes00"=>0],  ["strAbrevTpDoc" => $entity, "strCodExercicio"=>$strCodExercicio,"strCodSeccao" => $strCodSeccao, "intTpNumerador"=>1]); 
    // $database->delete("USR_sync_log", ["entity"=>$entity, "strCodExercicio"=>$strCodExercicio]);
 
    }  

    //if(!is_array($entity) && in_array($entity,$arrayEntityGeral)){
        if($hiddenEntity=="families"){
         $database->delete("Tbl_Gce_ArtigosFamilias",["strCodTpNivel"=>$ERP_strCodTpFamilia]);   
         $database->delete("Tbl_Gce_Familias",["strCodTpFamilia"=>$ERP_strCodTpFamilia]);
         $database->delete("USR_sync_taxonomy",["tipo"=>$hiddenEntity]);
        } 
        if($hiddenEntity=="subfamilies"){
            $database->delete("Tbl_Gce_ArtigosFamilias",["strCodTpNivel"=>$ERP_strCodTpSFamilia]);   
            $database->delete("Tbl_Gce_Familias",["strCodTpFamilia"=>$ERP_strCodTpSFamilia]);
            $database->delete("USR_sync_taxonomy",["tipo"=>$hiddenEntity]);
        }    
        if($hiddenEntity=="products"){            
            $database->delete("Tbl_Gce_ArtigosOutrasInfs",[]);   
            $database->delete("Tbl_Gce_ArtigosPrecos",[]); 
            $database->delete("Tbl_Gce_ArtigosPrecosCusto",[]); 
            $database->delete("Tbl_Gce_ArtigosFamilias",[]);  
            $database->delete("Tbl_Gce_Items_PCM_Stock",[]); 
            $database->delete("Mov_Stock_Lin",  []);
            $database->delete("Mov_Stock_Cab",  []);
            $database->delete("Mov_Venda_Lin",  []);
            $database->delete("Mov_Venda_Cab",  []);
            $database->delete("Tbl_SAFT_UniqueKey",  []);   
            $database->delete("Tbl_Gce_Artigos",[]);    
            $database->delete("Tbl_Gce_ArtigosCodigoBarras",[]);    
            $database->delete("USR_sync_taxonomy",["tipo"=>$hiddenEntity]);
           // $database->delete("USR_sync_log", ["entity"=>$entity]);   
        }  
        if($hiddenEntity=="clients"){            
            $database->delete("Tbl_Clientes",[]); 
            $database->delete("USR_sync_taxonomy",["tipo"=>$hiddenEntity]);  
        }   

    //}

    //USR_sync_log

$output=array("success"=>1,"type"=>"info","message"=>"Registos eliminados", "ent"=>$hiddenEntity, "log"=>$database->log(), "err"=>$database->error());

}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act']) && $act=="listLog" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "id"; 
    /* DB table to use */ 
    $sTable = "USR_sync_log"; 
    /*
    * Columns
    */ 
	$aColumns = array('id','dta', 'entity', 'act', 'msg');      
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
        if ( $sWhere == "" )
            {
                $sWhere = "WHERE (";
            }
            else
            {
                $sWhere .= " AND ";  
            }
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
	
	$iTotal = $database->count($sTable, ["id"], Medoo::raw("".$sWhere.""));	

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
		$row['DT_RowId'] = 'row_'.$aRow['id'];
		$row['DT_RowClass'] = 'log';
		
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
			if ( $aColumns[$i] == 'atenaReference' && $aRow[ $aColumns[$i]]=="")
            {
                $row[] = $aRow[ $aColumns[0]];
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



# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act']) && $act=="listErpProducts" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "id"; 
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Artigos"; 
    /*
    * Columns
    */ 
	$aColumns = array('Id','strCodigo','strDescricao','dtmAbertura','dtmAlteracao','strAbrevMedStk'); 
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
        if ( $sWhere == "" )
            {
                $sWhere = "WHERE (";
            }
            else
            {
                $sWhere .= " AND ";  
            }
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
	
	$iTotal = $database->count($sTable, ["id"], Medoo::raw("".$sWhere.""));	

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
		$row['DT_RowId'] = 'row_'.$aRow['Id'];
		$row['DT_RowClass'] = 'art';
		
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
			if ( $aColumns[$i] == 'atenaReference')
            {
                $row[] = $aRow[ $aColumns[0]];
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



# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act']) && $act=="listErpSalesDocs" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    $sTable = "Mov_Venda_Cab"; 
    /*
    * Columns
    */ 
	$aColumns = array('Id','strAbrevTpDoc','strNumero','dtmData','strCVDNome','strCVDNumContrib','fltTotal'); 
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
        if ( $sWhere == "" )
            {
                $sWhere = "WHERE (";
            }
            else
            {
                $sWhere .= " AND ";  
            }
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
	
	$iTotal = $database->count($sTable, ["id"], Medoo::raw("".$sWhere.""));	

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
		$row['DT_RowId'] = 'row_'.$aRow['Id'];
		$row['DT_RowClass'] = 'vnd';
		
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
			if ( $aColumns[$i] == 'atenaReference')
            {
                $row[] = $aRow[ $aColumns[0]];
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

##########################################################################################################

echo json_encode($output);   ?>