<?php include("index.php");
use Medoo\Medoo;
//Se for get e vier o act usar esse valor senão usar o list
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accao = isset($_GET['act']) ? $_GET['act'] : "list";
}
//Caso não venham definifidos limites obter os 100 primeiros registos
$limit = isset($_GET['limit']) && $_GET['limit'] != "" ? $_GET['limit'] : 100;
$offset = isset($_GET['offset']) && $_GET['offset'] != "" ? $_GET['offset'] : 0;

/* DB table to use */ 
$sTable = "Mov_Stock_Cab";   
/*
* Columns
*/  
$aColumns = array("strCodSeccao","strAbrevTpDoc","strCodExercicio","intNumero","strNumero","dtmData","bitAnulado","strCodArmazemOriDef","strCodLocalizacaoOriDef","strMeioExpedicao","strLocalCarga","strLocalDescarga","strViatura","strObs","fltTotal","UpdateStamp","dtmDataAbertura","dtmDataAlteracao","Id","strLogin","strAplicacaoOrigem","dtmDataCarga","strHoraCarga","strForteAbrevMoeda","fltForteCambio","bitSafeMode","bitUpdateIntegrador","strCodProjectoOrigem","intPrintCount","dtmDateDoc","bitCostsByExitCosting","intProjectLineOri");  

$aLinhas=array("strCodSeccao","strAbrevTpDoc","strCodExercicio","intNumero","intNumLinha","strCodArmazem","strCodLocalizacao","strCodArtigo","strCodLote","fltQuantidade","fltSinal","fltValorUnitario","fltSubTotal","strObs","strCodClassMovStk","intTipoAssociacao","intLinhaAssociacao","intLinhaReferencia","strFormula","fltMedidaQtd","fltMedida1","fltMedida2","fltMedida3","bitTransferencia","strCodArmazemOri","strCodLocalizacaoOri","dtmDataValor","Id","intNumLinhaDocOri","intNumLinhaDocOriComp","intEdicao","CA_Campo01","CA_Campo02","CA_Campo03","CA_Campo04","CA_Campo05","CA_Campo06","CA_Campo07","CA_Campo08","CA_Campo09","CA_Campo10","strCodDepartamento","dtmDataRecStock","strCodMarca","strCodModelo","strCodVersao","strCodNivel","intIdDuplicacao","fltGOPQtdAtribuida","intNumLinhaOrig","strCodProjecto","intProjectLine","fltQtyReserveToStock","strProjectCodeOri","intProjectLineOri");

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$data = json_decode(file_get_contents('php://input'), true);     
$Id=$data['Id'];
$intNumero=$data['intNumero']; 
$strAbrevTpDoc=$data['strAbrevTpDoc'];      
$strCodSeccao=$data['strCodSeccao'];      
$strCodExercicio=$data['strCodExercicio'];      
    
$queryCliente = $database->select($sTable, ["Id"], ["intNumero" => $intNumero,"strCodSeccao" => $strCodSeccao,"strAbrevTpDoc" => $strAbrevTpDoc,"strCodExercicio" => $strCodExercicio]);	      
     
if(is_array($queryCliente) && sizeof($queryCliente)>0){
    die(json_encode(array("success"=>0,"errormsg"=>"Código existente")));  
}
else if(!isset($data['intTpEntidade'])){
    //die(json_encode(array("success"=>0,"errormsg"=>"Entidade inválida")));  
}    
else {
    
$encLinhas = $data['linhas'];  
foreach ($encLinhas as $key => $linha) {
    foreach ($linha as $coluna => $valor) {
        // se o campo começa por "flt" e não é nulo/vazio, converte para float
        if (strpos($coluna, 'flt') === 0 ) {
            $encLinhas[$key][$coluna] = (float) $valor;
        }
    }
}


unset($data['Id'],$data['linhas'],$data['intPrintCount']);     
     
$database->insert($sTable, $data);  
//die(json_encode($database->error()));  

$idRegisto = $database->id();
    
if($idRegisto>0){      
    
    ## Atualiza Numerador ####
    $database->update("Tbl_Numeradores", ["intNum_Mes00" => $intNumero],["strAbrevTpDoc" => $strAbrevTpDoc,"strCodSeccao" => $strCodSeccao,"strCodExercicio" =>$strCodExercicio]);

    if(!is_array($encLinhas) || sizeof($encLinhas)==0){
        die(json_encode(array("success"=>1,"errormsg"=>"Sem linhas para inserir. Inserido apenas cabeçalho.","Id"=>$idRegisto)));  
    } else {
        recursive_unset($encLinhas, "Id"); 
        $database->insert("Mov_Stock_Lin", $encLinhas); 
        $idLinhas = $database->id();   
    }     
        
    if($idLinhas>0){  
        die(json_encode(array("success"=>1,"message"=>"Movimento criado","Id"=>$idRegisto)));   
    } else {
        $erroBD=$database->error(); 
        ## Remove Encomenda   
        $database->delete("Mov_Stock_Lin", ["strCodSeccao" => $strCodSeccao,"strAbrevTpDoc" =>$strAbrevTpDoc,"strCodExercicio" => $strCodExercicio,"intNumero" => $intNumero]);    
        $database->delete("Mov_Stock_Cab", ["Id" => $idRegisto]);    
        ## Reverte Numerador?
        $intNumeroAnt=$intNumero-1;
        $database->update("Tbl_Numeradores", ["intNum_Mes00" => $intNumeroAnt],["strAbrevTpDoc" => $strAbrevTpDoc,"strCodSeccao" => $strCodSeccao,"strCodExercicio" =>$strCodExercicio]);
        die(json_encode(array("success"=>0,"errormsg"=>$erroBD)));       
    }
         
} else {
    die(json_encode(array("success"=>0,"errormsg"=>$database->error(),"errormsg"=>$database->log())));       
}

} 
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="getDetail" && isset($_GET['num']) && $_SERVER['REQUEST_METHOD'] === 'GET'){
$numEncomenda=$_GET['num'];
$queryEncomenda = $database->select($sTable, $aColumns, ["Id" => $numEncomenda]);	
     
if(sizeof($queryEncomenda)==0) {  
die(json_encode(array("success"=>0,"errormsg"=>"registo inexistente")));
    
} else {       
    
$queryLinha = $database->select("Mov_Stock_Lin", $aLinhas, ["strCodSeccao" => $queryEncomenda[0]['strCodSeccao'],"strAbrevTpDoc" => $queryEncomenda[0]['strAbrevTpDoc'],"strCodExercicio" => $queryEncomenda[0]['strCodExercicio'],"intNumero" => $queryEncomenda[0]['intNumero'],"ORDER" => ["intNumLinha"=>"ASC"]]);	  

    $output = array_merge($queryEncomenda[0],array("linhas"=>$queryLinha)); 
}
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    
    $sIndexColumn = "Id";        
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
     
	
	
    $sWhere = "";
    if ( isset($_GET['q']) && $_GET['q'] != "" && in_array($_GET['searchField'],$aColumns))
    {
        $sWhere .= "WHERE (";
        $sWhere .= "".$_GET['searchField']." = '".addslashes( $_GET['q'] )."'";	
        $sWhere .= ') ';
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
	
	$sQuery = $database->select($sTable, $aColumns,Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".($offset*$limit)." ROWS FETCH NEXT ".$limit." ROWS ONLY"));
    
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
            if ( $aColumns[$i] == 'intPrintCount')
            {
                $queryLinha = $database->select("Mov_Stock_Lin", $aLinhas, [
                    "strCodSeccao" => $aRow['strCodSeccao'],
                    "strAbrevTpDoc" => $aRow['strAbrevTpDoc'],
                    "strCodExercicio" => $aRow['strCodExercicio'],
                    "intNumero" => $aRow['intNumero'],
                    "ORDER" => ["intNumLinha"=>"ASC"]
                ]);	                 
                $row['linhas'] = $queryLinha;
            }
            else if ( $aColumns[$i] == 'UpdateStamp' )
            {
                $row[$aColumns[$i]] = bin2hex($aRow[ $aColumns[$i]]);
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
$output = utf8ize($output);
echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}