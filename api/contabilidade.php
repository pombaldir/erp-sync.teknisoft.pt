<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");
use Medoo\Medoo;
//print_r($_GET);


if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['act'])) {
    $accao = "list";
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['act'])) {
    $accao = $_GET['act'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['act'])) {
    $accao = $input['act'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['act'])) {
    $accao = $input['act'];
}

$limit=isset($_GET['limit']) && $_GET['limit']!="" ? $_GET['limit'] : 100;
$offset=isset($_GET['offset']) && $_GET['offset']!="" ? $_GET['offset'] : 0;

$qParameters=array();

//die(print_r($_POST));
  //die(print_r($_GET));
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST' && $accao=="listDBemp"){

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

        $exercicio=getExercicioByDate($field_F);
        $mes=(int)substr($field_F,5,2);
        $prefixoMes = (string) (int) $mes;


        $account=$doc['account'];

        if ($database->has('Movimentos_Ctb_Cab', ['strNum_Doc' => $field_G . "GGGGGGG"])) {

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
                    //"strAbrevTpDoc"   => "",
                    //"dtmAbertura"   => date("Y-m-d H:i:s"),
                    //"dtmAlteracao"  => date("Y-m-d H:i:s"),
                    "strNum_Diario" => $numerador,
                    "dtmDocument"   => $field_F,
                    "strFArchTaxPayer" => $field_A,
                    "fltFArchTotal" => $field_O
                ];

                $database->insert("Movimentos_Ctb_Cab", $dadosCab);
                // Confirma a transação
                $database->pdo->commit();
                // (Opcional) guarda o valor em lista/log
                $recList['novo'][] = "Doc:$field_A → Num:$numerador (Mês:$mes, Exercicio:$exercicio) BD:$bdName";

            } catch (Exception $e) {
                $database->pdo->rollBack();
                $recList['error'][] = "❌ Erro ao inserir movimento: " . $e->getMessage();
            }
           
        }


         
       
    }


   $output = array("success"=>1,"response"=>"resposta", "status"=>200, "recs"=>$recList, "log"=>$input,"mensagem"=>"Registo adicionado com sucesso.!");       

}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    
    $sIndexColumn = "Id"; 
    /* DB table to use */ 
    
    if($_GET['tipo']=="diarios"){
        $sTable = "Tbl_Diarios"; 
        $aColumns = array("Id","intCodigo", "strDescricao", "bitFiltrar_Docs", "dtmAbertura", "dtmAlteracao", "bitDebDifCre", "bitPermiteIntroduzir");    
            /*  
            ########################################################### LISTA DIÁRIO #################################################	
                if(isset($_GET['act_g']) && $act_get=="listadiarios"){
                $rResult = mssql_query("SELECT Id, intCodigo, strDescricao, bitFiltrar_Docs, dtmAbertura, dtmAlteracao, bitDebDifCre, bitPermiteIntroduzir FROM Tbl_Diarios ORDER BY strDescricao ASC") or die("Erro");
                    while($post = mssql_fetch_assoc($rResult)) {
                    $output[] = array_map('utf8_encode',$post); 
                    } 
                }
            # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 				 				
            ########################################################### LISTA CONTAS #################################################	
                if(isset($_GET['act_g']) && $act_get=="listacontasbc"){
                $rResult = mssql_query("SELECT  strConta, strDescricao, strRefl_deb_deb, strRefl_deb_cre, strRefl_cre_deb, strRefl_cre_cre, strCodFichRepart, bitRecapitula, Id, bitMovimenta, strConta_Iva, intCodTaxaIva, strNumContrib, bitDefineFluxos, strFluxos_Deb, strFluxos_Cre, strFluxos_Bal, bitTaxa, fltTaxaValor, dtmAbertura, dtmAlteracao, UpdateStamp, fltCustoFixo, intTpImo, fltTaxaRetencaoIRS, intCodRubricaImp, intZonaRetencaoIRS, bitNaoGerirPendentes FROM Tbl_Plano_Contas WHERE (strCodExercicio = '$ano') AND (strConta LIKE '12%') order by strDescricao asc") or die("Erro");
                    while($post = mssql_fetch_assoc($rResult)) {
                    $output[] = array_map('htmlentities',$post); 
                    } 
                }
            */

    }  
    else if($_GET['tipo']=="contas"){ 
        $sTable = "Tbl_Plano_Contas"; 
        $aColumns = array("strConta", "strDescricao", "strRefl_deb_deb", "strRefl_deb_cre", "strRefl_cre_deb", "strRefl_cre_cre", "strCodFichRepart", "bitRecapitula", "Id", "bitMovimenta", "strConta_Iva", "intCodTaxaIva", "strNumContrib", "bitDefineFluxos", "strFluxos_Deb", "strFluxos_Cre", "strFluxos_Bal", "bitTaxa", "fltTaxaValor", "dtmAbertura", "dtmAlteracao", "UpdateStamp", "fltCustoFixo", "intTpImo", "fltTaxaRetencaoIRS", "intCodRubricaImp", "intZonaRetencaoIRS", "bitNaoGerirPendentes");    
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
        $output['aaData'][] = $row;
    }
   }   
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($output)){
    echo json_encode($output);
}
