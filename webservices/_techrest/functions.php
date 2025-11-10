<?php
use Medoo\Medoo;

function TechAPI_Login($TECHAPI_shop_number){

    global $database,$TECHAPI_vat_number,$TECHAPI_username,$TECHAPI_password_web;
    $hash="";

    $ch = curl_init();

    $curUrl="https://techapi.techsul.pt/ext/api/authentication/loginExt?vat_number=$TECHAPI_vat_number&shop_number=$TECHAPI_shop_number&username=$TECHAPI_username&password_web=$TECHAPI_password_web";

    curl_setopt($ch, CURLOPT_URL,$curUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = json_decode(curl_exec($ch),true);
    if (curl_errno($ch)) {
        echo curl_error($ch);
    }
    curl_close($ch);
 
    // print_r($server_output); 
    
    if($server_output['StatusCode']==200){
        $hash=$server_output['Content']['response']['hash'];
        $api_version=$server_output['Content']['response']['api_version'];
        $dataCount = $database->count("USR_sync_config", ['id'], ["id" => 1]);
        if($dataCount==0){  
            $database->insert("USR_sync_config", ["id"=>1, "hash"=>$hash,"api_version"=>$api_version,"lastLogin"=>date('Y-m-d H:i:s')]);
        } if($dataCount==1){  
            $database->update("USR_sync_config", ["hash"=>$hash,"api_version"=>$api_version,"lastLogin"=>date('Y-m-d H:i:s')], ["shop_number" => $TECHAPI_shop_number]);
        }    
    }  
    if($server_output['StatusCode']==401){
        echo "<h1>".$server_output['StatusMessage']."</h1>";
        die();
    }  
    return array("hash"=>$hash,"api_version"=>$api_version);    
}


function TechAPI_Entities($entity,$condition="",$lastId="",$order="order by id ASC",$sync=0){
    global $strHash,$TECHAPI_ApiVersion;

        $ch = curl_init();
 
        if($lastId!="" && $condition!=""){ $condition2=$condition." AND id > '".$lastId."' ";} else { $condition2=$condition; }
        $curUrl="https://techapi.techsul.pt/".$TECHAPI_ApiVersion."/api/$entity/getList?auth_hash=".$strHash."&condition=".trim($condition2)."&order=".trim($order).""; 
        $curUrl = trim(str_replace(" ","%20",$curUrl));   
        //$curUrl = str_replace("'","%27",$curUrl);      

        curl_setopt($ch, CURLOPT_URL,$curUrl);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch); 
        $server_output = json_decode(iconv("Windows-1251", "UTF-8", $output),true);

        if (curl_errno($ch)) {
            echo curl_error($ch);
        }
        curl_close($ch);
        // echo $curUrl; //
        if($server_output['StatusCode']==200){
            $dados=$server_output['Content'][$entity]; 
            if($sync==1) {  sincEntity($entity,$condition,$dados); } else {
            return $dados;
            } 
        }  else {
            return false;
        }   
}

/*
function getHasbyLoja($loja){
    global $database;
    $rquery=$database->select("USR_sync_config", ["hash"], ["shop_number"=>$loja]);
    if(is_array($rquery) && sizeof($rquery)>0){
    return $rquery[0]['hash'];  
    } else {
        return "";  
    }  
}
*/

function listLojas($fields=array(),$condition=array()){
    global $database;
    $rquery=$database->select("USR_sync_config", $fields, $condition);
    if(is_array($rquery) && sizeof($rquery)>0){
    return $rquery;  
    } else {
        return array();  
    }  
}

function TechAPI_GET_Entity($entity,$id,$retrn=array()){
    global $strHash,$TECHAPI_ApiVersion;

        $ch = curl_init();
 
        $curUrl="https://techapi.techsul.pt/".$TECHAPI_ApiVersion."/api/$entity/get?auth_hash=".$strHash."&id=".trim($id)."";
        $curUrl = str_replace(" ","%20",$curUrl);

        curl_setopt($ch, CURLOPT_URL,$curUrl);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $server_output = json_decode(curl_exec($ch),true);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }
        curl_close($ch);
        //echo $curUrl;
        if($server_output['StatusCode']==200){
            $dados=$server_output['Content'][$entity]; 
            if($retrn==""){
            return $dados;
            } else {
                $cleanArray = array_intersect_key(
                    $dados,  // the array with all keys
                    array_flip($retrn) // keys to be extracted
                );
                return $cleanArray;
            }
        }    
}


function ERP_Entities($tbl,$fields=array(),$condition=array()){
    global $database,$TECHAPI_shop_number;


    $rquery=$database->select($tbl, $fields, $condition);

    $erro=$database->error();
    $errormsg=$erro[2];
    if($errormsg==""){ 
    //var_dump($database->log());
    //print_r($database);
    return $rquery; 
    } else {
        echo "<h1>".$errormsg."</h1>"; die();
    }  

}

function ERP_Val($tipo,$techval){
    global $database,$TECHAPI_shop_number;
    $rquery=$database->select("USR_sync_taxonomy", ["erpval"], ["tipo"=>$tipo,"techval"=>$techval,"shop_number"=>$TECHAPI_shop_number]);
    if(is_array($rquery) && sizeof($rquery)>0){
    return $rquery[0]['erpval']; 
    } else {
        return;  
    }  
}

function Tech_Val($tipo,$erpval){
    global $database,$TECHAPI_shop_number;
    $rquery=$database->select("USR_sync_taxonomy", ["techval"], ["tipo"=>$tipo,"erpval"=>$erpval,"shop_number"=>$TECHAPI_shop_number]);
    if(is_array($rquery) && sizeof($rquery)>0){
    return $rquery[0]['techval']; 
    } else {
        return; 
    }  
}

function USR_sync_Val($tipo,$techval,$field){
    global $database,$TECHAPI_shop_number;
    $rquery=$database->select("USR_sync_taxonomy", ["$field"], ["tipo"=>$tipo,"techval"=>$techval,"shop_number"=>$TECHAPI_shop_number]);
    //print_r($database->log());
    if(is_array($rquery) && sizeof($rquery)>0){
    return $rquery[0][''.$field.'']; 
    } else {
        return "";  
    }  
}

function USR_sync_Param($tipo="typesdocsclients"){
    global $database;
    $par_typesdocsclients=array();
    $paramSyncdocsclients=ERP_Entities("USR_sync_taxonomy",["techval"],["tipo" =>$tipo,"sync"=>1]);  
    foreach($paramSyncdocsclients as $arrRes){
        $par_typesdocsclients[]=$arrRes['techval'];
    } 
    return $par_typesdocsclients;
}


function selOptionERP($ERPData,$fieldName,$idField,$valorFieldTechS,$predef,$fDescr="strDescricao",$fVal="Id"){
$selHtml="<select class=\"form-control configSelERP\" data-vtech=\"".$valorFieldTechS."\" name=\"".$fieldName."\" id=\"".$fieldName."_".$idField."\"><option value=\"\"></option>"; 
   foreach($ERPData as $valOpt){
    $selHtml.="<option  value=".$valOpt[''.$fVal.'']." "; if($predef==$valOpt[''.$fVal.'']){ $selHtml.=" selected"; }
    
    $selHtml.=">".$valOpt[''.$fDescr.'']." [".$valOpt[''.$fVal.'']."]</option>";
   }
   $selHtml.="</select>";
   return $selHtml;
}




function sincEntity($entity,$condition,$dados){

   // echo "$entity | $condition";  

    global $database,$ERP_strCodSubZona,$ERP_ConfLojas,$ERP_scodArtigos;
    $log=array(); 
#######################################################################################################################
####################################################### CLIENTES ######################################################
#######################################################################################################################
    if($entity=="clients"){ 
    foreach($dados as $val){
        $codCliente=$val['client_number'];
        $id=$val['id'];
        $name=$val['name'];
        $address=$val['address'];
        $local=$val['local'];
        $postal_code=$val['postal_code'];
        $country_id=$val['country_id'];
        $exception_reason_id=ERP_Val('paymentsconditions',$val['exception_reason_id']); 
        $vat_number=$val['vat_number'];
        $phone=$val['phone'];
        $mobile_phone=$val['mobile_phone'];
        $email=$val['email'];
        $fax=$val['fax'];
        $credit_limit=$val['credit_limit'];
        $blocked=$val['blocked'];
        $comments=$val['comments'];
        $blocked=$val['blocked'];
        $activity_number=$val['activity_number'];
        $contact_name=$val['contact_name'];
        $creation_date=$val['creation_date'];
        $edition_date=$val['edition_date'];
        $discount=$val['discount'];
        $payment_condition_id=ERP_Val('paymentsconditions',$val['payment_condition_id']);
        $vat_tax_id=$val['vat_tax_id'];
        $country_short_name=$val['country_short_name'];
        $exception_reason_name=$val['exception_reason_name'];
        $credit_limit=$val['credit_limit'];
        $system_data=$val['system_data'];
        
        /*{
            "id": "00000000093L1P1",
            "name": "Mundo do Vinho",
            "address": "Loule\r\n",
            "local": "",
            "postal_code": "",
            "country_id": "00000000000L0P0",
            "exception_reason_id": "00000000000L0P0",
            "vat_number": "501857036",
            "phone": "",
            "mobile_phone": "",
            "email": "",
            "fax": "",
            "credit_limit": "0.00",
            "blocked": 0,
            "comments": "",
            "activity_number": "",
            "contact_name": "",
            "client_number": 1000093,
            "system_data": 0,
            "creation_date": "2020-04-01 16:45:42.000",
            "edition_date": "2020-04-01 16:45:42.000",
            "usr": "admin",
            "usr_edition": "admin",
            "origin_identifier": "T",
            "discount": "0.00",
            "syncro_date": "2020-04-01 16:45:42.000000",
            "pendent_shops": 0,
            "payment_condition_id": "00000000000L0P0",
            "vat_tax_id": null,
            "country_short_name": "PT",
            "exception_reason_name": "Sem Isenção"
        },
        */
        $arrCliente['strTelefone']=$phone;
        $arrCliente['strTelemovel']= $mobile_phone;
        $arrCliente['strAbrevSubZona']=$ERP_strCodSubZona;
        $arrCliente['strEmail']= $email;
        $arrCliente['strMorada_lin1']=$address;
        $arrCliente['strLocalidade']=$local;
        $arrCliente['strPostal']=$postal_code;
        $arrCliente['strFax']=$fax;
        $arrCliente['fltDesconto']=$discount;
        $arrCliente['bitInactivo']=$blocked;
        $arrCliente['fltCCPlafond']=$credit_limit ? $credit_limit : 0;
        $arrCliente['strObs']=$comments;
        $arrCliente['dtmAlteracao']=$edition_date;
        $arrCliente['strCodCondPag']=$payment_condition_id;
        $arrCliente['strMotivoIsencaoIVA']=$exception_reason_name;
        $arrCliente['strCodMotIVAIsencao']=$exception_reason_id;

        $dataCount = $database->count("Tbl_clientes", ['Id'], ["intCodigo" => $codCliente]);
        ## INSERE REGISTO ##################################################################################
        if($dataCount==0 ){   
            $tp="insert";

            $arrCliente['strNome']=$name;
            $arrCliente['strNumContrib']=$vat_number;
            $arrCliente['intCodigo']=$codCliente;
            $arrCliente['dtmAbertura']=$creation_date;
            $arrCliente['bitPortalWeb']=1;
            $arrCliente['intSinalTp']=0;
            $arrCliente['bitAviso_vencimento']=1;
            $arrCliente['intCodCatEntidade']=1;
            $arrCliente['bitConsumidorFinal']=$system_data;

            $database->insert("Tbl_Clientes", $arrCliente);
            $erro=$database->error();
            $errormsg=$erro[2];
            
            if($errormsg==""){ 
                $log[]=array($tp,$entity,$codCliente,0,"Cliente $codCliente criado"); // tipo,entidade,nº cliente, sentido, msg
                syncLog(1,$entity,$tp,$codCliente,"Cliente $codCliente criado"); 
                //$database->insert("USR_sync_taxonomy", ["techval"=>$id, "erpval"=>$codCliente, "tipo"=>$entity, "sync"=>1, "dtasync"=>date('Y-m-d H:i:s')]);
            } else {
                $log[]=array($tp,$entity,$codCliente,0,$errormsg);
                syncLog(0,$entity,$tp,$codCliente,$errormsg); 
            }			
        } else { // Update? #################################################################################
            $dataCountUpdt = $database->count("Tbl_clientes", ['Id'], ["intCodigo" => $codCliente, "dtmAlteracao[<]"=>$edition_date]);
            $tp="update";
            if($dataCountUpdt==1){
                $database->update("Tbl_Clientes", $arrCliente, ["intCodigo" => $codCliente]); 
                $log[]=array($tp,$entity,$codCliente,0,"Cliente $codCliente atualizado");
                syncLog(1,$entity,$tp,$codCliente,"Cliente $codCliente atualizado"); 
            }
        }
    } 

    } 
#######################################################################################################################
####################################################### PRODUTOS ######################################################
#######################################################################################################################
if($entity=="products"){  
   
    $Tbl_CINTerno = $database->select("Tbl_Gce_Artigos",["intCodInterno"], ["ORDER" => ["intCodInterno"=>"DESC"],"LIMIT" => 1]);
    if(sizeof($Tbl_CINTerno)==0){ $codInterno=1; } else {   $codInterno=$Tbl_CINTerno[0]['intCodInterno']+1; } 
 
    foreach($dados as $val){
        $id=$val['id'];
        $product_number=$val['product_number'];
        $productsshops=$val['productsshops'];
        $edition_date = $val['edition_date'];
        $description=$val['description'];

        $arrProducts['dtmAlteracao']=$edition_date;
        $arrProducts['strCodCategoria']=ERP_Val('inventoryTypes',$val['inventory_type']); 
        if($val['main_bar_code']!="" && $val['main_bar_code']!="0" && $val['main_bar_code']!="--" && strlen( $val['main_bar_code']>=4)){
        $arrProducts['strCodBarras']=$val['main_bar_code'];
        }         
       // $arrProducts['plu']=$val['plu'];   
        $arrProducts['strDescricao']=$description;
       // $arrProducts['with_holding_tax']=$val['with_holding_tax'];
        //$arrProducts['holding_tax_percent']=$val['holding_tax_percent'];
        $arrProducts['strTpArtigo']=ERP_Val('productTypes',$val['product_type']); 
        $arrProducts['strObs']=$val['comments'];
        // $arrProducts['percent_margin']=$val['percent_margin'];
        $arrProducts['strAbreviatura']=$val['short_description'];
        $arrProducts['strAbrevMedStk']=ERP_Val('units',$productsshops[0]['unit_id']);
        $arrProducts['strAbrevMedVnd']=ERP_Val('units',$productsshops[0]['unit_id']);
        $arrProducts['strAbrevMedCmp']=ERP_Val('units',$productsshops[0]['unit_id']);
        $arrProducts['intCodTaxaIvaVenda']=ERP_Val('vatstaxs',$productsshops[0]['vat_tax_id']);
        $arrProducts['intCodTaxaIvaVenda2']=ERP_Val('vatstaxs',$productsshops[0]['vat_tax2_id']);
        $arrProducts['strMotivoIsencaoIVA']=ERP_Val('vatstaxs',$productsshops[0]['exception_reason_name']);
        $arrProducts['strCodMotIVAIsencao']=ERP_Val('vatstaxs',$productsshops[0]['exception_reason_id']);
        $arrProducts['intCodTaxaIvaCompra']=ERP_Val('vatstaxs',$productsshops[0]['purchase_vat_tax_id']);
        $arrProducts['bitAfectaIntrastat']=1;

        
        /*
        if(!(array_key_exists('unit_id',$productsshops[0]))){
                echo $arrProducts['strDescricao'];
                print_r($id);
                die("");
        }*/ 
        // $arrProducts['short_description']=$val['short_description2'];
        // $arrProducts['pendent']=$val['pendent'];
        // $arrProducts['orders_description']=$val['orders_description'];
        // $arrProducts['infarmed_register_number']=$val['infarmed_register_number'];
        // $arrProducts['is_parapharmacy']=$val['is_parapharmacy'];

        if($val['product_type']=="S"){ // MOV Stock? Não sei como fazer... é no tipo de documento

        }
        
        if($val['image']!=""){ 
            $ficheiro0 = base64_decode($val['image']);	
            $ficheiro=unpack("H*hex", $ficheiro0); 
            $imgContent="0x".$ficheiro['hex'];	
            $arrProducts['imgImagem']=Medoo::raw('CONVERT(varbinary(MAX), '.$imgContent.')');
        } else {
            $arrProducts['imgImagem']=Medoo::raw('CONVERT(varbinary(MAX),\'\')');  
        }
        // $arrProducts['productscodsbars']=$val['productscodsbars']; 
        /*
        */

       if($ERP_scodArtigos=="main_bar_code"){  
            $dataCountQ = $database->select("Tbl_Gce_Artigos", ['Id'], ["strCodBarras" => $val['main_bar_code']]);
            $strCodigo=$val['main_bar_code'];
        } else {
            $dataCountQ = $database->select("Tbl_Gce_Artigos", ['Id'], ["strCodigo" => $product_number]);
            $strCodigo=$product_number;
        } 

        if(is_array($dataCountQ)){
            $dataCount = sizeof($dataCountQ); 
        } else {
            $dataCount = 0;   
        }
        ## INSERE REGISTO ##################################################################################
        if($dataCount==0){     

            $tp="insert"; //die(var_dump($database->debug()));

            $arrProducts['dtmAbertura']=$val['creation_date'];
            $arrProducts['strCodigo']=$strCodigo;
            $arrProducts['intCodInterno']=$codInterno++;

            $database->insert("Tbl_Gce_Artigos", $arrProducts);
            $erro=$database->error();
            $errormsg=$erro[2];
            
            if($errormsg==""){
                $log[]=array($tp,$entity,$strCodigo,0,"Artigo $description criado"); // tipo,entidade,nº cliente, sentido, msg
                syncLog(1,$entity,$tp,$strCodigo,"Artigo $description criado");
                $database->insert("USR_sync_taxonomy", ["techval"=>$id, "erpval"=>$strCodigo, "tipo"=>$entity,"dtasync"=>date('Y-m-d H:i:s')]);
            } else {  
                $log[]=array($tp,$entity,$strCodigo,0,$errormsg);
                syncLog(0,$entity,$tp,$strCodigo,$errormsg); 
            }			
        } else { // Update? #################################################################################
            $dataCountUpdt = $database->count("Tbl_Gce_Artigos", ['Id'], ["strCodigo" => $strCodigo, "dtmAlteracao[<]"=>$edition_date]);
            $tp="update";
            $errormsg="";
            if($dataCountUpdt==1){
                $database->update("Tbl_Gce_Artigos", $arrProducts, ["strCodigo" => $strCodigo]);
                $erro=$database->error();
                $errormsg=$erro[2]; 
                if($errormsg==""){
                    $log[]=array($tp,$entity,$strCodigo,0,"Artigo $description atualizado");
                    syncLog(1,$entity,$tp,$strCodigo,"Artigo $description atualizado");
                } else {
                    $log[]=array($tp,$entity,$strCodigo,0,$errormsg);   
                    syncLog(0,$entity,$tp,$strCodigo,$errormsg); 
                }
            }
        } 
        if($errormsg==""){
        ERP_AssocFamilia($strCodigo,@$productsshops[0]['family_id'],@$productsshops[0]['subfamily_id']);
            ERP_ArtigosPrecos($productsshops,$strCodigo); // ATUALIZA PREÇOS 
            //echo "<pre>"; print_r($productsshops);echo "</pre>";
        }
    } 

    } 
#######################################################################################################################
###################################################### DOCUMENTOS #####################################################
#######################################################################################################################

if($entity=="documentsclients"){  
    //print_r($ERP_ConfLojas);
    foreach($dados as $val){
        $id=$val['id'];
        $shop_id=$val['shop_id'];
        $type_doc_client_id=$val['type_doc_client_id'];
        $doc_year=$val['doc_year'];
        $strAbrevTpDoc=ERP_Val('typesdocsclients',$type_doc_client_id);
        $strCodExercicio=ERP_Exercicio($doc_year);
        $confLoja=$ERP_ConfLojas[$shop_id];
        $strCodSeccao=$confLoja['seccao'];
        //$doc_number=$val['doc_number'];
        $doc_number=ERP_upDateNumerador("Mov_Venda_Cab",$strAbrevTpDoc,$strCodExercicio,$strCodSeccao,1);
  
        //die($doc_number);

        $sincronizarDOC=0; 

        $sincronizarDOC=USR_sync_Val("typesdocsclients",$type_doc_client_id,'sync'); // isto ja vai do query de consulta ao ws
        if($sincronizarDOC=="1"){     

        $doc_serie=$val['doc_serie'];
        $doc_date=$val['doc_date'];
        $doc_time=$val['doc_time'];
        $doc_id=$val['doc_id'];
        $canceled=$val['canceled']; if($val['actual_doc_status']=="A"){ $canceled=1; }
        $total_discount=$val['total_discount'];
        $payment_condition_id=$val['payment_condition_id'];
        
        $DocLines=$val['docsclientslines'];
         
        if($strCodExercicio=="") {   die("Exercício $doc_year inexistente no ERP");  }
        
        $strCodCondPag = ERP_Val('paymentsconditions',$payment_condition_id);
        $intCodEntidade=$val['client_number'];
        $strAbrevSubZona=ERP_Get_SubzonaEnt($intCodEntidade); 

        $arrDoc['CAB']['strCodExercicio']=$strCodExercicio;
        $arrDoc['CAB']['strCodSeccao']=$strCodSeccao;
        $arrDoc['CAB']['strAbrevTpDoc']=$strAbrevTpDoc; if($strAbrevTpDoc=="") {   die("Tipo Doc $type_doc_client_id não sincronizado");  }
        $arrDoc['CAB']['strNumExterno']=$id;
        //$arrDoc['CAB']['strCodeSerie']=$doc_serie; 
        $arrDoc['CAB']['dtmData']=$doc_date;
        $arrDoc['CAB']['strHora']=$doc_time;
        $arrDoc['CAB']['dtmDataCarga']=$doc_date;
        $arrDoc['CAB']['strHoraCarga']=substr($doc_time,0,5);
        $arrDoc['CAB']['intNumero']=$doc_number;
        $arrDoc['CAB']['strNumero']=$doc_id;
        $arrDoc['CAB']['bitAnulado']=$canceled;
        $arrDoc['CAB']['intTpEntidade']=0;
        $arrDoc['CAB']['intCodEntidade']=$intCodEntidade;
        $arrDoc['CAB']['fltTotalDescontosFinSIVA']=$total_discount;
        $arrDoc['CAB']['strCodCondPag']=$strCodCondPag;
        $arrDoc['CAB']['dtmDataVencimento']=ERP_Get_DataVencim($doc_date,$strCodCondPag);
        $arrDoc['CAB']['dtmComissaoDataDisp']=$arrDoc['CAB']['dtmDataVencimento'];
        $arrDoc['CAB']['strAbrevSubZona']=$strAbrevSubZona;
        $arrDoc['CAB']['strAbrevMoeda']="EUR";
        $arrDoc['CAB']['strAbrevMoedaPagTroco']="EUR";
        $arrDoc['CAB']['fltCambio']=1; 
        $arrDoc['CAB']['fltCambioPagTroco']=1; 
        $arrDoc['CAB']['bitDocOrigemWEB']=1; 
        $arrDoc['CAB']['strObs']=$val['comments'];
        $arrDoc['CAB']['strCVDNumContrib']= $val['vat_number'];
        $arrDoc['CAB']['strCVDNome']=$val['name'];
        $arrDoc['CAB']['strCVDMorada']=$val['address'];
        $arrDoc['CAB']['strCVDCodPostal']=$val['postal_code'];
        $arrDoc['CAB']['strCVDLocalidade']=$val['local'];
        $arrDoc['CAB']['strCVDTelefone']=$val['client_phone'];
        //$arrDoc['CAB']['strCVDEmail']=$val['client_phone'];
        if($val['retention_value']>0){  $arrDoc['CAB']['bitCVDRetIRS']=1;  }
        $arrDoc['CAB']['fltIRSValorRetido']=$val['retention_value'];
        $arrDoc['CAB']['intCodVendedor']=ERP_Val('employees',$val['ic_employee_id']);

        //$arrDoc['CAB']['fltTotalPagamentos']=$val['payed'];
        //$arrDoc['CAB']['fltInfTotalArtigos']=
        //$arrDoc['CAB']['fltInfTotalLinhas']=
        //$arrDoc['CAB']['fltInfTotaQtd']=
        //$arrDoc['CAB']['fltValorPago']=
        //$arrDoc['CAB']['fltValorPendente']=
        //
        //$arrDoc['CAB']['intIVACodTaxa1']=
        //$arrDoc['CAB']['fltIVATaxa1']=
        //$arrDoc['CAB']['fltIVAIncidencia1']=
        //$arrDoc['CAB']['fltIVAValor1']=

        $intSinal=ERP_Get_Sinal($strAbrevTpDoc);

        $arrDoc['CAB']['intSinal']=$intSinal;
        $arrDoc['CAB']['strAplicacaoOrigem']="VNDP";
        $arrDoc['CAB']['strLogin']=$val['usr']; 
        $arrDoc['CAB']['strLoginEstado']=$arrDoc['CAB']['strLogin']; 
        $arrDoc['CAB']['strLoginCriacao']=$arrDoc['CAB']['strLogin']; 
        $arrDoc['CAB']['bitIvaIncluido']=$DocLines[0]['vat_included']; 
       // $arrDoc['CAB']['fltTotalMercadoriaSIVA']=$val['total_wo_vat']-$val['total_vat']*$intSinal; 
        $arrDoc['CAB']['fltTotalMercadoriaCIVA']=($val['total_w_vat']-$val['total_discount'])*$intSinal; 
       // $arrDoc['CAB']['fltTotalDescontosSIVA']=$val['total_discount'];
        $arrDoc['CAB']['fltTotalDescontosCIVA']=$val['total_discount'];


        $arrDoc['CAB']['fltSubTotal']=$val['total_wo_vat']; 
        $arrDoc['CAB']['fltTotal']=$val['total_w_vat']; 
        $arrDoc['CAB']['fltTotalToPay']=$val['total_w_vat']; 
        $arrDoc['CAB']['fltTotalIVA']=$val['total_vat']; 

        $arrDoc['CAB']['fltValorPendente']=$val['to_receive']; 
        $arrDoc['CAB']['fltValorPago']=$val['total_received']; 


        $arrDoc['CAB']['dtmDataAbertura']=$val['creation_date'];
        $arrDoc['CAB']['dtmDataEstado']=$val['creation_date'];
        $arrDoc['CAB']['dtmDataAlteracaoEstado']=$arrDoc['CAB']['dtmDataEstado'];
        $arrDoc['CAB']['dtmDataAlteracao']=$val['edition_date'];
        $arrDoc['CAB']['strHash']=$val['hash'];
        $arrDoc['CAB']['intHashControl']=$val['hashcontrol'];
        $arrDoc['CAB']['strSaftDocNO']=$doc_id;
        

        $dataCount = $database->count("Mov_Venda_Cab", ['Id'], ["strCodExercicio" => $strCodExercicio,"strCodSeccao" => $strCodSeccao,"strAbrevTpDoc" => $strAbrevTpDoc, "strNumExterno"=>$id]);
        ## INSERE REGISTO #########################################################################################
        if($dataCount==0 && $sincronizarDOC==1){      
            $tp="insert";
            $database->insert("Mov_Venda_Cab", $arrDoc['CAB']);
            $erroCAB=$database->error();
            $errormsgCab=$erroCAB[2];
            $Mov_Venda_Cab_ID = $database->id();
            //sleep(10);  
            

            if($errormsgCab==""){ 
                $log[]=array($tp,$entity,$doc_id,0,"Doc $doc_id criado"); // tipo,entidade,nº cliente, sentido, msg
                //syncLog(1,$entity,$tp,$product_number,"Artigo $description criado");
     
                ## Movimento Stock 
                $idCabStock=$database->insert("Mov_Stock_Cab", [
                    "strCodSeccao" => $strCodSeccao,
                    "strAbrevTpDoc" => $strAbrevTpDoc,
                    "strCodExercicio" => $strCodExercicio,
                    "intNumero" => $doc_number,
                    "strNumero" => $doc_id,
                    "dtmData" => $doc_date,
                    "dtmDateDoc" => $doc_date,
                    "bitAnulado" => $canceled,/*
                    "strMeioExpedicao" => "1",
                    "strLocalCarga" => $strLocalCarga,
                    "strLocalDescarga" => $strLocalDescarga,
                    "strObs" => $observacoes,*/
                    "fltTotal" => $val['total_w_vat'],
                    "strAplicacaoOrigem" => "VNDP"
                ]);

        ## INSERE LINHAS #########################################################################################
        $arrResumoIVA=array(); $intNumLinha=1; $fltTotalMercadoriaSIVA=$fltTotalDescontosSIVA=$quantidTotal=$fltInfTotalArtigos=0;
        foreach($DocLines as $kLin=>$vLin){
            $idLinha=$vLin['id'];

            if($vLin['is_comment']==0){

            $vat_tax_id=ERP_Val('vatstaxs',$vLin['vat_tax_id']);
            $vat_tax=number_format($vLin['vat_tax']);
            $total_vat=$vLin['total_vat'];
            $total_wo_vat=$vLin['total_wo_vat'];
            $arrResumoIVA[$vat_tax][]=array("taxa"=>$vat_tax,"codTaxa"=>$vat_tax_id,"total_vat"=>$total_vat,"incidencia"=>$total_wo_vat);
            
            $quantidTotal=($vLin['quantity']*$intSinal)+$quantidTotal;
            $fltInfTotalArtigos++;	

            $bitNaoMovStk=ERP_artMovStock($vLin['product_number']);
            $arrDoc['LIN']['bitNaoMovStk']=$bitNaoMovStk==1 ? 1:0; 
            $arrDoc['LIN']['fltPrecoUnitario']=$vLin['price_un_w_vat'];
            $arrDoc['LIN']['fltValorLiquido']=$vLin['total_wo_vat']*$intSinal; 
            $fltTotalMercadoriaSIVA=($vLin['price_un_wo_vat']*$vLin['quantity'])+$fltTotalMercadoriaSIVA;

            $arrDoc['LIN']['intCodTaxaIVA']=$vat_tax_id; 
            $arrDoc['LIN']['fltTaxaIVA']=$vat_tax; 
            $arrDoc['LIN']['fltValorAPagar']=$vLin['total_w_vat'];
            $arrDoc['LIN']['strCodArtigo']=$vLin['product_number'];
            $arrDoc['LIN']['strCodArmazem']=ERP_Val('warehouses',$vLin['warehouse_origin_id']);  
            $arrDoc['LIN']['intTpLinha']=5; 
            $arrDoc['LIN']['fltDesconto1']=$vLin['discount_percent'];
            $arrDoc['LIN']['fltDesconto2']=$vLin['discount_percent2'];

            $fltValorDescontosCIVA=$vLin['discount_value']*$intSinal; 
            $fltValorDescontosSIVA=$fltValorDescontosCIVA/(($vat_tax/100)+1); 
            $arrDoc['LIN']['fltValorDescontosCIVA']=$fltValorDescontosCIVA;
            $arrDoc['LIN']['fltValorDescontosSIVA']=$fltValorDescontosSIVA;

            $fltTotalDescontosSIVA=$fltValorDescontosSIVA+$fltTotalDescontosSIVA;
       
            
            } else { // LINHA DE COMENTÁRIOS
            $arrDoc['LIN']['bitNaoMovStk']=1;  
            $arrDoc['LIN']['intTpLinha']=0;  
            }
            $arrDoc['LIN']['strCodExercicio']=$strCodExercicio;
            $arrDoc['LIN']['strCodSeccao']=$strCodSeccao;
            $arrDoc['LIN']['strAbrevTpDoc']=$strAbrevTpDoc;
            $arrDoc['LIN']['intNumero']=$doc_number;
            $arrDoc['LIN']['intNumLinha']=$intNumLinha;
            $arrDoc['LIN']['fltQuantidade']=$vLin['quantity']*$intSinal;
            $arrDoc['LIN']['strDescArtigo']=$vLin['product_description'];  

            $database->insert("Mov_Venda_Lin", $arrDoc['LIN']);
            $erroLinha=$database->error();
            $errormsgLinha=$erroLinha[2];
            unset($arrDoc['LIN']);   
            
            if($errormsgLinha!=""){
                /*  */   
                $database->delete("Mov_Stock_Cab", ["strCodSeccao" => $strCodSeccao,"strCodExercicio" => $strCodExercicio,"strAbrevTpDoc"=>$strAbrevTpDoc,"intNumero"=>$doc_number]);
                $database->delete("Mov_Venda_LIN", ["strCodSeccao" => $strCodSeccao,"strCodExercicio" => $strCodExercicio,"strAbrevTpDoc"=>$strAbrevTpDoc,"intNumero"=>$doc_number]); 
                $database->delete("Tbl_SAFT_UniqueKey", ["strCodSeccao" => $strCodSeccao,"strCodExercicio" => $strCodExercicio,"strAbrevTpDoc"=>$strAbrevTpDoc,"intNumero"=>$doc_number]); 
                $database->delete("Mov_Venda_Cab", ["strCodSeccao" => $strCodSeccao,"strCodExercicio" => $strCodExercicio,"strAbrevTpDoc"=>$strAbrevTpDoc,"intNumero"=>$doc_number]); 
                ERP_upDateNumerador("Mov_Venda_Cab",$strAbrevTpDoc,$strCodExercicio,$strCodSeccao); 
                
                //print_r($database->log()); 
                /**/
               $msg="$doc_id sincronizar=$sincronizarDOC IDCAB=$Mov_Venda_Cab_ID Linha $intNumLinha (artigo ".$vLin['product_number'].")  <b>|$strAbrevTpDoc|$strCodExercicio|$strCodSeccao|$doc_number|</b> => ".$errormsgLinha;
               echo "<pre>";
               echo $msg; 
               print_r($database->log());  
               echo "</pre>";   
               echo "<hr>";   die();
            } else {  
                if($bitNaoMovStk==0){  ## Movimento Stock
                    $database->insert("Mov_Stock_Lin", [
                        "strCodSeccao" => $strCodSeccao,
                        "strAbrevTpDoc" => $strAbrevTpDoc,
                        "strCodExercicio" => $strCodExercicio,
                        "intNumero" => $doc_number,
                        "intNumLinha" => $intNumLinha,
                        "strCodArmazem" => ERP_Val('warehouses',$vLin['warehouse_origin_id']),
                        "strCodArtigo" => $vLin['product_number'],
                        "fltQuantidade" => $vLin['quantity'],
                        "fltSinal" => -1,
                        "fltValorUnitario" => $vLin['price_un_w_vat'],
                        "fltSubTotal" => ($vLin['total_wo_vat']*$intSinal)+($vLin['discount_value']*$intSinal),
                        //"strCodClassMovStk" => 7,
                        "strFormula" => 1,
                        "dtmDataValor" =>$doc_date,
                        "intNumLinhaDocOri" =>$intNumLinha,
                        "dtmDataRecStock" => $doc_date
                    ]);
                    }
            }
            $intNumLinha++;     

        }  //print_r($arrResumoIVA);
        ## /INSERE LINHAS ########################################################################################
        $taxasIva=array_keys($arrResumoIVA);
        $i=1;
        foreach($taxasIva as $v){
            $arrDoc['CABT']['intIVACodTaxa'.$i]=$arrResumoIVA[$v][0]['codTaxa']; 
            $arrDoc['CABT']['fltIVATaxa'.$i]=$arrResumoIVA[$v][0]['taxa']; 
            $arrDoc['CABT']['fltIVAValor'.$i]=sumArrRecursive($arrResumoIVA[$v],'total_vat');
            $arrDoc['CABT']['fltIVAIncidencia'.$i]=sumArrRecursive($arrResumoIVA[$v],'incidencia');
            $i++;
        }
        $arrDoc['CABT']['fltTotalMercadoriaSIVA']=$fltTotalMercadoriaSIVA; 
        $arrDoc['CABT']['fltTotalDescontosSIVA']=$fltTotalDescontosSIVA; 
        $arrDoc['CABT']['fltInfTotalQtd']=$quantidTotal; 
        $arrDoc['CABT']['fltInfTotalArtigos']=$fltInfTotalArtigos; 
        
        $database->update("Mov_Venda_Cab", $arrDoc['CABT'], ["strCodExercicio" => $strCodExercicio,"strCodSeccao" => $strCodSeccao,"strAbrevTpDoc" => $strAbrevTpDoc, "intNumero"=>$doc_number]);
        ERP_upDateNumerador("Mov_Venda_Cab",$strAbrevTpDoc,$strCodExercicio,$strCodSeccao,1);
        syncLog(1,$entity,$tp,$doc_id,"Documento $doc_id sincronizado");
    } else {
            $log[]=array($tp,$entity,$doc_id,0,$errormsgCab);
            //syncLog(0,$entity,$tp,$product_number,$errormsg); 
            die("erro no CAB ".$errormsgCab);    
    }			


        //echo $id."<br>";

    }

 
    }  else { /*echo "<pre>$type_doc_client_id => Sincronizar=$sincronizarDOC ($doc_number não sincronizado)</pre>";*/ }  // if($sincronizar==1){
        unset($arrDoc,$sincronizarDOC); 
    } // foreach($dados as $val){

}
#######################################################################################################################
##################################################### /DOCUMENTOS #####################################################
#######################################################################################################################
echo "<pre>";
    print_r($log);
    echo "</pre>"; 

    if(sizeof($dados)==200){ 
        TechAPI_Entities($entity,$condition,$id,"",1); // TechAPI_Entities($entity,$condition="",$lastId="",$order="order by id ASC",$sync=0){
    }      
   // $myLastElement = $dados[array_key_last($dados)]; 


   // echo $condition;
   // print_r($dados);
    
}


#######################################################################################################################
############################################### FUNÇÕES ETICADATA #####################################################
#######################################################################################################################

function ERP_Exercicio($ano){
    global  $database;
    $Query_exerc=$database->query("SELECT TOP (1) strCodigo FROM Tbl_Exercicios WHERE YEAR(dtmInicio)='$ano'")->fetchAll(PDO::FETCH_ASSOC);	
    if(is_array($Query_exerc) && sizeof($Query_exerc)==1){
    $Codexercicio=$Query_exerc[0]['strCodigo'];
    return $Codexercicio;
    }  
}

function ERP_Get_DataVencim($dtmData,$strCodigo){
    global  $database;
    $ent=ERP_Entities("Tbl_CondPagamento",["intDias"],["strCodigo"=>$strCodigo]);
    $nDays=number_format($ent[0]['intDias']);
    return  date('Y-m-d', strtotime($dtmData. ' + '.$nDays.' days'));
}
function ERP_Get_SubzonaEnt($codEntidade,$tpEnt="Tbl_Clientes"){
    global $database,$ERP_strCodSubZona;
    $ent=ERP_Entities($tpEnt,["strAbrevSubZona"],["intCodigo"=>$codEntidade]);
    if(sizeof($ent)>0){
    $resultado=$ent[0]['strAbrevSubZona'];
    return $resultado;
    } else {
        return $ERP_strCodSubZona;  
    }
}

function ERP_upDateNumerador($tbl="Mov_Venda_Cab",$strAbrevTpDoc,$strCodExercicio,$strCodSeccao,$incrementa=0){
    global $database;
    $rNum=$database->select($tbl, ["intNumero"], ["strCodExercicio" => $strCodExercicio,"strCodSeccao" => $strCodSeccao,"strAbrevTpDoc" => $strAbrevTpDoc, "LIMIT" => 1, "ORDER" => ["intNumero"=>"DESC"]]);
    if(sizeof($rNum)==0){
        $lnum=1;
    } else {
    if($incrementa==0){
        $lnum=$rNum[0]['intNumero'];     
    }
    if($incrementa==1){
        $lnum=$rNum[0]['intNumero']+1;     
    }  
} 

    $database->update("Tbl_Numeradores", [ "intNum_Mes00" => $lnum
        ],[
            "strAbrevTpDoc" => $strAbrevTpDoc,
            "intTpNumerador" => 1,
            "strCodExercicio" => $strCodExercicio,
            "strCodSeccao" => $strCodSeccao
        ]);	
   

    return $lnum;
}


/*
function ERP_serie_Uptd($serie,$strAbrevTpDoc){
    global $database;
    $rNum=$database->select("Tbl_Series", ["strCode"], ["strCode" => $serie, "strAbrevTpDoc" => $strAbrevTpDoc, "LIMIT" => 1, "ORDER" => ["Id"=>"DESC"]]);
    if(sizeof($rNum)==0){
      
    } else {
       
    }  
} 
*/


function ERP_artMovStock($strCodigo){
    global $database;

    $Q_artigo=$database->select("Tbl_Gce_Artigos", ["bitNaoMovStk"],["strCodigo" => $strCodigo,"ORDER" => ["Id" => "DESC"], "LIMIT" => 1]);	
    //echo var_dump($database->debug());
    if(is_array($Q_artigo) && sizeof($Q_artigo)>0){
        $bitNaoMovStk=number_format($Q_artigo[0]['bitNaoMovStk']);	
    } else {
        $bitNaoMovStk=0;
    }

return $bitNaoMovStk;

}

function ERP_Get_Sinal($strCodigo){
    global  $database;
     //intTpNatureza 1=credito, 0=debito
    $ent=ERP_Entities("Tbl_Tipos_Documentos",["intTpNatureza"],["strAbreviatura"=>$strCodigo]);
    //print_r($ent[0]['intTpNatureza']); 
    if($ent[0]['intTpNatureza']==1){
        return -1;
    } else {
        return 1;
    }
}





function ERP_ArtigosPrecos($productsshops,$codProduto) {
    global  $database,$ERP_ConfLojas;

    /*
    $arrLojas=array();
    foreach($ERP_ConfLojas as $k=>$v){
        if($v['sync']==1){
        $arrLojas[]=$k;
        } 
    }
    */ 
    foreach($productsshops as $k=>$v){
       // if(in_array($v['shop_id'],$arrLojas)){
        //die(print_r($v['price_wo_vat1']));
            $i = 1;
            while ($i <= 4) { 
            $rNum=$database->select("Tbl_Gce_ArtigosPrecos", ["intNumero"], ["strCodArtigo" => trim($codProduto), "intNumero"=>$i]);
            /*$erro=$database->error();
            $errormsg=$erro[2]; 
            if($errormsg!=""){
                echo $errormsg;   print_r($database->log()); //die();
            }*/ 
            if(is_array($rNum) && sizeof($rNum)==0 && $v['price_wo_vat'.$i.'']>0){      
                $database->insert("Tbl_Gce_ArtigosPrecos", ["strCodArtigo"=>$codProduto,"fltPreco"=>$v['price_wo_vat'.$i.''],"strAbrevMoeda"=>"EUR","intNumero"=>$i]);
                $erro=$database->error();
                $errormsg=$erro[2]; 
                if($errormsg!=""){
                    echo $errormsg;   
                }
            } else { // ATUALIZA 
                $database->update("Tbl_Gce_ArtigosPrecos", ["fltPreco"=>$v['price_wo_vat'.$i.''],"strAbrevMoeda"=>"EUR","intNumero"=>$i], ["strCodArtigo" => trim($codProduto), "intNumero"=>$i]); 
                $erro=$database->error(); 
                $errormsg=$erro[2]; 
                if($errormsg!=""){
                    echo $errormsg;     
                }     
            }  

            $i++;
        }
        }

    //}
   
}




#######################################################################################################################
############################################## /FUNÇÕES ETICADATA #####################################################
#######################################################################################################################

function sumArrRecursive($array,$chave){
$sum = 0;
$array_obj = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
foreach($array_obj as $key => $value) {
    if($key == $chave)
        $sum += $value;
} 
return $sum; 
}

function cutNum($num, $precision = 2) {
    return floor($num*100)/100;
}

function getEntitiesTosync($taxonomy="typesdocsclients"){
    global  $database,$TECHAPI_shop_number;

    $Query=$database->select("USR_sync_taxonomy", ["erpval","id"],["tipo" => $taxonomy,"sync" => 1,"shop_number"=>$TECHAPI_shop_number,"ORDER" => ["Id" => "DESC"]]);	
    return $Query; 
}


function ERP_AssocFamilia($strCodArtigo,$family_id="",$subfamily_id=""){
    global  $database,$ERP_strCodTpFamilia,$ERP_strCodTpSFamilia,$ERP_OPT_criaAutoFamilia,$ERP_OPT_criaAutoSFamilia;

    if($family_id!=""){
        $famERP=ERP_Val('families',$family_id); 
        if($famERP=="" && $ERP_OPT_criaAutoFamilia==1) { $famERP=ERP_CriaRecAuto($family_id,$ERP_strCodTpFamilia,"families",1); }    
        if($famERP!=""){
            $famCount = $database->count("Tbl_Gce_ArtigosFamilias", ['Id'], ["strCodArtigo" => $strCodArtigo,"strCodTpNivel" => $ERP_strCodTpFamilia]);
            if($famCount==0){   $database->insert("Tbl_Gce_ArtigosFamilias", ["strCodArtigo"=>$strCodArtigo,"strCodTpNivel"=>$ERP_strCodTpFamilia,"strCodFamilia"=>$famERP]);} else {
                $dataUpdt =  $database->update("Tbl_Gce_ArtigosFamilias", ["strCodFamilia"=>$famERP], ["strCodArtigo" => $strCodArtigo,"strCodTpNivel" => $ERP_strCodTpFamilia, "strCodFamilia[!]"=>$famERP]); 
                if($dataUpdt->rowCount()==1){
                $famTECH=Tech_Val('families',$famERP);     
                syncLog(1,'products','updatefamily',$strCodArtigo,"Familia atualizada ".$famTECH."=>".$famERP.""); 
                }   
            } 
        } 
    }

    if($subfamily_id!=""){
        $sfamERP=ERP_Val('subfamilies',$subfamily_id);
        if($sfamERP=="" && $ERP_OPT_criaAutoSFamilia==1) { $sfamERP=ERP_CriaRecAuto($subfamily_id,$ERP_strCodTpSFamilia,"subfamilies",1); }
        if($sfamERP!=""){
            $sfamCount = $database->count("Tbl_Gce_ArtigosFamilias", ['Id'], ["strCodArtigo" => $strCodArtigo,"strCodTpNivel" => $ERP_strCodTpSFamilia]);
            if($sfamCount==0){   $database->insert("Tbl_Gce_ArtigosFamilias", ["strCodArtigo"=>$strCodArtigo,"strCodTpNivel"=>$ERP_strCodTpSFamilia,"strCodFamilia"=>$sfamERP]);} else {
                $dataUpdt =   $database->update("Tbl_Gce_ArtigosFamilias", ["strCodFamilia"=>$sfamERP], ["strCodArtigo" => $strCodArtigo,"strCodTpNivel" => $ERP_strCodTpSFamilia, "strCodFamilia[!]"=>$sfamERP]); 
                if($dataUpdt->rowCount()==1){
                    $sfamTECH=Tech_Val('subfamilies',$sfamERP);
                    syncLog(1,'products','updatesubfamily',$strCodArtigo,"Sub-familia atualizada ".$sfamTECH."=>".$sfamERP.""); 
                }   
            } 
        }
    }
}


function ERP_CriaRecAuto($family_id,$ERP_strCodTpFamilia,$taxonomy="families",$associa=1){
    global  $database;

    if($taxonomy=="families"){
        $arrFields=array('family_number','description');
        $tabela="Tbl_Gce_Familias";
    }
    if($taxonomy=="subfamilies"){
        $arrFields=array('id','description');
        $tabela="Tbl_Gce_Familias";
    }

    $entidade=TechAPI_GET_Entity($taxonomy,$family_id,$arrFields); 
 
    if(is_array($entidade) && sizeof($entidade)>0){
        $strCodigo=substr($entidade[$arrFields[0]],-6);  
        $strDescricao=$entidade[$arrFields[1]];

        $famCount = $database->count($tabela, ['Id'], ["strCodigo" => $strCodigo,"strCodTpFamilia" => $ERP_strCodTpFamilia]);

        if($famCount==0){
            $database->insert($tabela, ["strCodigo"=>$strCodigo,"strDescricao"=>$strDescricao,"strCodTpFamilia"=>$ERP_strCodTpFamilia]);
            $erro=$database->error();
            $errormsg=$erro[2]; 
            if($errormsg==""){
                syncLog(1,$taxonomy,'insert',$family_id,"Registo $strDescricao criado ($tabela)"); 
            }
            if($associa==1 && $errormsg==""){
                $database->insert("USR_sync_taxonomy", ["techval"=>$family_id, "erpval"=>$strCodigo, "tipo"=>$taxonomy,"dtasync"=>date('Y-m-d H:i:s')]);
                syncLog(1,$taxonomy,'insert',$family_id,"Taxonomia criada");    
            }      
        }
        return $strCodigo;
    }
}


function syncLog($tipo,$entity,$act,$recId,$msg){  
    // $tipo = 1 (success) // 2 (info) // 0 (error)
	global  $database,$TECHAPI_shop_number; 
    $database->insert("USR_sync_log", ["tipo"=>$tipo, "act"=>$act, "entity"=>$entity, "recnum"=>$recId, "msg"=>$msg,"dta"=>date('Y-m-d H:i:s'),"shop_number"=>$TECHAPI_shop_number]);
} 


?>