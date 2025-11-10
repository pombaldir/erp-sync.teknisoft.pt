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
$sTable = "Mov_Venda_Cab";
/*
* Columns
*/
/* 0 - Id, 1 - strCodSeccao, 2 - strAbrevTpDoc, 3 - strCodExercicio, 4 - intNumero, 5 - strNumero, 6 - strNumExterno
7 - dtmData, 8 - strHora, 9 - bitanulado, 10 - bitAnuladoAnt, 11 - intTpEntidade, 12 - intCodEntidade, 13 - intDireccao
14 - fltDescontoCab, 15 - fltDescontoFin, 16 - strCodCondPag, 17 - dtmDataVencimento, 18 - strCodEstadoDoc, 19 - intMercado,
20 - strAbrevSubZona, 21 - strAbrevMoeda, 22 - fltCambio, 23 - intSCPosto, 24 - strSCAbrevTpDoc, 25 - intSCNumero,
26 - strNumRequisicao, 27 - strRota, 28 - strMeioExpedicao, 29 - strLocalCarga, 30 - strLocalDescarga, 31 - dtmDataCarga,
32 - strHoraCarga, 33 - strViatura, 34 - strCodArmazemOriDef, 35 - strCodLocalizacaoOriDef, 36 - strObs, 37 - bitUrgente,
38 - strCVDNumContrib, 39 - strCVDNome, 40 - strCVDMorada, 41 - strCVDCodPostal, 42 - strCVDTelefone, 43 - bitCVDRetIRS,
44 - intCodVendedor, 45 - intTpComissao, 46 - fltComissaoValor, 47 - fltComissaoPercent, 48 - fltComissaoPenaliz,
49 - fltComissaoValorBase, 50 - bitComissaoDispLiq, 51 - dtmComissaoDataDisp, 52 - intTpComissaoDescontos,
53 - strCodRubrica, 54 - bitIvaIncluido, 55 - bitRegimeVenda, 56 - fltTotalPagamentos, 57 - fltInfTotalArtigos,
58 - fltInfTotalLinhas, 59 - fltInfTotalQtd, 60 - fltTotalMercadoriaSIVA, 61 - fltTotalMercadoriaCIVA,
62 - fltTotalDescontosSIVA, 63 - fltTotalDescontosCIVA, 64 - fltTotalDescontosFinSIVA, 65 - fltTotalDescontosFinCIVA,
66 - fltTotalOutrosSIVA, 67 - fltTotalOutrosCIVA, 68 - fltTotalAcertos, 69 - fltTotalIEC,
70 - intIVACodTaxa1, 71 - intIVACodTaxa2, 72 - fltIVATaxa1, 73 - fltIVATaxa2, 74 - fltIVAIncidencia1, 75 - fltIVAIncidencia2,
76 - fltIVAValor1, 77 - fltIVAValor2, 78 - fltTotalIVA, 79 - fltValorIVARegPago, 80 - fltIncidenciaIVARegPago, 81 - fltSubTotal, 
82 - fltIRSTaxa,83 - fltIRSIncidencia, 84 - fltIRSValorRetido, 85 - fltTotal, 86 - fltTroco, 87 - fltValorPago,
88 - fltValorPendente, 89 - intSinal, 90 - bitConversao, 91 - bitConvertido, 92 - bitLetraEmitida,
93 - strAplicacaoOrigem, 94 - strLogin, 95 - strIntraCodEntrega, 96 - strIntraCodPais, 97 - strIntraDescPais,
98 - intIntraModTrans, 99 - strIntraTpTrans, 100 - strIntraCodTrans, 101 - intIntraNatA, 102 - intIntraNatB,
103 - dtmDataAbertura, 104 - dtmDataAlteracao, 105 - UpdateStamp, 106 - strCodSeccaoEncSinal, 107 - strAbrevTpDocEncSinal,
108 - strCodExercicioEncSinal, 109 - intNumeroEncSinal, 110 - strTpMovPagTroco, 111 - fltSaldoCC, 112 - fltSaldoCCFinal,
113 - fltValorReembolso, 114 - fltValorParaCC, 115 - intNumValeEmitido, 116 - intNumValeDescontado,
117 - strCodSeccaoValeDescontado, 118 - strTpMovPagVale, 119 - bitArrImpostos, 120 - strForteAbrevMoeda,
121 - fltForteCambio, 122 - bitForteDifCambial, 123 - bitNaoConvertivel, 124 - bitEVLiquidoComEcovalor,
125 - fltEVTotalPilhas, 126 - fltEVTotalPneus, 127 - fltEVTotalOleos, 128 - fltEVTotalREEE,
129 - fltEVTotalDAutor, 130 - fltEVTotalNaoDefinido, 131 - fltEVTotal, 132 - bitEVEntidadeIsenta,
133 - strCrmGuid, 134 - intFEEstadoActual, 135 - dtmFEDataHoraEstadoActual, 136 - strFEDadosRecep,
137 - intNumeroMesaOuCartao, 138 - strCodSectorGourmet, 139 - strCodCartaoCliente, 140 - fltSinalPagoEncomenda,
141 - intNumeroPessoas, 142 - dtmDataEntrega, 143 - strAbrevMoedaPagTroco, 144 - fltCambioPagTroco, 145 - fltTrocoMoedaDoc,
146 - fltDiferencaArred, 147 - bitFolhaServico, 148 - fltTotalValorValeDescontoSIVA, 149 - fltTotalValorValeDescontoCIVA,
150 - intNumValeDescontoEmitido, 151 - bitSafeMode, 152 - bitCartaoConsumo, 153 - strFENumEtiqueta,
154 - bitEfectuaInversaoIVA, 155 - bitExigivelIVA, 156 - fltValorIVAPendente, 157 - strDescritivoPagamento,
158 - intCodCatEntidade, 159 - strCVDLocalidade, 160 - bitDocChequeOferta, 161 - strHash, 162 - intHashControl,
163 - bitDocCarregamentoCartao, 164 - strCodSecContrato, 165 - strCodTpContrato, 166 - strCodExercContrato,
167 - intNumeroContrato, 168 - bitProcessarContrato, 169 - bitDocOrigemWEB, 170 - intLinhaPreco,
171 - strMoradaLin2, 172 - dtmDataValidadeContrato, 173 - intEstadoContrato, 174 - dtmDataEstado,
175 - strObsEstado, 176 - intNumProcessamento, 177 - intCodEstabHotel, 178 - intNumQuartoHotel,
179 - intLinhaHospede, 180 - strMotivoIsencao*/
$aColumns = array("Id","strCodSeccao" ,"strAbrevTpDoc" ,"strCodExercicio" , "intNumero" ,"strNumero" ,"strNumExterno"
 ,"dtmData" ,"strHora" ,"bitAnulado" ,"intTpEntidade" ,"intCodEntidade" ,"intDireccao","fltDescontoCab"
 ,"fltDescontoFin" ,"strCodCondPag" ,"dtmDataVencimento" ,"strCodEstadoDoc" ,"intMercado","strAbrevSubZona" ,"strAbrevMoeda"
 ,"fltCambio","strMeioExpedicao" ,"strLocalCarga" ,"strLocalDescarga" ,"dtmDataCarga" ,"strHoraCarga" ,"strViatura"
 ,"strObs" ,"strCVDNumContrib" ,"strCVDNome" ,"strCVDMorada" ,"strCVDCodPostal" ,"strCVDTelefone","strCVDLocalidade" , "strCVDEmail"
 ,"intTpComissao" ,"fltComissaoValor" ,"fltComissaoPercent" ,"fltComissaoPenaliz","fltComissaoValorBase" ,"dtmComissaoDataDisp" ,"intTpComissaoDescontos"
 ,"strCodRubrica" ,"fltTotalPagamentos" , "fltInfTotalArtigos","fltInfTotalLinhas" ,"fltInfTotalQtd" ,"fltTotalMercadoriaSIVA" ,"fltTotalMercadoriaCIVA"
 ,"fltTotalDescontosSIVA" ,"fltTotalDescontosCIVA" ,"fltTotalDescontosFinSIVA" ,"fltTotalDescontosFinCIVA","fltTotalOutrosSIVA" ,"fltTotalOutrosCIVA" ,"fltTotalAcertos"
 ,"fltTotalIEC" ,"intIVACodTaxa1" ,"intIVACodTaxa2" ,"intIVACodTaxa3" ,"fltIVATaxa1" ,"fltIVATaxa2" ,"fltIVATaxa3"
 ,"fltIVAIncidencia1" ,"fltIVAIncidencia2" ,"fltIVAValor1" ,"fltIVAValor2" ,"fltIVAValor3" ,"fltTotalIVA" ,"fltValorIVARegPago"
 ,"fltIncidenciaIVARegPago" ,"fltSubTotal" ,"fltIRSTaxa" ,"fltIRSIncidencia" ,"fltIRSValorRetido" ,"fltTotal" ,"fltTroco"
 ,"fltValorPago" ,"fltValorPendente" ,"intSinal" ,"strAplicacaoOrigem" ,"strLogin" ,"dtmDataAbertura" ,"dtmDataAlteracao"
 ,"fltSinalPagoEncomenda" ,"intNumeroPessoas" ,"dtmDataEntrega" ,"strAbrevMoedaPagTroco","intCodVendedor"
 ,"fltCambioPagTroco" ,"fltTrocoMoedaDoc" ,"fltDiferencaArred" ,"fltTotalValorValeDescontoSIVA" ,"fltTotalValorValeDescontoCIVA" ,"intNumValeDescontoEmitido","strFENumEtiqueta"
 ,"fltValorIVAPendente" ,"strDescritivoPagamento" ,"intCodCatEntidade" ,"strHash" ,"intHashControl" ,"strCodSecContrato"
 ,"strCodTpContrato" ,"strCodExercContrato" ,"intNumeroContrato" ,"intLinhaPreco" ,"strMoradaLin2" ,"dtmDataValidadeContrato" ,"intEstadoContrato" 
 ,"dtmDataEstado" ,"strObsEstado" ,"intNumProcessamento" ,"intCodEstabHotel" ,"intNumQuartoHotel" ,"intLinhaHospede" ,"strMotivoIsencao");

/*
0 - strCodSeccao, 1 - strAbrevTpDoc, 2 - strCodExercicio, 3 - intNumero, 4 - intNumLinha, 5 - intTpLinha, 6 - strCodArmazem,
7 - strCodLocalizacao, 8 - strCodArtigo, 9 - strDescArtigo, 10 - strCodLote, 11 - intEdicao, 12 - fltQuantidade, 13 - fltCustoUnitario,
14 - fltPrecoUnitario, x - fltCustoTotal, x - bitNaoMovStk, x - bitConversao, x - intConversaoIdNum, x - intTpMargem, x - fltMargemMinima,
21 - strCodClassMovStk, x - intTipoAssociacao, x - intLinhaAssociacao, x - intLinhaReferencia, x - bitSujeitoRetIRS, x - strFormula, x - fltMedidaQtd,
28 - fltMedida1, x - fltMedida2, x - fltMedida3, x - bitTransferencia,x - strCodArmazemOri, x - strCodLocalizacaoOri, x - fltDesconto1,
35 - fltDesconto2, x - fltDesconto3, x - fltDescontoValorUnit, x - fltDescontoValor, x - strDocumOrigem, x - fltValorLiquido, x - intCodTaxaIVA,
*/
$aLinhas=array("strCodSeccao" ,"strAbrevTpDoc" ,"strCodExercicio" ,"intNumero" ,"intNumLinha" ,"intTpLinha" ,"strCodArmazem"
,"strCodLocalizacao" ,"strCodArtigo" ,"strDescArtigo", "strCodLote" ,"intEdicao" , "fltQuantidade","fltCustoUnitario"
,"fltPrecoUnitario","fltCustoTotal" ,"intConversaoIdNum" ,"intTpMargem" ,"fltMargemMinima"
,"strCodClassMovStk" ,"intTipoAssociacao" ,"intLinhaAssociacao" ,"intLinhaReferencia" ,"strFormula" ,"fltMedidaQtd"
,"fltMedida1" ,"fltMedida2" ,"fltMedida3" ,"strCodArmazemOri" ,"strCodLocalizacaoOri" ,"fltDesconto1"
,"fltDesconto2" ,"fltDesconto3" ,"fltDescontoValorUnit" ,"fltDescontoValor" ,"strDocumOrigem" ,"fltValorLiquido" ,"intCodTaxaIVA");
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    /*if($_SERVER['REQUEST_METHOD'] === 'POST'){
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
            $database->insert("Mov_Venda_Lin", $encLinhas); 
            $idLinhas = $database->id();        
                
            if($idLinhas>0){  
                die(json_encode(array("success"=>1,"message"=>"Venda criada","Id"=>$idRegisto)));   
            } else {
                $erroBD=$database->error(); 
                ## Remove Enomenda   
                $database->delete("Mov_Venda_Lin", ["strCodSeccao" => $strCodSeccao,"strAbrevTpDoc" =>$strAbrevTpDoc,"strCodExercicio" => $strCodExercicio,"intNumero" => $intNumero]);    
                $database->delete("Mov_Venda_Cab", ["Id" => $idRegisto]);    
                ## Reverte Numerador?
                $intNumeroAnt=$intNumero-1;
                $database->update("Tbl_Numeradores", ["intNum_Mes00" => $intNumeroAnt],["strAbrevTpDoc" => $strAbrevTpDoc,"strCodSeccao" => $strCodSeccao,"strCodExercicio" =>$strCodExercicio]);
                die(json_encode(array("success"=>0,"errormsg"=>$erroBD)));       
            }
                
        } else {
            die(json_encode(array("success"=>0,"errormsg"=>$database->error())));       
        }
        
        } 
            
    }*/
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    if($accao=="getDetail" && isset($_GET['num']) && $_SERVER['REQUEST_METHOD'] === 'GET'){
        $numVenda=$_GET['num'];
        $queryVenda = $database->select($sTable, $aColumns, ["Id" => $numVenda]);	
            
        if(sizeof($queryVenda)==0) {  
        die(json_encode(array("success"=>0,"errormsg"=>"registo inexistente")));
            
        } else {       
            
        $queryLinha = $database->select("Mov_Venda_Lin", $aLinhas, ["strCodSeccao" => $queryVenda[0]['strCodSeccao'],"strAbrevTpDoc" => $queryVenda[0]['strAbrevTpDoc'],
        "strCodExercicio" => $queryVenda[0]['strCodExercicio'],"intNumero" => $queryVenda[0]['intNumero'],"ORDER" => ["intNumLinha"=>"ASC"]]);	  
        
            $output = array_merge($queryVenda[0],array("linhas"=>$queryLinha)); 
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
         
        
        //die(json_encode($_GET['searchField']));
        $sWhere = "";
        if ( isset($_GET['q']) && $_GET['q'] != "" && in_array($_GET['searchField'],$aColumns))
        {
            //die(json_encode($_GET['q']));
            $termo=addslashes( $_GET['q']);
            if ( isset($_GET['srchType']) && $_GET['srchType'] != ""){
                switch ($_GET['srchType']) {
                    case "equal":
                        $qsrchType=" = '".$termo."'";
                        break;
                    case "not-equal":
                        $qsrchType=" <> '".$termo."'";
                        break;
                    case "endswith":
                        $qsrchType=" LIKE '%".$termo."'";
                    case "startswith":
                        $qsrchType=" LIKE '".$termo."%'";                
                    break;
                    default:
                        $qsrchType=" = '".$termo."'";
                }        
            } else {
                    $qsrchType=" = '".$termo."'";
            }
            
            $sWhere .= "WHERE (";
            $sWhere .= "".$_GET['searchField']." $qsrchType";	
            $sWhere .= ') ';
        }	 

        if ( isset($_GET['docType']) && $_GET['docType'] != ""){
            if ( $sWhere == "" )   {     $sWhere = "WHERE ";     }   else   {   $sWhere .= " AND ";    }
           $sWhere .= " strAbrevTpDoc IN  ('". implode('\',\'', $_GET['docType']) ."') ";
        }
        

        
        //die(print_r($sWhere));
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
       //die(json_encode($_GET));
       //die(json_encode($database->log()));
      
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
                    $queryLinha = $database->select("Mov_Venda_Lin", $aLinhas, [
                        "strCodSeccao" => $aRow['strCodSeccao'],
                        "strAbrevTpDoc" => $aRow['strAbrevTpDoc'],
                        "strCodExercicio" => $aRow['strCodExercicio'],
                        "intNumero" => $aRow['intNumero'],
                        "ORDER" => ["intNumLinha"=>"ASC"]
                    ]);	                 
                    $row['linhas'] = $queryLinha;
                }   
                
                else if ( $aColumns[$i] == 'dtmData' )
                {
                    $row[$aColumns[$i]] = array_shift(explode(' ', $aRow[ $aColumns[$i]]));
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
    