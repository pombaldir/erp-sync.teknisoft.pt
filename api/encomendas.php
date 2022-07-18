<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");
use Medoo\Medoo;
//print_r($_GET);

if($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['act']))	{	$accao="list";	}
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['act']))	    {	$accao=$_GET['act'];	}

$limit=isset($_GET['limit']) && $_GET['limit']!="" ? $_GET['limit'] : 100;
$offset=isset($_GET['offset']) && $_GET['offset']!="" ? $_GET['offset'] : 0;


/* DB table to use */ 
$sTable = "Mov_Encomenda_Cab";   
/*
* Columns
*/  
$aColumns = array("Id","strCodSeccao", "strAbrevTpDoc", "strCodExercicio", "intNumero", "strNumero", "strNumExterno", "dtmData", "strHora", "bitAnulado", "intTpEntidade", "intCodEntidade", "intDireccao", "fltDescontoCab", "fltDescontoFin", "strCodCondPag", "strNumRequisicao", "dtmDataRequisicao", "dtmDataVencimento","strAbrevSubZona", "strAbrevMoeda", "fltCambio", "strMeioExpedicao","strLocalCarga","strLocalDescarga", "strObs", "strECVDNumContrib", "strECVDNome", "strECVDMorada",  "strECVDLocalidade", "strECVDCodPostal", "strECVDTelefone", "bitECVDRetIRS", "intCodVendedor", "intTpComissao", "fltComissaoValor", "fltComissaoPercent", "fltComissaoPenaliz", "fltComissaoValorBase", "bitComissaoDispLiq", "dtmComissaoDataDisp","intTpComissaoDescontos", "bitIvaIncluido", "bitRegimeVenda", "fltInfTotalArtigos", "fltInfTotalLinhas", "fltInfTotalQtd", "fltTotalMercadoriaSIVA", "fltTotalMercadoriaCIVA","fltTotalDescontosSIVA","fltTotalDescontosCIVA", "fltTotalDescontosFinSIVA", "fltTotalDescontosFinCIVA", "fltTotalOutrosSIVA", "fltTotalOutrosCIVA", "fltTotalAcertos","intIVACodTaxa1", "intIVACodTaxa2", "fltIVATaxa1", "fltIVATaxa2", "fltIVAIncidencia1", "fltIVAIncidencia2", "fltIVAValor1", "fltIVAValor2", "fltTotalIVA", "fltSubTotal", "fltIRSTaxa", "fltIRSIncidencia", "fltIRSValorRetido", "fltTotal", "fltTotalPagamentos", "fltTroco", "strAplicacaoOrigem", "dtmDataAbertura", "dtmDataAlteracao", "strCodRubrica", "bitArrImpostos","dtmDataEntregaCab", "strHoraEntregaCab","intCodCatEntidade", "intLinhaPreco", "dtmDataEstado", "strObsEstado", "strCodEstadoDoc","fltTotalToPay", "strMotivoIsencao","strLogin", "strLoginCriacao", "strLoginEstado", "dtmDataAlteracaoEstado","intPrintCount");  


$aLinhas=array("strCodSeccao", "strAbrevTpDoc", "strCodExercicio", "intNumero", "intNumLinha", "intTpLinha", "strCodArmazem", "strCodArtigo", "strDescArtigo", "strCodLote", "fltQuantidade", "fltQuantidadePend", "fltQuantidadeAnul", "fltQuantidadeSatisf", "intNumLinhaReserva", "fltQtdReservarStk","fltPrecoUnitario","strCodClassMovStk","strObs","fltDesconto1", "fltDesconto2", "fltDesconto3", "fltDescontoValorUnit", "fltDescontoValor", "fltValorLiquido", "intCodTaxaIVA", "fltTaxaIVA","fltComissaoPerc", "fltComissaoValor", "fltComissaoLinha", "fltValorMercadoriaCIVA", "fltValorMercadoriaSIVA", "fltValorDescontosCIVA", "fltValorDescontosSIVA","fltValorDescontosFinCIVA", "fltValorDescontosFinSIVA", "fltValorAPagar", "Id", "CA_Campo01","strMotivoIsencaoIVA", "strCodOficialMotIVAIsencao");

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$data = json_decode(file_get_contents('php://input'), true);     
$Id=$data['Id'];
$intNumero=$data['intNumero']; 
$strAbrevTpDoc=$data['strAbrevTpDoc'];      
$strCodSeccao=$data['strCodSeccao'];      
$strCodExercicio=$data['strCodExercicio'];      
    
$queryCliente = $database->select($sTable, ["Id"], ["intNumero" => $intNumero]);	      
     
if(is_array($queryCliente) && sizeof($queryCliente)>0){
    die(json_encode(array("success"=>0,"errormsg"=>"Código existente")));  
}
else if(!isset($data['intTpEntidade'])){
    die(json_encode(array("success"=>0,"errormsg"=>"Entidade inválida")));  
}    
else {
    
$encLinhas=$data['linhas'];  
unset($data['Id'],$data['linhas'],$data['intPrintCount']);     
     
$database->insert($sTable, $data);  
$idRegisto = $database->id();
    
if($idRegisto>0){      
    
    ## Atualiza Numerador ####
    $database->update("Tbl_Numeradores", ["intNum_Mes00" => $intNumero],["strAbrevTpDoc" => $strAbrevTpDoc,"strCodSeccao" => $strCodSeccao,"strCodExercicio" =>$strCodExercicio]);

    recursive_unset($encLinhas, "Id"); 
    $database->insert("Mov_Encomenda_Lin", $encLinhas); 
    $idLinhas = $database->id();        
        
    if($idLinhas>0){  
        die(json_encode(array("success"=>1,"message"=>"Encomenda criada","Id"=>$idRegisto)));   
    } else {
        $erroBD=$database->error(); 
        ## Remove Enomenda   
        $database->delete("Mov_Encomenda_Lin", ["strCodSeccao" => $strCodSeccao,"strAbrevTpDoc" =>$strAbrevTpDoc,"strCodExercicio" => $strCodExercicio,"intNumero" => $intNumero]);    
        $database->delete("Mov_Encomenda_Cab", ["Id" => $idRegisto]);    
        ## Reverte Numerador?
        $intNumeroAnt=$intNumero-1;
        $database->update("Tbl_Numeradores", ["intNum_Mes00" => $intNumeroAnt],["strAbrevTpDoc" => $strAbrevTpDoc,"strCodSeccao" => $strCodSeccao,"strCodExercicio" =>$strCodExercicio]);
        die(json_encode(array("success"=>0,"errormsg"=>$erroBD)));       
    }
        
} else {
    die(json_encode(array("success"=>0,"errormsg"=>$database->error())));       
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
    
$queryLinha = $database->select("Mov_Encomenda_Lin", $aLinhas, ["strCodSeccao" => $queryEncomenda[0]['strCodSeccao'],"strAbrevTpDoc" => $queryEncomenda[0]['strAbrevTpDoc'],"strCodExercicio" => $queryEncomenda[0]['strCodExercicio'],"intNumero" => $queryEncomenda[0]['intNumero'],"ORDER" => ["intNumLinha"=>"ASC"]]);	  

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
                $queryLinha = $database->select("Mov_Encomenda_Lin", $aLinhas, [
                    "strCodSeccao" => $aRow['strCodSeccao'],
                    "strAbrevTpDoc" => $aRow['strAbrevTpDoc'],
                    "strCodExercicio" => $aRow['strCodExercicio'],
                    "intNumero" => $aRow['intNumero'],
                    "ORDER" => ["intNumLinha"=>"ASC"]
                ]);	                 
                $row['linhas'] = $queryLinha;
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
    echo json_encode($output );
}
