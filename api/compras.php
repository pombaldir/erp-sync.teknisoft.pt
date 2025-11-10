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
$sTable = "Mov_Compra_Cab";
/*
* Columns
*/

$aColumns = array("strCodSeccao","strAbrevTpDoc","strCodExercicio","intNumero","strNumero","strNumExterno","dtmData","bitAnulado","intTpEntidade","intCodEntidade","intCompraEncargo","bitDocEncargos","fltDescontoCab","fltDescontoFin","
                         strCodCondPag","dtmDataVencimento","strCodEstadoDoc","intMercado","strAbrevSubZona","strAbrevMoeda","fltCambio","strNumRequisicao","strMeioExpedicao","strLocalCarga","strLocalDescarga","dtmDataCarga","strHoraCarga","
                         strViatura","strObs","bitUrgente","strFCDNumContrib","strFCDNome","strFCDMorada","strFCDCodPostal","strFCDTelefone","bitFCDRetIRS","strCodRubrica","bitIvaIncluido","fltTotalPagamentos","fltInfTotalArtigos","fltInfTotalLinhas","
                         fltInfTotalQtd","fltEncargosValorSIVA","fltEncargosValorCIVA","fltEncargosDescontosSIVA","fltEncargosDescontosCIVA","fltEncargosIVA","fltEncargosIRS","fltEncargosIRSIncid","fltEncargosTotal","fltEncargosPorDistribuir","
                         fltEncargosDistribuido","fltTotalMercadoriaSIVA","fltTotalMercadoriaCIVA","fltTotalDescontosSIVA","fltTotalDescontosCIVA","fltTotalDescontosFinSIVA","fltTotalDescontosFinCIVA","fltTotalOutrosSIVA","fltTotalOutrosCIVA","fltTotalAcertos","
                         intIVACodTaxa1","intIVACodTaxa2","intIVACodTaxa3","intIVACodTaxa4","intIVACodTaxa5","intIVACodTaxa6","fltIVATaxa1","fltIVATaxa2","fltIVATaxa3","fltIVATaxa4","fltIVATaxa5","fltIVATaxa6","fltIVAIncidencia1","fltIVAIncidencia2","fltIVAIncidencia3,
                          fltIVAIncidencia4","fltIVAIncidencia5","fltIVAIncidencia6","fltIVAValor1","fltIVAValor2","fltIVAValor3","fltIVAValor4","fltIVAValor5","fltIVAValor6","fltTotalIVA","fltSubTotal","fltIRSTaxa","fltIRSIncidencia","fltIRSValorRetido","fltTotal","strAplicacaoOrigem","
                         strLogin","fltValorPago","fltValorPendente","intSinal","bitConversao","bitConvertido","dtmDataAbertura","dtmDataAlteracao","UpdateStamp","Id","fltValorIVARegPago","fltIncidenciaIVARegPago","fltIVAValorND1","fltIVAValorND2","fltIVAValorND3","
                         fltIVAValorND4","fltIVAValorND5","fltIVAValorND6","fltTotalIVAND","strIntraCodEntrega","strIntraCodPais","strIntraDescPais","intIntraModTrans","strIntraTpTrans","strIntraCodTrans","intIntraNatA","intIntraNatB","intDireccao","bitArrImpostos","
                         strForteAbrevMoeda","fltForteCambio","bitForteDifCambial","bitEVLiquidoComEcovalor","fltEVTotalPilhas","fltEVTotalPneus","fltEVTotalOleos","fltEVTotalREEE","fltEVTotalDAutor","fltEVTotalNaoDefinido","fltEVTotal","bitEVEntidadeIsenta","
                         strFEDadosRecep","bitEfectuaInversaoIVA","bitExigivelIVA","fltValorIVAPendente","intCodCatEntidade","strFCDLocalidade","strHash","intHashControl","bitUpdateIntegrador","bitDocOrigemWEB","dtmDataEstado","strObsEstado","
                         dtmDataAlteracaoEstado","strLoginEstado","strATDocCodeID","strLoginCriacao","intATDocCodeStatus","strATDocCodeStatus","bitCargaEnderecoAdicional","strCargaMorada","strCargaLocalidade","strCargaCodPostal","strCargaCodPais","
                         bitDescargaEnderecoAdicional","strDescargaMorada","strDescargaLocalidade","strDescargaCodPostal","strDescargaCodPais","bitAddYearIdDocAT","strSaftDocNO","dtmDataLancamento","dtmDataMovCTE","dtmDataMovSTK","
                         bitIvaCaixaVencido","bitIvaCaixaVencidoDC","fltDocumentSumTaxes","fltDocumentSumRetentions","intTaxTypeCalculation","fltValorPagoCmp","bitGenerateAutomaticSettlements","bitNotCalculateRetentions","fltTotalToPay","
                         fltDocSumTaxesExpense","fltDocSumRetentionsExpense","bitNotCalculateTotals","intPrintCount","strMoradaLin2","strMotivoIsencao","str1MotivoIsencao","str1CodOficialMotIVAIsencao","strFCDEmail","bitPartiallyIntegrated","strATCUD","
                         strQRCODE","dtmDataExpFactAT","bitIsCancellationOrRectification","bitAnuladoAnt","bitCostsByExitCosting","bitCompensationAllLines","bitNotPrintsPricesAndTaxes","fltRetentionReductedOutstandingValue","
                         fltRetentionReductedOutstandingValuePend","bitFullyCompensatedSourceDocs","strStateTaxNumber","strOperationType");
$columnLookup = array_flip($aColumns);

                         
$aLinhas=array("strCodSeccao","strAbrevTpDoc","strCodExercicio","intNumero","intNumLinha","intTpLinha","strCodArmazem","strCodLocalizacao","strCodArtigo","strDescArtigo","strCodLote","fltQuantidade","fltPrecoUnitario","fltCustoUnitario","
                         fltValorEncargosInt","fltValorEncargosExt","bitNaoMovStk","bitConversao","intNumLinhaEncargo","strCodClassMovStk","intTipoAssociacao","intLinhaAssociacao","intLinhaReferencia","bitSujeitoRetIRS","strFormula","fltMedidaQtd","
                         fltMedida1","fltMedida2","fltMedida3","fltDesconto1","fltDesconto2","fltDesconto3","fltDescontoValor","strDocumOrigem","fltValorLiquido","intCodTaxaIVA","fltTaxaIVA","fltValorMercadoriaCIVA","fltValorMercadoriaSIVA","fltValorDescontosCIVA","
                         fltValorDescontosSIVA","fltValorDescontosFinCIVA","fltValorDescontosFinSIVA","fltValorAPagar","id","fltPercIvaND","intConversaoIdNum","fltDescontoValorUnit","CA_Campo01","CA_Campo02","CA_Campo03","CA_Campo04","CA_Campo05","
                         CA_Campo06","CA_Campo07","CA_Campo08","CA_Campo09","CA_Campo10","intIntraCodRegiao","intEdicao","dtmDataDevol","strSemanaDevol","strIntraCodPautal","fltIntraPesoLiquido","bitEVSujeito","fltEVUnitPilhas","fltEVUnitPneus","
                         fltEVUnitOleos","fltEVUnitREEE","fltEVUnitDAutor","fltEVParcialDAutor","fltEVPercDAutor","fltEVUnitNaoDefinido","fltEVParcialNaoDefinido","fltEVPercNaoDefinido","fltEVTotal","strintracodpaisorigem","fltQuantidadeSatisf","fltPesoLiquidoTotal","
                         strCodMarca","strCodModelo","strCodVersao","strCodNivel","strCodFichaRepart","strCodConta","strCodContaReflDeb","strCodContaReflCre","intIdDuplicacao","fltGOPQtdAtribuida","intNumLinhaOrig","strDocAQueReporta","
                         intNumeroEtiquetas","strCodProjecto","fltCustototal","intCampaign","bitNotSubjHeaderDiscount","strCodOficialMotIVAIsencao","strMotivoIsencaoIVA","intProjectLine","fltNonDeductibleTaxCostValue","fltTotalTaxValueOfIncidenceLiquid","
                         fltTotalTaxValueOfIncidenceLiquidUnit","intConversaoIdNumDst","fltPendingQuantity","intLineNumberIntegr","fltTotalCompensado","fltValorAPagarAcerto","intAutDestinationTpDoc","strAutCarKey","strAutSectionCode","strAutDocType","
                         strAutFiscalYear","intAutDocNumber","intAutLineType","intAutIdLine","intAutExtraCode","GuidAutIdLine","bitProcessVehicleLine","fltOperationalCostValueStd");
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
        if (!is_array($data)) {
            $data = $_POST;
        }

        if (!is_array($data)) {
            die(json_encode(array("success"=>0,"errormsg"=>"Payload inválido.")));
        }

        $strAbrevTpDoc = isset($data['strAbrevTpDoc']) ? trim($data['strAbrevTpDoc']) : '';
        $strCodSeccao  = isset($data['strCodSeccao']) ? trim($data['strCodSeccao']) : '';

        if ($strAbrevTpDoc === '' || $strCodSeccao === '') {
            die(json_encode(array("success"=>0,"errormsg"=>"Secção e tipo de documento são obrigatórios.")));
        }

        $documents = array();
        if (isset($data['documents']) && is_array($data['documents'])) {
            $documents = $data['documents'];
        } elseif (isset($data['document']) && is_array($data['document'])) {
            $documents = array($data['document']);
        } elseif (isset($data['field_G'])) {
            $documents = array($data);
        }

        if (empty($documents)) {
            die(json_encode(array("success"=>0,"errormsg"=>"Sem documentos para processar.")));
        }

        $recList = array("novo" => array(), "exist" => array(), "error" => array());

        foreach ($documents as $doc) {
            if (!is_array($doc)) {
                $recList['error'][] = "Formato de documento inválido.";
                continue;
            }

            $fieldG = isset($doc['field_G']) ? trim($doc['field_G']) : '';
            if ($fieldG === '') {
                $recList['error'][] = "Documento sem número externo.";
                continue;
            }

            if ($database->has($sTable, ['strNumExterno' => $fieldG])) {
                $recList['exist'][] = $fieldG;
                continue;
            }

            $fieldF = isset($doc['field_F']) ? $doc['field_F'] : null;
            if (empty($fieldF)) {
                $recList['error'][] = "Documento $fieldG sem data.";
                continue;
            }

            $docTimestamp = strtotime($fieldF);
            if ($docTimestamp === false) {
                $recList['error'][] = "Data inválida para o documento $fieldG.";
                continue;
            }
            $docDate = date('Y-m-d', $docTimestamp);

            $strCodExercicio = isset($doc['strCodExercicio']) && $doc['strCodExercicio'] !== ''
                ? $doc['strCodExercicio']
                : (isset($data['strCodExercicio']) && $data['strCodExercicio'] !== '' ? $data['strCodExercicio'] : getExercicioByDate($fieldF));

            if (!$strCodExercicio) {
                $recList['error'][] = "Não foi possível determinar o exercício para o documento $fieldG.";
                continue;
            }

            $intNumero = null;
            $strNumero = null;
            $numeroInfo = null;

            try {
                if (isset($doc['intNumero']) && $doc['intNumero'] !== '') {
                    $intNumero = (int) $doc['intNumero'];
                    $strNumero = isset($doc['strNumero']) && $doc['strNumero'] !== '' ? trim($doc['strNumero']) : null;
                } elseif (isset($data['intNumero']) && $data['intNumero'] !== '') {
                    $intNumero = (int) $data['intNumero'];
                    $strNumero = isset($data['strNumero']) && $data['strNumero'] !== '' ? trim($data['strNumero']) : null;
                } else {
                    $numeroInfo = getNextDocumentNumerador($strCodExercicio, $strCodSeccao, $strAbrevTpDoc);
                    $intNumero = $numeroInfo['intNumero'];
                    $strNumero = $numeroInfo['strNumero']; 
                }
            } catch (Exception $e) {
                $recList['error'][] = "Erro ao obter numerador para o documento $fieldG: " . $e->getMessage();
                continue;
            }
           
            if ($strNumero === null || $strNumero === '') {
                if (is_array($numeroInfo) && isset($numeroInfo['strNumero']) && $numeroInfo['strNumero'] !== '') {
                    $strNumero = $numeroInfo['strNumero'];
                } else {
                    $formato = $database->get("Tbl_Numeradores", "strFormato", [
                        "strCodExercicio" => $strCodExercicio,
                        "strCodSeccao"    => $strCodSeccao,
                        "strAbrevTpDoc"   => $strAbrevTpDoc,
                        "intTpNumerador"  => 1
                    ]);
                    $strNumero = formatDocumentNumberByPattern($formato, $strCodExercicio, $intNumero, $strCodSeccao, $strAbrevTpDoc);
                
                }
            }


            $intTpEntidade = (isset($data['intTpEntidade']) && $data['intTpEntidade'] !== '' ? (int) $data['intTpEntidade'] : null);
            $intCodEntidade = (isset($data['intCodEntidade']) && $data['intCodEntidade'] !== '' ? (int) $data['intCodEntidade'] : null);

            $strNumContrib = isset($doc['field_A']) ? trim($doc['field_A']) : '';
            $fornecedorData = array();
            $strNumContrib = '500108668';
            if ($strNumContrib !== '') {
                $entidade = $database->select(
                    "Tbl_Fornecedores",
                    ["intCodigo","strNome","strMorada_lin1","strMorada_lin2","strLocalidade","strPostal","strTelefone","strNumContrib","strAbrevSubZona"],
                    ["strNumContrib" => $strNumContrib]
                );
 
                if (is_array($entidade) && !empty($entidade)) {
                    $intTpEntidade  = 2; // Fornecedor
                    $intCodEntidade = (int) $entidade[0]['intCodigo'];
                    $fornecedorData = array(
                        "intCodEntidade"   => $intCodEntidade,
                        "strFCDNumContrib" => $strNumContrib,
                        "strFCDNome"       => $entidade[0]['strNome'],
                        "strFCDMorada"     => trim($entidade[0]['strMorada_lin1'] . ' ' . $entidade[0]['strMorada_lin2']),
                        "strFCDCodPostal"  => $entidade[0]['strPostal'],
                        "strFCDTelefone"   => $entidade[0]['strTelefone'],
                        "strFCDLocalidade"   => $entidade[0]['strLocalidade'],
                        "strAbrevSubZona"  => $entidade[0]['strAbrevSubZona']
                    );
                }
            }

            $strAbrevMoeda = (isset($data['strAbrevMoeda']) && $data['strAbrevMoeda'] !== '' ? $data['strAbrevMoeda'] : 'EUR');
            $normalizeDecimal = static function ($value) {
                if ($value === null || $value === '') {
                    return null;
                }
                if (is_string($value)) {
                    $value = str_replace(["\xc2\xa0", ' '], '', $value);
                    $value = preg_replace('/[^\d,\.\-]/', '', $value);
                    if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
                        $value = str_replace('.', '', $value);
                    }
                    $value = str_replace(',', '.', $value);
                }
                return is_numeric($value) ? (float) $value : null;
            };

            $fltInfTotalLinhas = isset($data['fltInfTotalLinhas']) && $data['fltInfTotalLinhas'] !== '' ? (float) $data['fltInfTotalLinhas'] : 1.0;
            $fltInfTotalQtd = isset($data['fltInfTotalQtd']) && $data['fltInfTotalQtd'] !== '' ? (float) $data['fltInfTotalQtd'] : 1.0;
            $fltInfTotalArtigos = isset($data['fltInfTotalArtigos']) && $data['fltInfTotalArtigos'] !== '' ? (float) $data['fltInfTotalArtigos'] : 1.0;
            $fltTotalMercadoriaSIVA = 0.0;
            $fltTotalMercadoriaCIVA = $normalizeDecimal($doc['field_I'] ?? null);
            $fltTotalMercadoriaCIVA = $fltTotalMercadoriaCIVA !== null ? $fltTotalMercadoriaCIVA : 0.0;
            

            $qrEnforcedValues = array();

            $ivaCodeSlots = array_fill(1, 6, -1);
            $ivaValueSlots = array_fill(1, 6, 0.0);
            $ivaBaseSlots = array_fill(1, 6, 0.0);
            $ivaRateSlots = array_fill(1, 6, -1.0);

            $ivaMappings = [
                ['rate' => 6,  'baseField' => 'field_I5', 'taxField' => 'field_I6'],
                ['rate' => 13, 'baseField' => 'field_I3', 'taxField' => 'field_I4'],
                ['rate' => 23, 'baseField' => 'field_I7', 'taxField' => 'field_I8'],
            ];

            $slot = 1;
            foreach ($ivaMappings as $mapping) {
                if ($slot > 6) {
                    break;
                }
                $baseValue = $normalizeDecimal($doc[$mapping['baseField']] ?? null);
                $taxValue = $normalizeDecimal($doc[$mapping['taxField']] ?? null);
                $baseValue = $baseValue !== null ? $baseValue : 0.0;
                $taxValue = $taxValue !== null ? $taxValue : 0.0;

                if ($baseValue === 0.0 && $taxValue === 0.0) {
                    continue;
                }
 
                $computedRate = null;
                if ($baseValue > 0.0) {
                    $computedRate = ($taxValue / $baseValue) * 100;
                } elseif ($taxValue > 0.0) {
                    $computedRate = 0.0;
                }

                $candidateRates = array();
                if ($computedRate !== null) {
                    $candidateRates[] = round($computedRate, 4);
                    $candidateRates[] = round($computedRate, 2);
                    $candidateRates[] = round($computedRate, 1);
                    $candidateRates[] = round($computedRate, 0);
                }
                $candidateRates[] = (float) $mapping['rate'];
                $candidateRates = array_values(array_unique(array_filter($candidateRates, static function ($item) {
                    return $item !== null;
                })));

                $selectedRate = -1.0;
                $taxCode = null;
                foreach ($candidateRates as $candidate) {
                    $taxCode = getIvaCodeByTax($candidate);
                    if ($taxCode !== null) {
                        $selectedRate = (float) $candidate;
                        break;
                    }
                }
                if ($selectedRate === -1.0 && !empty($candidateRates)) {
                    $selectedRate = (float) $candidateRates[0];
                }

                $ivaRateSlots[$slot] = $selectedRate;
                $ivaBaseSlots[$slot] = $baseValue;
                $ivaValueSlots[$slot] = $taxValue;
                if ($taxCode !== null) {
                    $ivaCodeSlots[$slot] = (int) $taxCode;
                }
                $slot++;
            }

            $sumBases = array_sum($ivaBaseSlots);
            if ($sumBases > 0) {
                $fltTotalMercadoriaSIVA = $sumBases;
            }

            $fltTotalIVA = $normalizeDecimal($doc['field_N'] ?? null);
            $fltTotalIVA = $fltTotalIVA !== null ? $fltTotalIVA : 0.0;

            $totalDocumento = $normalizeDecimal($doc['field_O'] ?? null);
            $fltSubTotal = isset($data['fltSubTotal']) && $data['fltSubTotal'] !== '' ? (float) $data['fltSubTotal'] : ($fltTotalMercadoriaSIVA !== 0.0 ? $fltTotalMercadoriaSIVA : 1.0);
            if ($totalDocumento !== null) {
                if ($fltTotalMercadoriaCIVA === 0.0) {
                    $fltTotalMercadoriaCIVA = $totalDocumento;
                }
                $qrEnforcedValues['fltTotal'] = $totalDocumento;
                $fltSubTotal = $totalDocumento;
            }
          

            $paymentTotal = null;
            if (isset($doc['field_S']) && $doc['field_S'] !== '') {
                $segments = array_filter(explode(';', $doc['field_S']));
                $accumulator = 0.0;
                $hasAmount = false;
                foreach ($segments as $segment) {
                    $amount = $normalizeDecimal($segment);
                    if ($amount !== null) {
                        $accumulator += $amount;
                        $hasAmount = true;
                    }
                }
                if ($hasAmount) {
                    $paymentTotal = $accumulator;
                }
            }

            if ($paymentTotal !== null) {
                $qrEnforcedValues['fltTotalPagamentos'] = $paymentTotal;
                $qrEnforcedValues['fltValorPago'] = $paymentTotal;
                if ($totalDocumento !== null) {
                    $qrEnforcedValues['fltValorPendente'] = max($totalDocumento - $paymentTotal, 0.0);
                }
            } elseif ($totalDocumento !== null) {
                $qrEnforcedValues['fltValorPago'] = $totalDocumento;
                $qrEnforcedValues['fltValorPendente'] = 0.0;
            }

             
            $hashValue = $doc['field_H'] ?? ($doc['field_J'] ?? null);
            if ($hashValue !== null && $hashValue !== '') {
                //$qrEnforcedValues['strHash'] = $hashValue;
            }
            if (isset($doc['field_K']) && $doc['field_K'] !== '') {
                $qrEnforcedValues['strATCUD'] = $doc['field_K'];
            }
            if (isset($doc['field_Q']) && $doc['field_Q'] !== '') {
                $qrEnforcedValues['strATDocCodeID'] = $doc['field_Q'];
            }
            if (isset($doc['field_R']) && $doc['field_R'] !== '') {
                $hashControl = $normalizeDecimal($doc['field_R']);
                if ($hashControl !== null) {
                    $qrEnforcedValues['intHashControl'] = (int) round($hashControl);
                } else {
                    $qrEnforcedValues['strATDocCodeStatus'] = $doc['field_R'];
                }
            }
            if (isset($doc['field_T']) && $doc['field_T'] !== '') {
                $qrEnforcedValues['strQRCODE'] = (string) $doc['field_T'];
            }
            if ($totalDocumento !== null) {
                $qrEnforcedValues['fltTotalToPay'] = $totalDocumento;
            }

            $currentDateTime = date('Y-m-d H:i:s');
            $intSinalValue = isset($data['intSinal']) && $data['intSinal'] !== '' ? (int) $data['intSinal'] : 1;

            $dadosCab = array(
                "strCodExercicio" => $strCodExercicio,
                "strCodSeccao"    => $strCodSeccao,
                "strAbrevTpDoc"   => $strAbrevTpDoc,
                "intNumero"       => $intNumero,
                "strNumero"       => $strNumero,
                "strNumExterno"   => $fieldG,
                "dtmData"         => $docDate,
                "dtmDataVencimento" => $docDate,
                "strAbrevMoeda"   => $strAbrevMoeda,
                "fltCambio"       => 1,
                "fltInfTotalLinhas" => $fltInfTotalLinhas,
                "fltInfTotalQtd" => $fltInfTotalQtd,
                "fltInfTotalArtigos" => $fltInfTotalArtigos,
                "fltTotalMercadoriaSIVA" => $fltTotalMercadoriaSIVA,
                "fltTotalMercadoriaCIVA" => $fltTotalMercadoriaCIVA,
                "fltTotalIVA" => $fltTotalIVA,
                "fltSubTotal" => $fltSubTotal,
                "fltIVATaxa1" => $ivaRateSlots[1],
                "fltIVATaxa2" => $ivaRateSlots[2],
                "fltIVATaxa3" => $ivaRateSlots[3],
                "fltIVATaxa4" => $ivaRateSlots[4],
                "fltIVATaxa5" => $ivaRateSlots[5],
                "fltIVATaxa6" => $ivaRateSlots[6],
                "fltIVAIncidencia1" => $ivaBaseSlots[1],
                "fltIVAIncidencia2" => $ivaBaseSlots[2],
                "fltIVAIncidencia3" => $ivaBaseSlots[3],
                "fltIVAIncidencia4" => $ivaBaseSlots[4],
                "fltIVAIncidencia5" => $ivaBaseSlots[5],
                "fltIVAIncidencia6" => $ivaBaseSlots[6],
                "intIVACodTaxa1" => $ivaCodeSlots[1],
                "intIVACodTaxa2" => $ivaCodeSlots[2],
                "intIVACodTaxa3" => $ivaCodeSlots[3],
                "intIVACodTaxa4" => $ivaCodeSlots[4],
                "intIVACodTaxa5" => $ivaCodeSlots[5],
                "intIVACodTaxa6" => $ivaCodeSlots[6],
                "fltIVAValor1" => $ivaValueSlots[1],
                "fltIVAValor2" => $ivaValueSlots[2],
                "fltIVAValor3" => $ivaValueSlots[3],
                "fltIVAValor4" => $ivaValueSlots[4],
                "fltIVAValor5" => $ivaValueSlots[5],
                "fltIVAValor6" => $ivaValueSlots[6],
                "dtmDataAbertura" => $currentDateTime,
                "dtmDataAlteracao" => $currentDateTime,
                "dtmDataLancamento" => $currentDateTime,
                "dtmDataMovCTE" => $currentDateTime,
                "dtmDataMovSTK" => $currentDateTime,
                "intTpEntidade"   => $intTpEntidade,
                "intCodEntidade"  => $intCodEntidade,
                "intSinal"        => $intSinalValue
            );

          

            foreach ($qrEnforcedValues as $column => $value) {
                if (isset($columnLookup[$column])) {
                    $dadosCab[$column] = $value;
                }
            }

            $extractCabColumnValues = static function (array $source) use ($columnLookup) {
                $values = array_intersect_key($source, $columnLookup);
                return array_filter($values, static function ($value) {
                    return $value !== null && $value !== '';
                });
            };

              

            $additionalFromData = $extractCabColumnValues($data);
            $additionalFromDoc = $extractCabColumnValues($doc);

            foreach ($dadosCab as $key => $value) {
                if ($value !== null && $value !== '') {
                    unset($additionalFromData[$key], $additionalFromDoc[$key]);
                }
            }

            unset(
                $additionalFromData['Id'],
                $additionalFromDoc['Id'],
                $additionalFromData['UpdateStamp'],
                $additionalFromDoc['UpdateStamp']
            );

            $dadosCab = array_merge($dadosCab, $additionalFromData, $additionalFromDoc, $fornecedorData);

            $dadosCab['fltCambio'] = 1;
            $dadosCab['dtmDataVencimento'] = $docDate;
            $dadosCab['strAbrevMoeda'] = $strAbrevMoeda;
            $dadosCab['fltInfTotalLinhas'] = $fltInfTotalLinhas;
            $dadosCab['fltInfTotalQtd'] = $fltInfTotalQtd;
            $dadosCab['fltInfTotalArtigos'] = $fltInfTotalArtigos;
            $dadosCab['fltTotalMercadoriaSIVA'] = $fltTotalMercadoriaSIVA;
            $dadosCab['fltTotalMercadoriaCIVA'] = $fltTotalMercadoriaCIVA;
            $dadosCab['fltTotalIVA'] = $fltTotalIVA;
            for ($slot = 1; $slot <= 6; $slot++) {
                $dadosCab['fltIVATaxa' . $slot] = $ivaRateSlots[$slot];
                $dadosCab['fltIVAIncidencia' . $slot] = $ivaBaseSlots[$slot];
                $dadosCab['intIVACodTaxa' . $slot] = $ivaCodeSlots[$slot];
                $dadosCab['fltIVAValor' . $slot] = $ivaValueSlots[$slot];
            }
            foreach ($qrEnforcedValues as $column => $value) {
                if (isset($columnLookup[$column])) {
                    $dadosCab[$column] = $value;
                }
            }
            $dadosCab['dtmDataAbertura'] = $currentDateTime;
            $dadosCab['dtmDataAlteracao'] = $currentDateTime;
            $dadosCab['intTpEntidade'] = $intTpEntidade;
            $dadosCab['intCodEntidade'] = $intCodEntidade;

        
            try {
                $database->pdo->beginTransaction();

                $database->insert($sTable, $dadosCab);
                
                $insertError = $database->error();
                if (!is_array($insertError) || $insertError[0] !== '00000') {
                    $errorCode = isset($insertError[0]) ? $insertError[0] : 'N/A';
                    $errorMsg  = isset($insertError[2]) ? $insertError[2] : 'Erro desconhecido na inserção do cabeçalho.';
                    $database->pdo->rollBack();
                    $recList['error'][] = "Falha ao inserir cabeçalho $fieldG ($errorCode): $errorMsg";
                    die(json_encode(array("success"=>0,"errormsg"=>$errorMsg)));
                    continue;
                }

                $insertId = (int) $database->id();
                if ($insertId <= 0) {
                    $database->pdo->rollBack();
                    $recList['error'][] = "Falha ao inserir cabeçalho $fieldG: registo não criado.";
                    continue;
                }

                static $lineColumnLookup = null;
                if ($lineColumnLookup === null) {
                    $lineColumnLookup = array_flip($aLinhas);
                }

                $rawLines = array();
                if (isset($doc['linhas']) && is_array($doc['linhas'])) {
                    $rawLines = $doc['linhas'];
                } elseif (isset($doc['line_items']) && is_array($doc['line_items'])) {
                    $rawLines = $doc['line_items'];
                }
                $preparedLines = array();

                $lineSequence = 1;
                foreach ($rawLines as $lineIndex => $lineData) {
                    if (!is_array($lineData)) {
                        continue;
                    }

                    $normalizedLine = $lineData;
                    $lineDataUpper = array_change_key_case($lineData, CASE_UPPER);

                    if (isset($lineDataUpper['PRODUCT_CODE']) && $lineDataUpper['PRODUCT_CODE'] !== '') {
                        $normalizedLine['strCodArtigo'] = trim($lineDataUpper['PRODUCT_CODE']);
                    }

                    if (isset($lineDataUpper['ITEM']) && $lineDataUpper['ITEM'] !== '') {
                        $normalizedLine['strDescArtigo'] = trim($lineDataUpper['ITEM']);
                    }

                    $quantity = isset($lineDataUpper['QUANTITY']) ? $normalizeDecimal($lineDataUpper['QUANTITY']) : null;
                    if ($quantity !== null) {
                        $normalizedLine['fltQuantidade'] = round($quantity, 6);
                    }

                    $unitPrice = isset($lineDataUpper['UNIT_PRICE']) ? $normalizeDecimal($lineDataUpper['UNIT_PRICE']) : null;
                    if ($unitPrice !== null) {
                        $normalizedLine['fltPrecoUnitario'] = round($unitPrice, 6);
                    }

                    $totalWithVat = isset($lineDataUpper['PRICE']) ? $normalizeDecimal($lineDataUpper['PRICE']) : null;
                    if ($totalWithVat === null && $quantity !== null && $unitPrice !== null) {
                        $totalWithVat = $quantity * $unitPrice;
                    }

                    $vatRate = isset($lineDataUpper['IVA_TAXA']) ? $normalizeDecimal($lineDataUpper['IVA_TAXA']) : null;
                    if ($vatRate !== null) {
                        $normalizedLine['fltTaxaIVA'] = $vatRate;
                        $taxCode = getIvaCodeByTax($vatRate);
                        if ($taxCode !== null) {
                            $normalizedLine['intCodTaxaIVA'] = (int) $taxCode;
                        }
                    }

                    $priceExVat = isset($lineDataUpper['PRICE_VAT']) ? $normalizeDecimal($lineDataUpper['PRICE_VAT']) : null;
                    if ($priceExVat !== null) {
                        $normalizedLine['fltValorMercadoriaSIVA'] = round($priceExVat, 4);
                    } elseif ($totalWithVat !== null && $vatRate !== null) {
                        $baseValue = $totalWithVat / (1 + ($vatRate / 100));
                        $normalizedLine['fltValorMercadoriaSIVA'] = round($baseValue, 4);
                    }

                    if ($totalWithVat !== null) {
                        $totalWithVat = round($totalWithVat, 4);
                        $normalizedLine['fltValorMercadoriaCIVA'] = $totalWithVat;
                        $normalizedLine['fltValorLiquido'] = $totalWithVat;
                        $normalizedLine['fltValorAPagar'] = $totalWithVat;
                    }

                    $prepared = array(
                        "strCodSeccao"   => $strCodSeccao,
                        "strAbrevTpDoc"  => $strAbrevTpDoc,
                        "strCodExercicio"=> $strCodExercicio,
                        "intNumero"      => $intNumero
                    );

                    foreach ($normalizedLine as $column => $value) {
                        if ($column === 'Id' || !is_string($column) || !isset($lineColumnLookup[$column])) {
                            continue;
                        }

                        if ($value === '' || $value === null) {
                            $prepared[$column] = null;
                            continue;
                        }

                        if (strpos($column, 'flt') === 0) {
                            $decimal = $normalizeDecimal($value);
                            $prepared[$column] = $decimal !== null ? $decimal : null;
                            continue;
                        }

                        if (strpos($column, 'int') === 0) {
                            $prepared[$column] = is_numeric($value) ? (int) $value : null;
                            continue;
                        }

                        if (strpos($column, 'bit') === 0) {
                            if (is_numeric($value)) {
                                $prepared[$column] = (int) ((float) $value > 0 ? 1 : 0);
                            } else {
                                $prepared[$column] = in_array(strtolower((string) $value), array('true', 'yes', 'on'), true) ? 1 : 0;
                            }
                            continue;
                        }

                        $prepared[$column] = is_string($value) ? trim($value) : $value;
                    }

                    if (!isset($prepared['intTpLinha'])) {
                        $prepared['intTpLinha'] = isset($normalizedLine['intTpLinha']) && $normalizedLine['intTpLinha'] !== ''
                            ? (int) $normalizedLine['intTpLinha']
                            : 1;
                    }

                    $lineQuantity = isset($prepared['fltQuantidade']) ? (float) $prepared['fltQuantidade'] : null;
                    if ($lineQuantity === null || $lineQuantity <= 0) {
                        continue;
                    }

                    $hasMeaningfulData = !empty($prepared['strDescArtigo'])
                        || (isset($prepared['fltQuantidade']) && $prepared['fltQuantidade'] !== null)
                        || (isset($prepared['fltValorMercadoriaCIVA']) && $prepared['fltValorMercadoriaCIVA'] !== null)
                        || (isset($prepared['fltValorMercadoriaSIVA']) && $prepared['fltValorMercadoriaSIVA'] !== null);

                    if ($hasMeaningfulData) {
                        $prepared['intNumLinha'] = $lineSequence++;
                        $preparedLines[] = $prepared;
                    }
                }

                if (empty($preparedLines)) {
                    $database->pdo->rollBack();
                    $recList['error'][] = "Documento $fieldG sem linhas válidas para inserção.";
                    continue;
                }

                $database->insert("Mov_Compra_Lin", $preparedLines);

                $lineInsertError = $database->error();

                if (!is_array($lineInsertError) || $lineInsertError[0] !== '00000') {
                    $errorCode = isset($lineInsertError[0]) ? $lineInsertError[0] : 'N/A';
                    $errorMsg  = isset($lineInsertError[2]) ? $lineInsertError[2] : 'Erro desconhecido na inserção das linhas.';
                    $database->pdo->rollBack();
                    $recList['error'][] = "Falha ao inserir linhas de $fieldG ($errorCode): $errorMsg";
                    continue;
                }

                $database->update("Tbl_Numeradores", array("intNum_Mes00" => $intNumero), array(
                    "strAbrevTpDoc" => $strAbrevTpDoc,
                    "intTpNumerador" => 1,
                    "strCodSeccao"  => $strCodSeccao,
                    "strCodExercicio" => $strCodExercicio
                ));

                $updateError = $database->error();
                if (!is_array($updateError) || $updateError[0] !== '00000') {
                    $errorCode = isset($updateError[0]) ? $updateError[0] : 'N/A';
                    $errorMsg  = isset($updateError[2]) ? $updateError[2] : 'Erro desconhecido ao atualizar numerador.';
                    $database->pdo->rollBack();
                    $recList['error'][] = "Falha ao atualizar numerador para $fieldG ($errorCode): $errorMsg";
                    continue;
                }

                $database->pdo->commit();
                $recList['novo'][] = $fieldG;
            } catch (Exception $e) {
                $database->pdo->rollBack();
                $recList['error'][] = "Erro ao inserir compra $fieldG: " . $e->getMessage();
            }
        }

        $success = empty($recList['error']);
        die(json_encode(array(
            "success"   => $success ? 1 : 0,
            "resultado" => $recList
        )));
    }




    if($accao=="getDetail" && isset($_GET['num']) && $_SERVER['REQUEST_METHOD'] === 'GET'){
        $numVenda=$_GET['num'];
        $queryVenda = $database->select($sTable, $aColumns, ["Id" => $numVenda]);	
            
        if(sizeof($queryVenda)==0) {  
        die(json_encode(array("success"=>0,"errormsg"=>"registo inexistente")));
            
        } else {       
            
        $queryLinha = $database->select("Mov_Compra_Lin", $aLinhas, ["strCodSeccao" => $queryVenda[0]['strCodSeccao'],"strAbrevTpDoc" => $queryVenda[0]['strAbrevTpDoc'],
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
                    $queryLinha = $database->select("Mov_Compra_Lin", $aLinhas, [
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
    
