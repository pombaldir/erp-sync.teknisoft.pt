<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");
use Medoo\Medoo;
//print_r($_GET);

if (!function_exists('generateUuidV4')) {
    function generateUuidV4()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

if (!function_exists('truncateField')) {
    function truncateField($value, int $maxLength)
    {
        if ($value === null) {
            return null;
        }
        $value = (string) $value;
        if (function_exists('mb_strlen')) {
            return mb_strlen($value, 'UTF-8') > $maxLength ? mb_substr($value, 0, $maxLength, 'UTF-8') : $value;
        }
        return strlen($value) > $maxLength ? substr($value, 0, $maxLength) : $value;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['act'])) {
    $accao = "list";
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['act'])) {
    $accao = $_GET['act'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['act'])) {
    $accao = $input['act'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($input['act'])) {
    $accao = $_POST['act'];
}

$limit=isset($_GET['limit']) && $_GET['limit']!="" ? $_GET['limit'] : 100;
$offset=isset($_GET['offset']) && $_GET['offset']!="" ? $_GET['offset'] : 0;

$qParameters=array();

//die(print_r($input));
 // die(print_r($_POST));

 //die(print_r($_GET));
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'GET' && $accao=="listDBemp"){
//die(print_r($_GET));
    try {  
        $statement = $database->query("SELECT name FROM sys.databases WHERE name LIKE 'emp[_]%' ORDER BY name");
        if ($statement === false) {
            $error = $database->error();
            $message = is_array($error) ? implode(' | ', array_filter($error)) : 'Erro ao listar bases de dados.';
            throw new Exception($message);
        }

        $empresas = $statement->fetchAll(PDO::FETCH_COLUMN);
        

        $output = array(
            "success" => 1,
            "data" => array()
        );

        foreach ($empresas as $empresa) {
            $output['data'][] = array(
                "emp" => $empresa,
                "value" => $empresa
            );
        }
    } catch (Exception $e) {
        http_response_code(500);
        $output = array(
            "success" => 0,
            "error" => $e->getMessage()
        );
    }
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST' && $accao=="importMovim"){

    $codDiario=isset($input['codDiario']) && $input['codDiario']!=""? $input['codDiario']: "8";
    $documents=isset($input['documents']) && $input['documents']!=""? $input['documents']: array();
    $recList=array();
    $cabInsertedIds = array();
   
    foreach($documents as $doc){
        $field_A = $doc['field_A'];
        $field_B = $doc['field_B'];
        $field_C = $doc['field_C'];
        $field_D = $doc['field_D'];
        $field_E = $doc['field_E'];
        $field_F = $doc['field_F'];
        $field_G = $doc['field_G'];
        $field_H = $doc['field_H'];
        $field_I1 = $doc['field_I1'];
        $field_I2 = isset($doc['field_I2']) ? $doc['field_I2']: "";
        $field_I3 = $doc['field_I3'];
        $field_I4 = $doc['field_I4'];
        $field_I5 = $doc['field_I5'];
        $field_I6 = $doc['field_I6'];
        $field_I7 = $doc['field_I7'];
        $field_I8 = $doc['field_I8'];
        $field_N = $doc['field_N'];
        $field_O = $doc['field_O'];
        $field_Q = $doc['field_R'];
        $docType = $doc['docType'] ?? "V/FAC";
        $fileAttachment = $doc['file_attachment'] ?? null;

        $exercicio=getExercicioByDate($field_F);
        $mes=(int)substr($field_F,5,2);
        $prefixoMes = (string) (int) $mes;

        $account=$doc['account'];

        if ($database->has('Movimentos_Ctb_Cab', ['strNum_Doc' => $field_G . "GGGGGGG"])) {
        //if ($database->has('Movimentos_Ctb_Cab', ['strNum_Doc' => $field_G])) {
            // Documento já existe
            $recList['exist'][] = "Documento:$field_A, Exercício:$exercicio, Mês:$mes, BD:$bdName";

        } else {
            try {
                // Início da transação
                $database->pdo->beginTransaction();

                // Gera o novo numerador (a tua lógica atual)
                $ultimo = $database->max("Movimentos_Ctb_Cab", "intNum_Diario", [
                    "strCodExercicio" => $exercicio,
                    "intCodDiario"    => $codDiario,
                    "intMes"          => $mes
                ]);
                $novoSequencial = $ultimo ? ((int) substr($ultimo, strlen($prefixoMes))) + 1 : 1;
                $numerador = (int) ($prefixoMes . str_pad($novoSequencial, 4, "0", STR_PAD_LEFT));

                // Insere o registo
                $dadosCab = [
                    "strCodExercicio" => $exercicio,
                    "intCodDiario"    => $codDiario,
                    "intMes"          => $mes,
                    "intNum_Diario"   => $numerador,
                    "strNum_Doc"      => $field_G,
                    "strData"         => $field_F,
                    "strAbrevMoeda"   => "EUR", 
                    "fltCambio"       => 1,
                    "strAplicacaoOrigem"    => "CTEP",
                    "strAbrevTpDoc"   => $docType,
                    "dtmAbertura"   => date("Y-m-d H:i:s"),
                    "dtmAlteracao"  => date("Y-m-d H:i:s"),
                    "strNum_Diario" => $numerador,
                    "dtmDocument"   => $field_F,
                    "strFArchTaxPayer" => $field_A,
                    "fltFArchTotal" => $field_O
                ];
              //  die(json_encode($input)); 
 
                $database->insert("Movimentos_Ctb_Cab", $dadosCab);
                $cabId = $database->id();
                if (empty($cabId)) {
                    $cabId = $database->get("Movimentos_Ctb_Cab", "Id", [
                        "strCodExercicio" => $exercicio,
                        "intCodDiario"    => $codDiario,
                        "intMes"          => $mes,
                        "intNum_Diario"   => $numerador,
                        "strNum_Doc"      => $field_G
                    ]);
                }

                // Normaliza a coleção de linhas (podem vir em account ou lines)
                $linhasDoc = [];
                if (isset($doc['account']) && is_array($doc['account'])) {
                    $linhasDoc = $doc['account'];
                } elseif (isset($doc['lines']) && is_array($doc['lines'])) {
                    $linhasDoc = $doc['lines'];
                }

                if (empty($linhasDoc)) {
                    $database->pdo->rollBack();
                    $recList['error'][] = "❌ Documento:$field_A sem linhas contabilísticas.";
                    continue;
                }

                $numeroLinha = 1;
                $linhaErro = null;
                //die(json_encode($linhasDoc)); 
                foreach ($linhasDoc as $linhaDoc) {
                    $contaLinha = $linhaDoc['strConta'] ?? $linhaDoc['account'] ?? $linhaDoc['conta'] ?? null;
                    $valorLinha = $linhaDoc['fltValor'] ?? $linhaDoc['valor'] ?? $linhaDoc['value'] ?? 0;
                    $intGrp_Terc = $linhaDoc['intGrp_Terc'] ?? -1;
                    $descricaoLinha = $linhaDoc['strDescricao'] ?? $linhaDoc['description'] ?? $field_C;
                    $debitoCredito = strtoupper($linhaDoc['strDeb_Cre'] ?? $linhaDoc['type'] ?? ($valorLinha >= 0 ? 'D' : 'C'));
                    $tax_rate = $linhaDoc['tax_rate'] ?? null;
 
                    if ($contaLinha === null) {
                        $linhaErro = "❌ Linha sem conta definida no documento:$field_A.";
                        break;
                    }

                    if ($debitoCredito !== 'D' && $debitoCredito !== 'C') {
                        $debitoCredito = $valorLinha >= 0 ? 'D' : 'C';
                    }

                    $valorAbsoluto = abs((float) $valorLinha);
                    if ($valorAbsoluto == 0.0) {
                        continue;
                    }


                    $dadosLinha = [
                        "strCodExercicio"  => $exercicio,
                        "intCodDiario"     => $codDiario,
                        "intNum_Diario"    => $numerador,
                        "intMes"           => $mes,
                        "intNumlinha"      => $numeroLinha++,
                        "strConta"         => $contaLinha,
                        "fltValor"         => $valorAbsoluto,
                        "strDeb_Cre"       => $debitoCredito,
                        "strDescricao"     => $descricaoLinha,
                        "strData"          => $field_F,
                        "fltValorMoeda"    => $valorAbsoluto,
                        "intGrp_Terc"      => $intGrp_Terc,
                        "strCodePlan"    => "CONTAB",
                        "strAbrevMoedaLin" => "EUR"
                    ];

                    if (!empty($linhaDoc['strNumContrib'])) {
                        $dadosLinha['strNumContrib'] = $linhaDoc['strNumContrib'];
                    } elseif (!empty($linhaDoc['taxId'])) {
                        $dadosLinha['strNumContrib'] = $linhaDoc['taxId'];
                    }

                    $database->insert("Movimentos_Ctb_Lin", $dadosLinha);
                    $erroLinha = $database->error();
                    if (is_array($erroLinha) && $erroLinha[0] !== '00000') {
                        $linhaErro = "❌ Erro ao inserir linha ($contaLinha) do documento:$field_A → " . $erroLinha[2];
                        break;
                    }
                }
 
                if ($linhaErro !== null) {
                    $database->pdo->rollBack();
                    $recList['error'][] = $linhaErro;
                    continue;
                }

                // Guarda anexo do documento (PDF) se enviado
                $anexoErro = null;
                $anexoResumo = "sem anexo"; 
                if ($fileAttachment && !empty($fileAttachment['content_base64'])) {
                    $conteudoFicheiro = base64_decode($fileAttachment['content_base64'], true);
                    if ($conteudoFicheiro === false) {
                        $anexoErro = "❌ Base64 inválido no anexo do documento:$field_A.";
                    } else {
                        $anexoId = generateUuidV4(); // para consistência (ctrl/ficheiro)
                        $anexoDbId = generateUuidV4(); // PK da tabela
                        $infoAnexo = [
                            "path" => $fileAttachment['path'] ?? null,
                            "filename" => $fileAttachment['filename'] ?? null,
                            "size" => $fileAttachment['size'] ?? null,
                            "mime_type" => $fileAttachment['mime_type'] ?? null,
                            "import_type" => $doc['import_type'] ?? null,
                            "dte_add" => $doc['dte_add'] ?? null,
                            "intCodDiario" => $codDiario,
                            "intNum_Diario" => $numerador,
                            "strCodExercicio" => $exercicio,
                            "strNum_Doc" => $field_G
                        ];

                        // Respeita limites de comprimento das colunas
                        $strRef = truncateField($field_G, 30);
                        // Garantir FK para Tbl_Aut_Documentacao
                        $strCodDocumentacao = null;
                        $codDocTry = truncateField("ADCTB", 10);
                        if ($codDocTry && $database->has("Tbl_Aut_Documentacao", ["strCodigo" => $codDocTry])) {
                            $strCodDocumentacao = $codDocTry;
                        } else {
                            $primeiroCodDoc = $database->get("Tbl_Aut_Documentacao", "strCodigo", ["ORDER" => ["strCodigo" => "ASC"]]);
                            if ($primeiroCodDoc) {
                                $strCodDocumentacao = truncateField($primeiroCodDoc, 10);
                            } else {
                                $anexoErro = "❌ Sem códigos em Tbl_Aut_Documentacao para atribuir ao anexo.";
                            }
                        }
                        $strLocal = truncateField($fileAttachment['path'] ?? ($fileAttachment['filename'] ?? ''), 30);
                        $strIdFicheiro = truncateField($fileAttachment['filename'] ?? $anexoId, 500);
                        $strCtrlConsistency = truncateField($anexoId, 40);
                        $strDocArchiveNumber = truncateField("", 40);
                        $strDocInfo = truncateField(json_encode($infoAnexo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 70);
                        // Obtém situação válida para respeitar FK
                        $situacaoDefault = $database->min("Tbl_Aut_SitucacoesDocumentacao", "intCodigo");
                        if ($situacaoDefault === null) { $situacaoDefault = 1; }
                        $situacaoDefault = (int) $situacaoDefault; 
 
                        if ($anexoErro !== null) {
                            // sem código de documentação válido, evita quebra da FK
                            $recList['error'][] = $anexoErro;
                            continue;
                        }

                        $dadosAnexo = [
                            "id" => $anexoDbId,
                            "strCodDocumentacao" => $strCodDocumentacao,
                            "dtmDataEmissao" => $field_F,
                            "strRef" => $strRef,
                            "strObs" => $field_C,
                            "strLocal" => $strLocal,
                            "intCodSituacao" => $situacaoDefault,
                            "dtmDataSituacao" => date("Y-m-d H:i:s"),
                            "strLogin" => $doc['user'] ?? "api",
                            "strIdFicheiro" => $strIdFicheiro,
                            "Ficheiro" => $conteudoFicheiro,
                            "bitIsForSend" => isset($doc['bitIsForSend']) ? (int) $doc['bitIsForSend'] : 0,
                            "bitWithElectronicSignature" => 0,
                            "strCtrlConsistency" => $strCtrlConsistency,
                            "bitConsistent" => 1,
                            "strDocArchiveNumber" => $strDocArchiveNumber,
                            "bitDocArchiveNumber" => 0,
                            "bitFromInbox" => 0,
                            "strDocInfo" => $strDocInfo
                        ];
+
                        // Inserção direta (literal) para evitar interferência do driver com o ID/varbinary
                        $pdo = $database->pdo;
                        $q = static function ($v) use ($pdo) {
                            return $pdo->quote($v);
                        }; 
                        $hexFile = bin2hex($dadosAnexo["Ficheiro"]);
                        $idLiteral = $dadosAnexo["id"] ?: generateUuidV4();
                        $sqlAnexo = "INSERT INTO Tbl_AnexosDigitais (id, strCodDocumentacao, dtmDataEmissao, strRef, strObs, strLocal, intCodSituacao, dtmDataSituacao, strLogin, strIdFicheiro, Ficheiro, bitIsForSend, bitWithElectronicSignature, strCtrlConsistency, bitConsistent, strDocArchiveNumber, bitDocArchiveNumber, bitFromInbox, strDocInfo) VALUES (COALESCE(CAST(" . $q($idLiteral) . " AS uniqueidentifier), NEWID()), "
                            . $q($dadosAnexo["strCodDocumentacao"]) . ", "
                            . $q($dadosAnexo["dtmDataEmissao"]) . ", "
                            . $q($dadosAnexo["strRef"]) . ", "
                            . $q($dadosAnexo["strObs"]) . ", "
                            . $q($dadosAnexo["strLocal"]) . ", "
                            . $dadosAnexo["intCodSituacao"] . ", "
                            . $q($dadosAnexo["dtmDataSituacao"]) . ", "
                            . $q($dadosAnexo["strLogin"]) . ", "
                            . $q($dadosAnexo["strIdFicheiro"]) . ", "
                            . "0x{$hexFile}, "
                            . (int) $dadosAnexo["bitIsForSend"] . ", "
                            . (int) $dadosAnexo["bitWithElectronicSignature"] . ", "
                            . $q($dadosAnexo["strCtrlConsistency"]) . ", "
                            . (int) $dadosAnexo["bitConsistent"] . ", "
                            . $q($dadosAnexo["strDocArchiveNumber"]) . ", "
                            . (int) $dadosAnexo["bitDocArchiveNumber"] . ", "
                            . (int) $dadosAnexo["bitFromInbox"] . ", "
                            . $q($dadosAnexo["strDocInfo"])
                            . ")"; 

                        $execOk = $pdo->exec($sqlAnexo);
                        if ($execOk === false) {
                            $errorInfo = $pdo->errorInfo();
                            $anexoErro = "❌ Erro ao inserir anexo do documento:$field_A → " . ($errorInfo[2] ?? 'desconhecido');
                        } else {
                            // Após inserir o anexo, associa na Tbl_AnexosDigitais_Entidades
                            $qOrNull = static function ($v) use ($pdo) {
                                return $v === null ? "NULL" : $pdo->quote($v);
                            };
                            $entTipo = isset($doc['intTipoEntidade']) ? (int) $doc['intTipoEntidade'] : 23;
                            $entChave1 = (string) $exercicio;
                            $entChave2 = (string) $codDiario;
                            $entChave3 = (string) $mes;
                            $entNumero = (int) $numerador;
                            $entChaveComp = $field_G ?? null;
                            $bitFiscalArchive = isset($doc['bitFiscalArchive']) ? (int) $doc['bitFiscalArchive'] : 1;
                            $bitFiscalArchiveHist = isset($doc['bitFiscalArchiveHist']) ? (int) $doc['bitFiscalArchiveHist'] : 1;
                            $entDesc = $doc['adicionalDescription'] ?? null;

                            $sqlEnt = "INSERT INTO Tbl_AnexosDigitais_Entidades (idCab, intTipoEntidade, strChave1, strChave2, strChave3, intNumero, strChaveComp, bitFiscalArchive, bitFiscalArchiveHist, strAdicionalDescription) VALUES (CAST(" . $q($idLiteral) . " AS uniqueidentifier), "
                                . $entTipo . ", "
                                . $q($entChave1) . ", "
                                . $q($entChave2) . ", "
                                . $q($entChave3) . ", "
                                . $entNumero . ", "
                                . $qOrNull($entChaveComp) . ", "
                                . $bitFiscalArchive . ", "
                                . $bitFiscalArchiveHist . ", "
                                . $qOrNull($entDesc)
                                . ")";

                            $execEnt = $pdo->exec($sqlEnt);
                            if ($execEnt === false) {
                                $errorInfo = $pdo->errorInfo();
                                $anexoErro = "❌ Erro ao associar anexo em Tbl_AnexosDigitais_Entidades:$field_A → " . ($errorInfo[2] ?? 'desconhecido');
                            } else {
                                $anexoResumo = "anexo_ok id={$idLiteral} ficheiro=" . ($fileAttachment['filename'] ?? $anexoId);
                            }
                        }
                    }
                }

                if ($anexoErro !== null) {
                    $database->pdo->rollBack();
                    $recList['error'][] = $anexoErro;
                    continue;
                }

                // Confirma a transação
                $database->pdo->commit();
                // (Opcional) guarda o valor em lista/log
                //$recList['novo'][] = "Doc:$field_A → Num:$numerador (Mês:$mes, Exercicio:$exercicio) BD:$bdName; Anexo:$anexoResumo";
                $recList['novo'][] = "Doc:$field_A → $numerador";
                $cabInsertedIds[] = array(
                    "id" => $cabId,
                    "strNum_Doc" => $field_G,
                    "intNum_Diario" => $numerador,
                    "strCodExercicio" => $exercicio,
                    "intCodDiario" => $codDiario,
                    "intMes" => $mes
                );

            } catch (Exception $e) {
                $database->pdo->rollBack();
                $recList['error'][] = "❌ Erro ao inserir movimento: " . $e->getMessage();
            }
           
        }     
    }

    $mensagem = "Registo adicionado com sucesso.";
    $response="success";
    if (!empty($recList['novo'])) {
        $mensagensNovos = implode(" | ", $recList['novo']);
        $mensagem = (count($recList['novo']) > 1)
            ? "Registos adicionados com sucesso. {$mensagensNovos}"
            : "Registo adicionado com sucesso. {$mensagensNovos}";
    }
    if (!empty($recList['exist']) && empty($recList['novo'])) {
        $mensagem = "Documento já lançado; nenhum novo registo criado. Documento externo: $field_G";
        $response="warning";
    } elseif (!empty($recList['exist']) && !empty($recList['novo'])) {
        $mensagem = "Importação concluída; alguns documentos já estavam lançados.";
    }
  
   $recList['cab_ids'] = $cabInsertedIds;
   $output = array(
       "success"=>1,
       "type"=>$response,
       "status"=>200,
       "recs"=>$recList,
       "cab_ids"=>$cabInsertedIds,
       "log"=>$input,
       "mensagem"=>$mensagem
   );       

}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    
    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    
    if($_GET['tipo']=="diarios"){
        $sTable = "Tbl_Diarios"; 
        $aColumns = array("Id","intCodigo", "strDescricao", "bitFiltrar_Docs", "dtmAbertura", "dtmAlteracao", "bitDebDifCre", "bitPermiteIntroduzir");    
    }  
    else if($_GET['tipo']=="movimentos"){  //die($bdName);
        $sTable = "Movimentos_Ctb_Cab"; 
        //die(print_r($_GET));
        $aColumns = array("strCodExercicio", "intCodDiario", "intNum_Diario", "intMes", "strData", "strAbrevTpDoc", "strNum_Doc", "Id", "strAbrevMoeda", "fltCambio", "dtmAbertura", "dtmAlteracao", "strAplicacaoOrigem", "strLogin", "strNum_Diario");    
        $qParameters=array($aColumns[0],$aColumns[1],$aColumns[2],$aColumns[3],$aColumns[4],$aColumns[5]);
    } 
    else if($_GET['tipo']=="taxasiva"){
        $sTable = "Tbl_Taxas_Iva"; 
        $aColumns = array("Id","intCodigo", "strDescricao", "fltTaxa");    
    }          
    else if($_GET['tipo']=="cat-entidades"){
        $sTable = "Tbl_Categoria_Entidade"; 
        $aColumns = array("Id","intCodigo", "strDescricao");    
    }      
    else if($_GET['tipo']=="numeradores"){
        $sTable = "Tbl_Numeradores"; 
        $aColumns = array("Id", "strAbrevTpDoc", "strCodSeccao", "strCodExercicio","intNum_Mes00"); 
        $qParameters=array($aColumns[1],$aColumns[2],$aColumns[3]);
    }    
    
    else if($_GET['tipo']=="familias"){
        $sTable = "Tbl_Gce_Familias"; 
        $aColumns = array("Id", "strCodigo", "strDescricao", "strCodTpFamilia"); 
    }  
    else if($_GET['tipo']=="tipofamilias"){
        $sTable = "Tbl_Gce_Tipos_Familias"; 
        $aColumns = array("Id", "strCodigo", "strDescricao", "intNivel"); 
    }     
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
     

     if (isset($_GET['order']) && $_GET['order'] != "" && !isset( $_GET['iSortCol_0']))
    {
        $sOrdem=explode(";",$_GET['order']); 
        if(in_array($sOrdem[0],$aColumns)) {
        $sOrder = "ORDER BY $sOrdem[0] $sOrdem[1]";  
        }
    } 
     
	
    $sWhere = "";
    if ( isset($_GET['q']) && $_GET['q'] != "" && in_array($_GET['searchField'],$aColumns))
    {
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
    
    if(sizeof($qParameters)>0){
        if($sWhere==""){ $sWhere .= "WHERE";   } else { $sWhere .= "AND "; }
        foreach($qParameters as $parm){
            if($_GET[''.$parm.'']!=""){
            $sWhere.=" $parm = '".$_GET[''.$parm.'']."' AND ";  
            }
        }
        $sWhere = substr_replace( $sWhere, "", -5);
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

	
	$sQuery = $database->select($sTable, $aColumns, Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".($offset*$limit)." ROWS FETCH NEXT ".$limit." ROWS ONLY"));
	
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
            
             if ( $aColumns[$i] == 'bitEVSujeito')
            {
                $queryPreco = $database->select("Tbl_Gce_Artigosprecos", [
                "intNumero",
                "fltPreco",   
                "intTpIVA",      
                ], [
                    "strCodArtigo" => $aRow[ $aColumns[1]],
                    "ORDER" => ["intNumero"=>"ASC"]
                ]);	                 
                $row['precos'] = $queryPreco[0];
            }
             else if ( $aColumns[$i] == 'bitCusto')
            {
                $queryFam = $database->select("Tbl_Gce_ArtigosFamilias", [
                "strCodFamilia",
                "strCodTpNivel",   
                ], [
                    "strCodArtigo" => $aRow[ $aColumns[1]],
                    "ORDER" => ["strCodTpNivel"=>"ASC"]
                ]);	                 
                $row['familias'] = $queryFam[0];
            }
            
            
             else if ( $aColumns[$i] != ' ' )
            {
                $row[$aColumns[$i]] = $aRow[ $aColumns[$i]];
            }

            
        }
        if($_GET['tipo']=="movimentos"){ 
                 $queryLin= $database->select("Movimentos_Ctb_Lin", [
                "intNumlinha", "strConta", "fltValor", "strDeb_Cre", "intGrp_Terc","strNumContrib","bitReconciliado","strCodePlan", "IdReconciliation", "fltValueReconcilied"  
                ], [
                    "strCodExercicio" => $aRow[ $aColumns[0]],
                    "intCodDiario" => $aRow[ $aColumns[1]],
                    "intNum_Diario" => $aRow[ $aColumns[2]],
                    "intMes" => $aRow[ $aColumns[3]],
                    "ORDER" => ["intNumlinha"=>"ASC"]
                ]);	  
                $row['linhas'] =$queryLin;
            }

        $output['aaData'][] = $row;
    }
   }   
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($output)){
    echo json_encode($output);
}
