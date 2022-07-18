<?php $ptitle="Sincronização";	include("header.php"); ?>

<!-- Smart Wizard -->

<p>Este wizard ajuda-o a sincronizar os Produtos do ERP com a sua Loja Online</p>
<div id="wizard2" class="form_wizard wizard_horizontal">
  <ul class="wizard_steps">
    <li> <a href="#step-1"> <span class="step_no">1</span> <span class="step_descr"> Passo 1<br />
      <small>Tipo de sincronização</small> </span> </a> </li>
    <li> <a href="#step-2"> <span class="step_no">2</span> <span class="step_descr"> Passo 2<br />
      <small>Descrição</small> </span> </a> </li>
    <li> <a href="#step-3"> <span class="step_no">3</span> <span class="step_descr"> Passo 3<br />
      <small>Progresso</small> </span> </a> </li>
  </ul>
  <div id="step-1">
    <form class="form-horizontal form-label-left">
      <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="first-name">Tipo <span class="required">*</span> </label>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <select class="form-control" name="tpo" id="tpo">
            <option value="">Escolha 1 opção</option>
            <option value="ArtwebToErp">Do website para o ERP</option>
            <option value="ArtStock">Sincronizar Stocks</option>
          </select>
        </div>
      </div>
    </form>
  </div>
  <div id="step-2">
    <h2 class="StepTitle">Descrição</h2>
    <span class="spandescr" id="spanArtwebToErp">
    <p> Esta rotina irá atualizar no ERP o campo interno que indica que o artigo existe ou não na sua loja online. Útil para quando elimina artigos no backoffice do seu website e atualizar essa informação no ERP. </p>
    <p> Este é um processo seguro e utiliza um campo do seu ERP que não tem grande impacto mas que é de grande utilidade para esta plataforma. </p>
    <p> Não irá criar nem alterar nenhum artigo no ERP nem na loja online, apenas serve para atualizar registos de sincronização.</p>
  </span>
  
   <span class="spandescr" id="spanArtStock">
    <p>Esta rotina permite atualizar os stocks no website a partir do ERP</p>
  </span>  
  
  
  </div>
  <div id="step-3">
    <div align="center" class="textoDetalhe"><span class="" id="textoDetalhe"></span> </div>
    <div class="progress " id="progress">
      <div class="progress-bar progress-bar-warning" data-transitiongoal="0"></div>
    </div>
  </div>
</div>
<!-- End SmartWizard Content -->

<?php include("footer.php"); ?>