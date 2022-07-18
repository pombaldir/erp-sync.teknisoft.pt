<?php $ptitle="Sincronização";	include("header.php"); ?>

<!-- Smart Wizard -->

<p>Este wizard ajuda-o a sincronizar as Famílias do ERP e as categorias da sua Loja Online</p>
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
            <option value="CatwebToErp">Do website para o ERP</option>
            <option value="FamErpReset">Remover relação de famílias</option>
          </select>
        </div>
      </div>
    </form>
  </div>
  <div id="step-2">
    <h2 class="StepTitle">Descrição</h2>
    <p> Esta rotina irá atualizar no ERP o campo interno que indica que a família foi ou não alterada na sua loja online. Útil para quando elimina ou altera categorias no backoffice do seu website e atualizar essa informação no ERP. </p>
    <p> Este é um processo seguro e utiliza um campo do seu ERP que não tem grande impacto mas que é de grande utilidade para esta plataforma. </p>
     <p> Não irá criar nem alterar nenhuma família no ERP nem na loja online, apensas server para atualizar registos de sincronização.</p>
    
    
  </div>
  <div id="step-3"> 
  
  
  <div class="progress right" id="progress">
                        <div class="progress-bar progress-bar-warning" data-transitiongoal="0"></div>
                      </div>
                      
                   <div id="texto2" align="center"><span id="textomsg" class="textomsg"></span></div>
  
  
  
  </div>
</div>
<!-- End SmartWizard Content -->

<?php include("footer.php"); ?>