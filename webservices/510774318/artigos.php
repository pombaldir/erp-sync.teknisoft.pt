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


if(isset($_GET['act_g']) && $act_get=="sku"){
	
	$codartigo=$_GET['strCodigo'];	
  
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );		
	
	$rResult = sqlsrv_query($conn, "SELECT QuantStock from Gce_stk_real where strCodArtigo='$codartigo'", $params, $options) or die( print_r( sqlsrv_errors(), true));   

	$l = sqlsrv_fetch_array($rResult, SQLSRV_FETCH_ASSOC);
	
	$stock=number_format($l['QuantStock'],2,".",""); 
	$found=sqlsrv_num_rows($rResult);		   
								
	$output = array("stock"=>$stock,"found"=>$found);

}  

	if(isset($_GET['act_g']) && $act_get=="getLangTitle"){
	$lang=stripslashes($_GET['lang']);
	$field=stripslashes($_GET['field']);
	$artigo=stripslashes($_GET['artigo']);
	
	$rResult = sqlsrv_query($conn, "SELECT Tbl_Gce_ArtigosIdiomas.strDescricao, Tbl_Gce_ArtigosIdiomas.strDescricaoCompl, Tbl_Gce_ArtigosIdiomas.strExcerto, Tbl_Gce_ArtigosIdiomas.strCodIdioma, Tbl_Gce_ArtigosIdiomas.strCodArtigo
FROM Tbl_Gce_ArtigosIdiomas INNER JOIN Tbl_Gce_Artigos ON Tbl_Gce_ArtigosIdiomas.strCodArtigo = Tbl_Gce_Artigos.strCodigo where Tbl_Gce_Artigos.Id='$artigo' and Tbl_Gce_ArtigosIdiomas.strCodIdioma='$lang'") or die( print_r( sqlsrv_errors(), true));   
	$linha = sqlsrv_fetch_array($rResult, SQLSRV_FETCH_ASSOC);
	if($field=="descricao"){
		$valtitulo=$linha['strDescricao'];   
	}
	if($field=="descricao"){
		$valtitulo=$linha['strDescricaoCompl'];    
	}
	if($field=="titulo"){
		$valtitulo=$linha['strDescricao'];   
	}
	if($valtitulo=="null"){
		$valtitulo=""; 
	}
	
	
	$html_msg="($lang) $valtitulo<br><br>clique submeter para guardar os dados"; 
	$output = array("mensagem" => "sucesso", "html" => "$html_msg",  "valor" => "$valtitulo");	
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($_POST['act_p']) && $act_pst=="checkweb"){
	$id_artigo=$_POST['id_artigo'];	
	$rResult =mssql_query("UPDATE Tbl_Gce_Artigos set bitPortalWeb=1 WHERE Id='$id_artigo'") or die("$sQuery: " . print_r( mssql_get_last_message(), true)); 
	
	$query = "SELECT strCodigo,strCodCategoria,strDescricao,strDescricaoCompl,strObs,imgImagem from Tbl_Gce_Artigos where Id='$id_artigo'";
	$result = mssql_query($query) or $html_msg=mssql_get_last_message();
	$post = mssql_fetch_array($result); 
	$codartigo=$post['strCodigo'];  
	 
	if($post['imgImagem']==""){
	$imgImagem=0;	
	} else {
	$imgImagem=1;		
	} 
	
	# # STOCK
	$rStock = mssql_query("SELECT QuantStock from Gce_stk_real where strCodArtigo='$codartigo'") or $html_msg=mssql_get_last_message();
	$lStock = mssql_fetch_array($rStock);
	$stock=number_format($lStock['QuantStock'],2,".",""); 
	# # PREÇOS
	$queryprecos = "SELECT intNumero,fltPreco from Tbl_Gce_ArtigosPrecos where strcodArtigo='$codartigo'";
	$resultprecos = mssql_query($queryprecos) or $html_msg=mssql_get_last_message();
	while($postprecos = mssql_fetch_array($resultprecos)) {
		$precos[$postprecos['intNumero']]=number_format($postprecos['fltPreco'],2,".","");
	}
												
	$detalhes=array("strCodigo"=>$post['strCodigo'],"strCodCategoria"=>$post['strCodCategoria'],"strDescricao"=>$post['strDescricao'],"strDescricaoCompl"=>$post['strDescricaoCompl'],"strObs"=>$post['strObs'],"imgImagem"=>$imgImagem,"stock"=>$stock,"precos"=>$precos); 

	if($html_msg==""){
	$sucesso=1;	$html_msg="Artigo atualizado no ERP";  
	} else {		$sucesso=0;		}
	$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg", "idartigo" => "$id_artigo", "artigo" => $detalhes);

	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	if(isset($_POST['act_p']) && $act_pst=="uncheckweb"){
	
	$id_artigo=$_POST['id_artigo'];
	$rResult =mssql_query("UPDATE Tbl_Gce_Artigos set bitPortalWeb=0 WHERE Id='$id_artigo'") or $html_msg=mssql_get_last_message();
	
	$sucesso=1;	$html_msg="Artigo atualizado no ERP";  
	$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg", "idartigo" => "$id_artigo");
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if(isset($act_get) && $act_get=="view" && !isset($_POST['act_p'])){
		
	$num=$_GET['num'];
	$output = array();
	$precos = array();
	$stocks = array();
	$familias = array();
	
	$query = "SELECT strCodigo,intCodInterno,strDescricao,strDescricaoCompl,strObs,imgImagem,fltPCReferencia,strCodCategoria  from Tbl_Gce_Artigos where Id='$num'";
	$result = sqlsrv_query($conn, $query) or die( print_r( sqlsrv_errors(), true));

		while($post = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
			$codartigo=utf8_encode($post['strCodigo']);
			$queryprecos = "SELECT intNumero,fltPreco from Tbl_Gce_ArtigosPrecos where strcodArtigo='$codartigo'";
			$resultprecos = sqlsrv_query($conn, $queryprecos) or die( print_r( sqlsrv_errors(), true));
				while($postprecos = sqlsrv_fetch_array($resultprecos, SQLSRV_FETCH_ASSOC)) {
					$precos[]=array("intNumero"=>$postprecos['intNumero'],"fltPreco"=>$postprecos['fltPreco']);
				}
				
			$querystocks = "SELECT strCodArtigo, QuantStock, ReservaStock FROM Gce_stk_real WHERE (strCodArtigo = '$codartigo')";
			
			$resultstk = sqlsrv_query($conn, $querystocks) or die( print_r( sqlsrv_errors(), true));

				while($poststk = sqlsrv_fetch_array($resultstk, SQLSRV_FETCH_ASSOC)) {
					$stocks[]=array("QuantStock"=>$poststk['QuantStock'],"ReservaStock"=>utf8_encode($poststk['ReservaStock'])); 
				}
				
			$queryfamiliasart = "SELECT strcodfamilia,strCodTpNivel from Tbl_Gce_ArtigosFamilias where strcodArtigo='$codartigo'";
			$resultartfam = sqlsrv_query($conn, $queryfamiliasart) or die( print_r( sqlsrv_errors(), true));
				while($postarttfml = sqlsrv_fetch_array($resultartfam, SQLSRV_FETCH_ASSOC)) {
				$familias[]=array("strcodfamilia"=>$postarttfml['strcodfamilia'],"strCodTpNivel"=>$postarttfml['strCodTpNivel']);
				}	
				
				
				
			$queryTraduc = sqlsrv_query($conn, "SELECT Tbl_Gce_ArtigosIdiomas.strDescricao, Tbl_Gce_ArtigosIdiomas.strDescricaoCompl, Tbl_Gce_ArtigosIdiomas.strExcerto, Tbl_Gce_ArtigosIdiomas.strCodIdioma, Tbl_Gce_ArtigosIdiomas.strCodArtigo
FROM Tbl_Gce_ArtigosIdiomas INNER JOIN Tbl_Gce_Artigos ON Tbl_Gce_ArtigosIdiomas.strCodArtigo = Tbl_Gce_Artigos.strCodigo where Tbl_Gce_Artigos.Id='$num'") or die( print_r( sqlsrv_errors(), true));   
	while($linhatr = sqlsrv_fetch_array($queryTraduc, SQLSRV_FETCH_ASSOC)){
		$valtitulo=$linhatr['strDescricao'];   
		$valtrad=$linhatr['strDescricaoCompl'];    
		$validioma=$linhatr['strCodIdioma']; 
		$idiomas[]=array("strDescricao"=>$valtitulo,"strDescricaoCompl"=>$valtrad,"strCodIdioma"=>$validioma);		
	}	 
			
			$output[] = array("strCodigo"=>$codartigo,"intCodInterno"=>utf8_encode($post['intCodInterno']),"strDescricao"=>htmlentities($post['strDescricao']),"strDescricaoCompl"=>utf8_encode($post['strDescricaoCompl']),"strObs"=>utf8_encode($post['strObs']),"imgImagem"=>base64_encode($post['imgImagem']),"fltPCReferencia"=>utf8_encode($post['fltPCReferencia']),"precos"=>$precos,"stocks"=>$stocks,"familias"=>$familias,"idiomas"=>$idiomas);
		}  
		
	
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
	if(isset($_POST['act_p']) && $act_pst=="edit"){
		
		
	
	$id_artigo=$_POST['numro'];
	$codigo=$_POST['codigo'];
	$descricao=$_POST['descricao'];  
	$titulo=utf8_decode($_POST['titulo']); 
	$editatitulo=$_POST['editatitulo'];
	
	*/
	
	if(isset($_GET['act_g']) && $act_get=="edit"){
		
	$id_artigo=$_GET['numro'];
	$codigo=$_GET['codigo'];
	$descricao=utf8_decode($_GET['descricao']);  
	$titulo=utf8_decode($_GET['titulo']); 
	$editatitulo=$_GET['editatitulo'];
		
		
	
if(isset($_FILES['imgfile'])){$tamanhofoto=$_FILES['imgfile']['size'];} else {$tamanhofoto=0;}
		
		$qerfoto="";
		if($tamanhofoto > 0)
		{
          $imgContent=file_get_contents($_FILES['imgfile']['tmp_name']);
		  $data= unpack("H*hex", $imgContent); 
		  $qerfoto=",imgImagem=0x".$data['hex']."";
		}	
		
		if(isset($_POST['editatitulo']) && $_POST['editatitulo']==1){
		 $qerfoto.=",strDescricao='$titulo'";  
		}
		
		
	$rResult = sqlsrv_query($conn, "UPDATE Tbl_Gce_Artigos set strObs='$descricao'$qerfoto WHERE Id='$id_artigo' and strCodigo='$codigo'") or die( print_r( mssql_get_last_message(), true));
	
	
	$html_msg="artigo editado";
	
	$output = array("mensagem" => "sucesso", "html" => "$html_msg");	
		
	}
	
	 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	if(isset($_POST['act_p']) && $act_pst=="editfoto"){
	
	$idexterno=addslashes($_POST['idexterno']);

	if(isset($_FILES['imgfile'])){$tamanhofoto=$_FILES['imgfile']['size'];} else {$tamanhofoto=0;}
	
	//if(isset($_POST['imgfile'])){$tamanhofoto=$_POST['imgfile']['size'];} else {$tamanhofoto=0;}
		
		$qerfoto="";
		if($tamanhofoto > 0)
		{
          $imgContent=file_get_contents($_FILES['imgfile']['tmp_name']);
		  $data= unpack("H*hex", $imgContent); 
		  $qerfoto="imgImagem=0x".$data['hex']."";
		  
		 $rResult = sqlsrv_query($conn, "UPDATE Tbl_Gce_Artigos set $qerfoto WHERE Id='$idexterno'") or die( print_r( sqlsrv_errors(), true)); 
		  
		}	

	
	$html_msg="foto enviada: $tamanhofoto";
	
	//$output = array("mensagem" => "sucesso", "html" => "$html_msg");	
		
	}
	
	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	
	
	
	
	
	if(isset($act_get) && $act_get=="elim_img" && !isset($_POST['act_p'])){
		
	$idartigo=$_GET['idartigo'];
	
	$rResult = sqlsrv_query($conn, "UPDATE Tbl_Gce_Artigos set imgImagem=NULL WHERE Id='$idartigo'") or die( print_r( sqlsrv_errors(), true));
	
	$msghtml="foto eliminada";
		
	$output = array("mensagem" => "sucesso", "html" => "$msghtml");		
	} 
	
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	if(isset($act_get) && $act_get=="getFoto" && !isset($_POST['act_p'])){
				
	$idartigo=$_GET['idartigo'];
	
	mssql_query("SET TEXTSIZE 5242880");
	$rResult = mssql_query("select imgImagem from Tbl_Gce_Artigos WHERE Id='$idartigo'") or die( print_r( mssql_get_last_message(), true));
	$linha = mssql_fetch_array($rResult);
	$imgImagem=$linha['imgImagem']; 
	if($imgImagem!=""){
		$output = base64_encode($imgImagem);	
	} else {
		$output="";	
	}
	} 
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	if(isset($act_get) && $act_get=="list" && !isset($_POST['act_p'])){

       $sIndexColumn = "strCodigo"; 
     
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Artigos"; 
 
    /*
    * Columns
    * If you don't want all of the columns displayed you need to hardcode $aColumns array with your elements.
    * If not this will grab all the columns associated with $sTable
    */ 
	/*	*/
	
$aColumns = array('Id','strCodigo', 'strDescricao','strTpArtigo', 'bitPortalWeb', 'strCodCategoria');      
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
            $sWhere .= "Tbl_Gce_Artigos.".$aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";
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
           $sWhere .= "Tbl_Gce_Artigos.".$aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
		   // $sWhere .= "Tbl_Gce_Artigos.strDescricao LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
		
    }
     	
	 	if($sWhere==""){
		$sWhere2="where Tbl_Gce_Artigos.strDescricao<>'' and Tbl_Gce_Artigos.bitInactivo=0"; 
		} else {
		$sWhere2="and Tbl_Gce_Artigos.strDescricao<>'' and Tbl_Gce_Artigos.bitInactivo=0"; 	
		}
		
		if(isset($_GET['categ_id']) && strlen($_GET['categ_id'])>1){
		$sWhere3="AND Tbl_Gce_ArtigosFamilias.strCodFamilia='$_GET[categ_id]'"; 	
		} else {
		$sWhere3="";	
		} 
		
		

	$sQuery = "SELECT " . $sLimit . " Tbl_Gce_Artigos.strCodigo,Tbl_Gce_Artigos.strDescricao,Tbl_Gce_Artigos.bitPortalWeb,Tbl_Gce_Artigos.Id,Tbl_Gce_Artigos.strTpArtigo,Tbl_Gce_Artigos.strCodCategoria FROM $sTable  LEFT OUTER JOIN 
  Tbl_Gce_ArtigosFamilias ON Tbl_Gce_Artigos.strCodigo = Tbl_Gce_ArtigosFamilias.strCodArtigo  $sWhere $sWhere2 $sWhere3   
    AND Tbl_Gce_Artigos.Id NOT IN 
    (
        SELECT Id  FROM
        (
			SELECT " . $sLimit2 . "Tbl_Gce_Artigos.Id FROM $sTable LEFT OUTER JOIN
  Tbl_Gce_ArtigosFamilias ON Tbl_Gce_Artigos.strCodigo = Tbl_Gce_ArtigosFamilias.strCodArtigo $sWhere $sWhere2 $sWhere3 
			    
            $sOrder 
        ) 
        as [virtTable]
    ) GROUP BY Tbl_Gce_Artigos.strCodigo,Tbl_Gce_Artigos.strDescricao,Tbl_Gce_Artigos.Id,Tbl_Gce_Artigos.strTpArtigo,Tbl_Gce_Artigos.strCodCategoria,Tbl_Gce_Artigos.bitPortalWeb
    $sOrder  ";
	 
    $rResult =mssql_query($sQuery) or die( print_r( mssql_get_last_message(), true));
     
    /* Data set length after filtering */
    /* odbc_num_rows isn't supported by all ODBC drivers, so just run a count */
    /* This shouldn't need to be changed */
	if(isset($_GET['categ_id']) && strlen($_GET['categ_id'])>1){
	$sQueryCnt = "SELECT count(*) as counter FROM $sTable LEFT OUTER JOIN 
  Tbl_Gce_ArtigosFamilias ON Tbl_Gce_Artigos.strCodigo = Tbl_Gce_ArtigosFamilias.strCodArtigo  $sWhere $sWhere2 $sWhere3";	
	} else {
    $sQueryCnt = "SELECT count(*) as counter FROM $sTable $sWhere $sWhere2";
	}
     
    $rResultCnt =mssql_query($sQueryCnt ) or die( print_r( mssql_get_last_message(), true));
    $aResultCnt = mssql_fetch_array( $rResultCnt);
    $iFilteredTotal = $aResultCnt['counter'];       
      
    /* Total data set length */
    /* odbc_num_rows isn't supported by all ODBC drivers, so just run a count */
    /* This shouldn't need to be changed */
	if(isset($_GET['categ_id']) && strlen($_GET['categ_id'])>1){
    $sQuery = "SELECT COUNT(".$sIndexColumn.") as counter FROM   $sTable $sWhere $sWhere2";
	} else {
    $sQuery = "SELECT COUNT(".$sIndexColumn.") as counter FROM   $sTable $sWhere $sWhere2";
	}
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
            if ( $aColumns[$i] == "strCodCategoria" ) 
            {
            /* Special output formatting  */ 
            $row[] = "<a href=\"#\"><button class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> editar</button></a>";
            } 
			else  if ( $aColumns[$i] == "strDescricao" ) 
            {
            $row[] = "<a href=\"#\">".$aRow[ $aColumns[$i] ]."</a>";
            }	
				
			else  if ( $aColumns[$i] == "strTpArtigo" )  
            {
			$rResultS = mssql_query("SELECT QuantStock from Gce_stk_real where strCodArtigo='".$aRow[ $aColumns[1] ]."'");   
			$lstocks = mssql_fetch_array($rResultS);
			$row[] = number_format($lstocks['QuantStock'],0);
			}
			
			else  if ( $aColumns[$i] == "bitPortalWeb" ) 
            {
				$valorweb=$aRow[ $aColumns[$i] ];
				if($valorweb==1){$vlor="checked=\"checked\"";} else {$vlor="";}
				$row[] = "<input class=\"chkbx\" id=\"".$aRow[ $aColumns[0] ]."\" name=\"\" type=\"checkbox\" value=\"1\" $vlor />";
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
    echo json_encode($output );
   }
}
?>