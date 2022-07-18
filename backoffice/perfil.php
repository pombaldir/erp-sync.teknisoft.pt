<?php 
$ptitle="Perfil";
$tpPag=2;
include("header.php"); 
$utilizador=get_user_full($_SESSION['user_id'], $mysqli);
?>
<?php if($act==""){ 



?>

<!-- page content -->

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>O meu perfil</h3>
      </div>
      <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Pesquisar...">
            <span class="input-group-btn">
            <button class="btn btn-default" type="button">Ok</button>
            </span> </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Detalhes de utilizador <small>O meu perfil</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="<?php echo URLBASE;?>/settings">Definições</a> </li>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
              <div class="profile_img">
                <div id="crop-avatar"> 
                  <!-- Current avatar -->
                  
                  <div class="thumbnail">
                    <?php 
$fotoatual=$utilizador['foto']; 
 if($fotoatual!=""){	
 echo '<img width=\"200\" class=\"img-responsive avatar-view\" src="data:image/jpeg;base64,'.base64_encode( $fotoatual ).'"/>';
 } else {
echo "<img width=\"200\" class=\"img-responsive avatar-view\" src=\"images/user.png\" alt=\"\">";	 
}
?>
                  </div>
                </div>
              </div>
              <h3><?php echo $_SESSION['nome']; ?></h3>
              <ul class="list-unstyled user_data">
                <!--
                        <li><i class="fa fa-map-marker user-profile-icon"></i> San Francisco, California, USA
                        </li>

                        <li>
                          <i class="fa fa-briefcase user-profile-icon"></i> Software Engineer
                        </li>
                        -->
                
                <li class="m-top-xs"> <i class="fa fa-envelope user-profile-icon"></i> <a href="#"><?php echo $utilizador['email'];?></a> </li>
              </ul>
              <a class="btn btn-success" href="<?php echo URLBASE;?>/perfil/edit/0"><i class="fa fa-edit m-right-xs"></i> Editar Perfil</a> <br />
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Atividade Recente</a> </li>
                  <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Notas</a> </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab"> 
                    
                    <!-- start recent activity -->
                    <ul class="messages">
                      <?php
$query = $mysqli->query("select accao,DATE_FORMAT(data_ad, '%d/%m/%Y %H:%i') as data_ad,MONTHNAME(data_ad) as mes,DATE_FORMAT(data_ad, '%d') as dia from logs order by idnum desc limit 0,5") or die($mysqli->errno .' - '. $mysqli->error);
while($dados = $query->fetch_array()){
	
$accao=$dados['accao'];	


switch ($accao) {
    case "Login":
        $icone_a="fa-sign-in";
        break;
    case "cake":
        case "Logout":
        $icone_a="fa-sign-out";
}			  
 ?>
                      <li> <i class="icone1 fa <?php echo $icone_a;?>"></i>
                        <div class="message_date">
                          <h3 class="date text-info"><?php echo $dados['dia'];?></h3>
                          <p class="month"><?php echo $dados['mes'];?></p>
                        </div>
                        <div class="message_wrapper">
                          <h4 class="heading"><?php echo $dados['accao'];?></h4>
                          <blockquote class="message">
                            <h6><?php echo $dados['data_ad'];?></h6>
                          </blockquote>
                          <br />
                          <!-- 
                                  <p class="url">
                                    <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
                                    <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                                  </p> --> 
                        </div>
                      </li>
                      <?php } ?>
                    </ul>
                    <!-- end recent activity --> 
                    
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                    <p> 
                      <!-- text area -->
                      
                    <form class="form-horizontal form-label-left input_mask" id="notasform" name="notasform">
                      <input type="hidden" name="accaoP" id="accaoP" value="editNotes">
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-3 col-xs-12">Notas </label>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <textarea rows="8" name="notes" id="notes" class="form-control col-md-12 col-xs-12"><?php echo $utilizador['notes'];?></textarea>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                          <button class="btn btn-primary" type="reset">Apagar</button>
                          <button type="submit" class="btn btn-success">Submeter</button>
                        </div>
                      </div>
                    </form>
                    
                    <!-- /text area -->
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- /page content -->

<?php } if($act=="edit"){ 



$query = $mysqli->query("select * from members where id=".$_SESSION['user_id']."") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();

?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Editar Perfil</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Perfil<small></small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content"> <br />
            <form class="form-horizontal form-label-left input_mask" id="userform" name="userform" novalidate>
              <div class="row">
                <div class="col-md-3"> 
                  
                  <!-- -->
                  
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 100%">
                      <?php   

if(is_file(DOCROOT."/images/user".$_SESSION['user_id'].".jpg")){
 	echo "<img id=\"fotouser\" src=\"".URLBASE."/images/user".$_SESSION['user_id'].".jpg\" alt=\"\">";	 	 
 }  else {
	echo "<img id=\"fotouser\" src=\"".URLBASE."/images/user.png\" alt=\"\">"; 
 }
?>
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                    <div> <span class="btn btn-default btn-file"><span class="fileinput-new">Alterar imagem</span><span class="fileinput-exists">Alterar</span>
                      <input type="file" id="uploadfile" name="uploadfile" />
                      </span> <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a> </div>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <input type="text" class="form-control has-feedback-left" id="nome" name="nome" value="<?php echo $dados['nome'];?>" placeholder="Nome e Apelido">
                    <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span> </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <input type="text" class="form-control" id="tlm" name="tlm" placeholder="Telefone" value="<?php echo $dados['tlm'];?>" >
                    <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span> </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <input type="email" class="required form-control has-feedback-left" id="email" name="email" placeholder="Email" value="<?php echo $dados['email'];?>" required="required">
                    <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span> </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback"> </div>
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12"> </div>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label for="password" class="control-label col-md-3">Nova Password</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input id="password" type="password" name="password" data-validate-length="6,8" class="optional form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Repita Password</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input id="password2" type="password" name="password2" data-validate-linked="password" class="optional form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-9">
                  <button class="btn btn-primary" type="reset">Cancelar</button>
                  <button type="submit" class="btn btn-success" id="submitbtn">Submeter</button>
                </div>
              </div>
              <input type="hidden" name="accaoP" id="accaoP" value="edit">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<?php include("footer.php"); ?>