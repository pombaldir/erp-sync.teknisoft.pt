<?php header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("conn_mssql.php");
use Medoo\Medoo;

	if((isset($_GET['auth_userid']) &&  $_GET['auth_userid']=="$tokenAPI") || $_POST['auth_userid']=="$tokenAPI") {
	if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	} else {$act_get="";}
	if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); } else {$act_pst="";}
	if(isset($_GET['token']))	{	$token=stripslashes($_GET['token']);	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	if(isset($_GET['act_g']) && $act_get=="search"){
		
	$query=stripslashes($_GET['query']);
	$field=stripslashes($_GET['field']);
	
    $suggestions = $database->select("Tbl_Clientes", [
	"[>]Tbl_SubZonas" => ["strAbrevSubZona" => "strAbrevZona"],
	],[
	"strNumContrib",
	"strNome",
	"intCodigo",
	"strMorada_lin1",
	"strMorada_lin2",
	"strLocalidade",
	"strPostal",
	"strAbrevSubZona",
    "strAbrevZona",
	"strEmail",
	"strTelefone",
	"strTelemovel"
	], [
		"Tbl_Clientes.".$field."[~]" => $query."%",
		"bitInactivo" => 0
	]);        
       
    if(!is_array($suggestions) || sizeof($suggestions)==0){
        $suggestions=array();
    }    
        
    foreach($suggestions as $k=>$v) {
		$suggestionsFinal[] = array("value"=>$v['strNumContrib'], "data"=> $v);
    }
        
   
    //@array_walk_recursive($suggestions,function(&$item){$item=strval($item);});
        
	$output=array("suggestions"=>$suggestionsFinal);
   
	}	 

	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	
	if(isset($_GET['act_g']) && $act_get=="cliente_existe"){
	$nif=stripslashes($_GET['nif']);
	$rResult = mssql_query("select intCodigo from Tbl_Clientes WHERE strNumContrib='$nif'") or die("Erro");
	$post = mssql_fetch_array($rResult);
		$output= array("existe"=>mssql_num_rows($rResult), "intCodigo"=>"$post[intCodigo]"); 
	}

	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	
	if(isset($_GET['act_g']) && $act_get=="view"){
		
	$get_num=stripslashes($_GET['num']);
	$rResult = mssql_query("select * from Tbl_Clientes WHERE intCodigo='$get_num'") or die("Erro");
	$post = mssql_fetch_array($rResult);	
	
	$rPend=mssql_query("SELECT GetPendentesA_Actuais_1.fltValorPendente FROM Tbl_Tipos_Documentos INNER JOIN dbo.GetPendentesA_Actuais(GETDATE(), 1, 0, $get_num) AS GetPendentesA_Actuais_1 ON Tbl_Tipos_Documentos.strAbreviatura = GetPendentesA_Actuais_1.strAbrevTpDoc  where Tbl_Tipos_Documentos.bitDispVendas=1") or die(mssql_get_last_message());
	$somapendente=0;	
	while($post2 = mssql_fetch_array($rPend)){
		$valor=$post2['fltValorPendente'];
		$somapendente=$somapendente+$valor;	
	}
	 
	$output=array("intCodigo"=>"$post[intCodigo]","strNome"=>"$post[strNome]","strNumContrib"=>"$post[strNumContrib]","strMorada_lin1"=>"$post[strMorada_lin1]","strMorada_lin2"=>"$post[strMorada_lin2]","strLocalidade"=>"$post[strLocalidade]","strPostal"=>"$post[strPostal]","strAbrevSubZona"=>"$post[strAbrevSubZona]","strEmail"=>"$post[strEmail]","strTelefone"=>"$post[strTelefone]","strTelemovel"=>"$post[strTelemovel]","bitInactivo"=>"$post[bitInactivo]","bitUseElectronicDocument"=>"$post[bitUseElectronicDocument]","strObs"=>"$post[strObs]","valorPendente"=>"$somapendente");  
	}
	
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	
	if(isset($_GET['act_g']) && $act_get=="valorpendente"){
	$get_num=stripslashes($_GET['num']);	
	$rResult=mssql_query("SELECT GetPendentesA_Actuais_1.fltValorPendente FROM Tbl_Tipos_Documentos INNER JOIN dbo.GetPendentesA_Actuais(GETDATE(), 1, 0, $get_num) AS GetPendentesA_Actuais_1 ON Tbl_Tipos_Documentos.strAbreviatura = GetPendentesA_Actuais_1.strAbrevTpDoc  where Tbl_Tipos_Documentos.bitDispVendas=1") or die(mssql_get_last_message());
	$subt=0;
	$docs=array();	
	while($post = mssql_fetch_array($rResult)){
		$valor=$post['fltValorPendente'];
		$subt=$subt+$valor;	
	}
		$output= array("totalpend"=>"$subt", "docs"=>$docs);
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	
	if(isset($_GET['act_g']) && $act_get=="viewPend"){
	$get_num=stripslashes($_GET['num']);	
	$docs=stripslashes($_GET['docs']);	

$rResult = mssql_query("SELECT fltTotal*intSinal as fltValorOriginal, fltValorPendente*intSinal as fltValorPendente,strAbrevTpDoc,strNumero,intNumero,Id,convert(varchar, dtmData, 105) as dtmData  from dbo.GetPendentesA_Actuais(GETDATE(), 1, 0, $get_num) WHERE GetPendentesA_Actuais.Id IN (".implode(', ', explode(",",$docs)).") ") or die(mssql_get_last_message ()); 
 
 while($post=mssql_fetch_array($rResult)){
		$Id=$post['Id'];
		$strAbrevTpDoc=$post['strAbrevTpDoc'];
		$strNumero=$post['strNumero'];
		$intNumero=$post['intNumero'];
		$fltTotal=$post['fltValorOriginal'];
		$fltValorPendente=$post['fltValorPendente'];
		$dtmData=$post['dtmData'];
		$documentos[]=array("Id"=>"$Id", "dtmData"=>$dtmData, "fltTotal"=>$fltTotal, "fltValorPendente"=>$fltValorPendente, "strAbrevTpDoc"=>$strAbrevTpDoc, "strNumero"=>$strNumero, "intNumero"=>$intNumero);
	
 }
	
		$output=$documentos;
		
	}
	
	

	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['act_p']) && $act_pst=="updtObs"){
$numcliente=addslashes($_POST['ncliente']);
$observacao=addslashes($_POST['observacao']);
$nif=addslashes($_POST['strnif']);
mssql_query("update Tbl_Clientes set strObs='$observacao' WHERE intCodigo='$numcliente' and strNumContrib='$nif'");
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['act_p']) && ($act_pst=="edit" || $act_pst=="ad")){

$numcliente=addslashes($_POST['intCodigo']);
$cp=addslashes($_POST['strPostal']);
$email=addslashes($_POST['strEmail']);
$fax=addslashes($_POST['fax']);
$localidade=addslashes($_POST['strLocalidade']);
$morada1=addslashes($_POST['strMorada_lin1']);
$morada2=addslashes($_POST['strMorada_lin2']);
$nif=addslashes($_POST['strNumContrib']);
$nome=addslashes($_POST['nomecliente']);
$subzona=addslashes($_POST['subzona']);
$tel=addslashes($_POST['strTelefone']);
$tlm=addslashes($_POST['strTelemovel']);
$zona=addslashes($_POST['zona']);
if(isset($_POST['bitInactivo'])){$bitInactivo=0;}else{$bitInactivo=1;} 
if(isset($_POST['bitUseElectronicDocument'])){$bitUseElectronicDocument=1;}else{$bitUseElectronicDocument=0;} 


if($act_pst=="edit")
{
##################################################################	EDITA CLIENTE  #########################################################	  
mssql_query("update Tbl_Clientes set strTelefone='$tel', strFax='$fax', strTelemovel='$tlm', strAbrevSubZona='$subzona',strEmail='$email',strMorada_lin1='$morada1',strMorada_lin2='$morada2',strLocalidade='$localidade',strPostal='$cp',bitInactivo='$bitInactivo',bitUseElectronicDocument='$bitUseElectronicDocument' WHERE intCodigo='$numcliente' and strNumContrib='$nif'");

$mensagem="1";
$htmlmsg="Ficha de Cliente editada";
$class="fa-exclamation-triangle vd_green";	

}	if($act_pst=="ad")	{
#################################################################	ADICIONA CLIENTE  ######################################################	
	$rq=mssql_query("select TOP 1 intCodigo from Tbl_Clientes where intCodigo NOT IN (".implode(", ", $arrexcluir).") order by intCodigo desc");
	$lcl=mssql_fetch_array($rq);
	$numcliente=$lcl['intCodigo']+1;
	
	$rq2=mssql_query("select TOP 1 intCodigo from Tbl_Clientes where strNumContrib='".$_POST['strNumContrib']."'");
	if(mssql_num_rows($rq2)>0){
	$lex=mssql_fetch_array($rq2);	
	$mensagem="0";  
	$htmlmsg=utf8_encode("Ja existe cliente com esse NIF");	
	$numcliente=$lex['intCodigo'];
	$class="fa-exclamation-triangle vd_red";
	} else {
	mssql_query("INSERT INTO Tbl_Clientes (strTelefone,strFax,strTelemovel,strAbrevSubZona,strEmail,strMorada_lin1,strMorada_lin2,strLocalidade,strPostal,strNome,strNumContrib,intCodigo,bitUseElectronicDocument) VALUES ('$tel','$fax','$tlm','$subzona','$email','$morada1','$morada2','$localidade','$cp','$nome','$nif','$numcliente','$bitUseElectronicDocument')") or $errormsg=mssql_get_last_message();
    
	if($errormsg==""){
	$mensagem="1";
	$htmlmsg=utf8_encode("Ficha de cliente criada");
	$class="fa-exclamation-triangle vd_green";	
	} else {
	$mensagem="0";
	$htmlmsg=$errormsg;
	$class="fa-exclamation-triangle vd_red";
	}
	
}
}


$output = array("response" => "$mensagem", "msg" =>"$htmlmsg", "intCodigo" => "$numcliente", "class"=> "$class"); 
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
if($act_get=="" && $act_pst=="" ){   
    
	$sIndexColumn 	= "intCodigo";
    $sTable 		= "Tbl_Clientes";
 	$aColumns 		= array('intCodigo','strNome','strNumContrib','strTelefone','strTelemovel','strLocalidade','strEmail'); 
 
  	$sLimit = "";
  	$sLimit = "TOP " . addslashes( $_GET['iDisplayLength'] ) .  " ";
  	$sLimit2 = "";
  	$sLimit2 = "TOP " . addslashes( ((int)$_GET['iDisplayStart']) ) .  " "; 

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
            $sWhere .= $aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";
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
            $sWhere .= $aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
    }
     		
	 	if($sWhere==""){
		$sWhere="where strNome<>'' and bitInactivo=0 AND intCodigo NOT IN (".implode(", ", $arrexcluir).") "; 
		} else {
		$sWhere .="and strNome<>'' and bitInactivo=0 "; 	
		}
		
		if(isset($_GET['filtro']) and $_GET['filtro']!=""){
			    $filtro=$_GET['filtro'];
				$filtro=explode(",", $_GET['filtro']);

			if($sWhere==""){
			$sWhere="WHERE intCodigo IN ('" . implode("','", $filtro) . "') "; 
			} else {
			$sWhere .="AND intCodigo IN ('" . implode("','", $filtro) . "') "; 	
			}
		}
		
		
     	
	$sQuery = "SELECT " . $sLimit . " ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM $sTable $sWhere
    AND $sIndexColumn NOT IN
    (
        SELECT $sIndexColumn FROM
        (
            SELECT " . $sLimit2  . " ".str_replace(" , ", " ", implode(", ", $aColumns))."  FROM $sTable  $sWhere 
            $sOrder
        )
        as [virtTable]
    )
    $sOrder";
	
    $rResult = mssql_query($sQuery) or die("$sQuery: " . mssql_get_last_message());
     
    /* Data set length after filtering */
    $sQueryCnt = "SELECT count(*) as counter FROM $sTable $sWhere $sWhere2";
     
    $rResultCnt = mssql_query($sQueryCnt ) or die (" $sQueryCnt: " . mssql_get_last_message());
    $aResultCnt = mssql_fetch_array($rResultCnt);
    $iFilteredTotal = $aResultCnt['counter'];       
      
    /* Total data set length */
    $sQuery = "
        SELECT COUNT(".$sIndexColumn.") as counter
        FROM   $sTable $sWhere 
    ";
    $rResultTotal = mssql_query($sQuery ) or die(mssql_get_last_message());
    $aResultTotal = mssql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal['counter']; 
             
    /*
     * Output
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
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( $aColumns[$i] == "strNome" ) 
            {
                $row[] = "<a href=\"clientes.php?act=edit&num=".$aRow[ $aColumns[0] ]."\">   ".$aRow[ $aColumns[$i] ]."   </a>";
            }
			 else if ( $aColumns[$i] == "strTelefone" ) 
            {
				$telefone=$aRow[ $aColumns[$i] ];
               
                $row[] = mb_substr(preg_replace('/\s+/', '', $telefone),0,9);
            }
			
			
 			else if ( $aColumns[$i] == "strEmail" ) 
            {
			$cliente=$aRow[$aColumns[0]];  
			$nome=$aRow[$aColumns[1]]; 
			$email=$aRow[$aColumns[$i]]; 
			if($email==""){
			$htmemail="<a data-original-title=\"E-mail\" data-toggle=\"tooltip\" data-placement=\"top\" class=\"btn btn-xs menu-icon vd_bd-red vd_red\" href=\"#\"> <i class=\"fa  fa-envelope\"></i> </a>";		
			$htmemail.=" <a data-original-title=\"Marketing\" data-toggle=\"tooltip\" data-placement=\"top\" class=\"btn btn-xs menu-icon vd_bd-red vd_red\" href=\"#\"> <i class=\"fa fa-send\"></i> </a>";		

			}  else {
			$htmemail="<a data-original-title=\"E-mail\" data-toggle=\"tooltip\" data-placement=\"top\" class=\"btn btn-xs menu-icon vd_bd-green vd_green\" href=\"mensagens.php?act=send&tp=new&customer=$cliente\"> <i class=\"fa  fa-envelope\"></i> </a>";		
			$htmemail.=" <a data-original-title=\"Marketing\" data-toggle=\"tooltip\" data-emailcust=\"$email\" data-nome=\"$nome\" data-cliente=\"$cliente\"  href=\"#\" class=\"btn btn-xs menu-icon vd_bd-blue vd_blue btnModalM\"> <i class=\"fa fa-send\"></i> </a>";		
			}
			
			
            $row[] = '<a data-original-title="editar" data-toggle="tooltip" data-placement="top" class="btn btn-xs menu-icon vd_bd-blue vd_blue" href="clientes.php?act=edit&num='.$aRow[ $aColumns[0] ].'"> <i class="fa fa-pencil"></i> </a> '.$htmemail.'';
            }			
			
            else if ( $aColumns[$i] != ' ' )
            {
                $row[] = $aRow[ $aColumns[$i] ];
            }
        }
        $output['aaData'][] = $row;
    }
    }	
	echo json_encode($output);
}?>