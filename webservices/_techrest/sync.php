<?php include('index.php');


if(isset($_GET['browser']) && $_GET['browser']==1){
echo'<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>


    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
   
    <!-- bootstrap-daterangepicker -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

 
<meta name="robots" content="noindex">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container"> <div class="top_nav"></div> <div class="right_col" role="main">';

}


$order="order by id ASC";



if($act=="all"){     //die($ERP_dataIniSync);
    if($tp=="documentsclients" ){
        $totalRes="where doc_date>='".$ERP_dataIniSync."'";
    }  
    else  if($tp=="products" ){
        $totalRes="where creation_date>='".$ERP_dataIniSync." 00:00:00'";    
    }   
    else  if($tp=="clients" ){
        $totalRes="where creation_date>='".$ERP_dataIniSync." 00:00:00'";    
    }    
    //$totalRes="where id='2308081927266687067491L1P1'";   
    //$order="order by doc_number ASC";     
}    
else if($act=="date"){  
    if(!isset($_GET['date'])){
        $dte=date('Y-m-d');
    }   else {
        $dte=$_GET['date'];
    }         
    if($tp=="products" ){
         $totalRes="where creation_date='".$dte."' OR edition_date='".$dte."'";  
       // $totalRes=" where product_number='37'";   
    } else { 
    $totalRes="where doc_date='".$dte."'";  
    }

    $Amanha=date('Y-m-d', strtotime('+1 day', strtotime($dte)));
    $Ontem=date('Y-m-d', strtotime('-1 day', strtotime($dte)));

    if($dte!=date('Y-m-d')){
    echo "<br><br><a id=\"btnSyncManual\" class=\"btn btn-lg btn-info\" href=\"/sync.php?tp=".$tp."&act=date&date=".$Amanha."&auth_userid=".$tokenAPI."&browser=1&loja=".$TECHAPI_shop_number."\">$Amanha <span class=\"fa fa-arrow-right\"></span></a><br><br>";
    } 
}   
else {
    $totalRes="where edition_date>='".date('Y-m-d')." 00:00:00'";
}

# * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
if($tp=="clients" || $tp=="products" || $tp=="documentsclients"){ 
    // https://tr.erpsinc.pt/sync.php?tp=clients&act=&auth_userid=r87anjpcj63pjb9D

    // die(ERP_artMovStock("1000033"));
    if($tp=="documentsclients"){
    //$totalRes="where doc_date<='2020-04-04' AND type_doc_client_id IN ('00000000001L1P0')"; 
    // $totalRes="where doc_date>='2023-01-01'";
    //  $totalRes="where doc_id='FR 101L1A23P1/2'"; 
    // $totalRes="where doc_id='NC 106L1A23P1/1'"; 
    //  $totalRes="where doc_date='2023-01-19'";    
    }
        // echo $totalRes;   
 
    TechAPI_Entities($tp,$totalRes,"",$order,1);    
}

if($tp=="robot"){ 

    $listLojas=listLojas(array('shop_number'),array("sync"=>1));
    foreach($listLojas as $v){
        TechAPI_Login($v['shop_number']);
        sleep(2);
        $_SESSION['loja']=$v['shop_number'];

         TechAPI_Entities("clients",$totalRes,"","",1); 
         sleep(10);
         TechAPI_Entities("products",$totalRes,"","",1); 
         sleep(10);
         TechAPI_Entities("documentsclients",$totalRes,"","",1);
    }   
    // die(print_r($listLojas));
    //die("Loja:$TECHAPI_shop_number $totalRes");
}


if(isset($_GET['browser']) && $_GET['browser']==1){
    echo'</div></div></div></body>
    

    <script src="https://www.erpsinc.pt/backoffice/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/fastclick/lib/fastclick.js"></script>
        <script type="text/javascript">
    $(document).ready(function(){';
        if($act=="date" && isset($_GET['date']) && (str_replace("-","",$_GET['date'])<str_replace("-","",date('Y-m-d')))){ echo'
        var href = $("#btnSyncManual").attr(\'href\');
        setTimeout(function() {
        window.location.href = href; 
        console.log("click: "+href);
        }, 5000); ';
        } 
        echo'});
    </script>
    </html>';
}

?>