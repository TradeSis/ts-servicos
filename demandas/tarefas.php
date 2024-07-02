<?php
//Lucas 07112023 id965 - Melhorias Tarefas 
// lucas id654 - Melhorias Tarefas
// Lucas 17102023 novo padrao
//Gabriel 06102023 ID 596 mudanças em agenda e tarefas 
//lucas 25092023 ID 358 Demandas/Comentarios
// Gabriel 22092023 id 544 Demandas - Botão Voltar
// gabriel 04082023


include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/tarefas.php');
include_once(__DIR__ . '/../database/demanda.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');


//Lucas 22112023 id 688 - Removido visão do cliente ($ClienteSession)

$clientes = buscaClientes();
$atendentes = buscaAtendente();
$ocorrencias = buscaTipoOcorrencia();
//Lucas 09112023 id965 - removido variavel $demandas
//lucas 25092023 ID 358 Adicionado buscaUsuarios
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);

if ($_SESSION['administradora'] == 1) {
  $idCliente = null;
} else {
  $idCliente = $usuario["idCliente"];
}

if ($_SESSION['administradora'] == 1) {
  $idAtendente = $usuario["idUsuario"];
} else {
  $idAtendente = null;
}
$statusTarefa = "1"; //ABERTO

//Lucas 07112023 id965 - removido variavel do filtro Periodo
$filtroEntrada = null;
$idTipoOcorrencia = null;
$PeriodoInicio = null;
$PeriodoFim = null;
 // lucas 07112023 id654 - Removido PrevistoOrderm e RealOrdem, e adicionado dataOrdem no lugar
$dataOrdem = null;


if (isset($_SESSION['filtro_tarefas'])) {
  $filtroEntrada = $_SESSION['filtro_tarefas'];
  $idCliente = $filtroEntrada['idCliente'];
  $idAtendente = $filtroEntrada['idAtendente'];
  $idTipoOcorrencia = $filtroEntrada['idTipoOcorrencia'];
  $statusTarefa = $filtroEntrada['statusTarefa'];
  //Lucas id965 - removido variavel do filtro Periodo
  $PeriodoInicio = $filtroEntrada['PeriodoInicio'];
  $PeriodoFim = $filtroEntrada['PeriodoFim'];
   // lucas 07112023 id654 - Removido PrevistoOrderm e RealOrdem, e adicionado dataOrdem no lugar
  $dataOrdem = $filtroEntrada['dataOrdem'];

}
//Lucas id965 - removido variaveis do filtro Periodo


?>
<!doctype html>
<html lang="pt-BR">

<head>

  <?php include_once ROOT . "/vendor/head_css.php"; ?>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

</head>

<body>

  <div class="container-fluid">

    <div class="row">
      <!--<BR>  MENSAGENS/ALERTAS -->
    </div>
    <div class="row mt-3">
       <!-- <BR><BR><BR> BOTOES AUXILIARES -->
    </div>

    <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

      <div class="col-2 col-lg-1 order-lg-1">
        <button class="btn btn-outline-secondary ts-btnFiltros" type="button"><i class="bi bi-funnel"></i></button>
      </div>

      <div class="col-4 col-lg-3 order-lg-2" id="filtroh6">
        <h2 class="ts-tituloPrincipal">Tarefas</h2>
        <h6 style="font-size: 10px;font-style:italic;text-align:left;"></h6>
      </div>

      <div class="col-6 col-lg-2 order-lg-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#periodoModal"><i class="bi bi-calendar3"></i></button>
      </div>

      <div class="col-12 col-lg-6 order-lg-4">
        <div class="input-group">
          <input type="text" class="form-control ts-input" id="buscaTarefa" placeholder="Buscar por id ou titulo">
          <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
          <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
        </div>
      </div>

    </div>

    <!-- MENUFILTROS -->
    <div class="ts-menuFiltros mt-2 px-3">
      <label>Filtrar por:</label>

      <!-- Gabriel 06102023 ID 596 ajustado posiçao -->
      <div class="ls-label col-sm-12"> <!-- ABERTO/FECHADO -->
        <form class="d-flex" action="" method="post">
          <select class="form-control" name="statusTarefa" id="FiltroStatusTarefa">
            <option value="<?php echo null ?>">
              <?php echo "Todos" ?>
            </option>
            <option <?php if ($statusTarefa == "1") {
                      echo "selected";
                    } ?> value="1">Aberto</option>
            <option <?php if ($statusTarefa == "0") {
                      echo "selected";
                    } ?> value="0">Realizado</option>
          </select>
        </form>
      </div>

      <div class="col-sm text-end mt-2">
        <a onClick="limpar()" role=" button" class="btn btn-sm bg-info text-white">Limpar</a>
      </div>
    </div>

    <div class="table mt-2 ts-divTabela ts-tableFiltros">
      <table class="table table-sm table-hover" id="tblEditavel">
        <thead class="ts-headertabelafixo">
          <tr class="ts-headerTabelaLinhaCima">

            <!-- Helio 071123 - Ajuste nas TD por col -->
            <th class="col-5">Tarefa</th>
            <th class="col-1">Responsável</th>
            <th class="col-1">Cliente</th>
            <th class="col-1">Ocorrência</th>
            <th class="col-3">Datas</th>
            <th class="col-1" colspan="2"></th>
          </tr>
          <tr class="ts-headerTabelaLinhaBaixo">
            <th></th>
            <th>
              <form action="" method="post">
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idAtendente" id="FiltroUsuario">
                  <option value="<?php echo null ?>">
                    <?php echo "Todos" ?>
                  </option>
                  <?php
                  foreach ($atendentes as $atendente) {
                  ?>
                    <option <?php
                            if ($atendente['idUsuario'] == $idAtendente) {
                              echo "selected";
                            }
                            ?> value="<?php echo $atendente['idUsuario'] ?>">
                      <?php echo $atendente['nomeUsuario'] ?>
                    </option>
                  <?php } ?>
                </select>
              </form>
            </th>
            <th>
              <form action="" method="post">
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idCliente" id="FiltroClientes">
                  <option value="<?php echo null ?>">
                    <?php echo "Todos" ?> 
                  </option>
                  <?php
                  foreach ($clientes as $cliente) {
                  ?>
                    <option <?php
                            if ($cliente['idCliente'] == $idCliente) {
                              echo "selected";
                            }
                            ?> value="<?php echo $cliente['idCliente'] ?>">
                      <?php echo $cliente['nomeCliente'] ?>
                    </option>
                  <?php } ?>
                </select>
              </form>
            </th>
            <th>
              <form action="" method="post">
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idTipoOcorrencia" id="FiltroOcorrencia">
                  <option value="<?php echo null ?>">
                    <?php echo "Todos" ?>
                  </option>
                  <?php
                  foreach ($ocorrencias as $ocorrencia) {
                  ?>
                    <option <?php
                            if ($ocorrencia['idTipoOcorrencia'] == $idTipoOcorrencia) {
                              echo "selected";
                            }
                            ?> value="<?php echo $ocorrencia['idTipoOcorrencia'] ?>">
                      <?php echo $ocorrencia['nomeTipoOcorrencia'] ?>
                    </option>
                  <?php } ?>
                </select>
              </form>
            </th>
             <!-- lucas id654 - Removido filtro RealOrdem e substituido filtro PrevistoOrdem por dataOrdem -->
            <th>
              <form action="" method="post">
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="dataOrdem" id="FiltrodataOrdem">
                  <!-- Lucas 07112023 id965 - ajustado ordem do filtro -->
                  <option <?php if ($dataOrdem == "0") {
                            echo "selected";
                          } ?> value="0">ASC</option>
                  <option <?php if ($dataOrdem == "1") {
                            echo "selected";
                          } ?> value="1">DESC</option>
                  
                </select>
              </form>
            </th>
            <!-- <th></th> -->
            <th colspan="2"></th>
          </tr>
        </thead>

        <tbody id='dados' class="fonteCorpo">

        </tbody>
      </table>
    </div>

  </div>


  <!--------- FILTRO PERIODO --------->
  <?php include_once 'modalTarefa_filtroPeriodo.php' ?>

  <!--------- MODAL STOP Tab EXECUCAO --------->
  <?php include_once 'modalTarefa_stop.php' ?>

  <!--------- INSERIR/AGENDAR --------->
  <?php include_once 'modalTarefa_inserirAgendar.php' ?>

  <!--------- TAREFAS ALTERAR--------->
  <?php include 'modalTarefa_alterar.php'; ?>



  <!-- LOCAL PARA COLOCAR OS JS -->

  <?php include_once ROOT . "/vendor/footer_js.php"; ?>
  <!-- script para menu de filtros -->
  <script src="<?php echo URLROOT ?>/sistema/js/filtroTabela.js"></script>
  

  <script src="<?php echo URLROOT ?>/services/demandas/tarefas.js"></script>

  <script>
    buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val() , $("#buscaTarefa").val());

    function limpar() {
      buscar(null, null, null, null, null, null, null, null, null, function() {
        //gabriel 13102023 id 596 fix atualizar pagina correta
        //window.location.reload();
      });
    }

    function limparPeriodo() {
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), null, null, null, null, function() {
        window.location.reload();
      });
    }
    //Gabriel 06102023 ID 596 movido function do ajax para ser utilizado fora dele
    function formatDate(dateString) {
      if (dateString !== null && !isNaN(new Date(dateString))) {
        var date = new Date(dateString);
        var day = date.getUTCDate().toString().padStart(2, '0');
        var month = (date.getUTCMonth() + 1).toString().padStart(2, '0');
        var year = date.getUTCFullYear().toString().padStart(4, '0');
        return day + "/" + month + "/" + year;
      }
      return "00/00/0000";
    }

    function formatTime(timeString) {
      if (timeString !== null) {
        var timeParts = timeString.split(':');
        var hours = timeParts[0].padStart(2, '0');
        var minutes = timeParts[1].padStart(2, '0');
        return hours + ":" + minutes;
      }
      return "00:00";
    }

    var vdata = new Date();

    var day = String(vdata.getDate()).padStart(2, '0');
    var month = String(vdata.getMonth() + 1).padStart(2, '0');
    var year = vdata.getFullYear();
    var today = `${day}/${month}/${year}`;

    var hh = String(vdata.getHours()).padStart(2, '0');
    var mm = String(vdata.getMinutes()).padStart(2, '0');
    var time = hh + ':' + mm;

    //Gabriel 16102023 id596 variavel dia/hora 
    var todayTime = today + " " + time;

    function buscar(idCliente, idAtendente, tituloTarefa, idTipoOcorrencia, statusTarefa, PeriodoInicio, PeriodoFim, dataOrdem, buscaTarefa, callback) {
      //Gabriel 11102023 ID 596 utiliza valores do buscar para gravar no h6 da tabela filtros status e periodo
      var h6Element = $("#filtroh6 h6");
      var text = "";
      if (statusTarefa === "1") {
        if (text) text += ", ";
        text += "Status = Aberto";
      } else if (statusTarefa === "0") {
        if (text) text += ", ";
        text += "Status = Realizado";
      }
      /* Lucas 07112023 id965 - removido status de periodo */
      
      if (PeriodoInicio !== "") {
        if (text) text += " em ";
        text += formatDate(PeriodoInicio);
      }
      if (PeriodoFim !== "") {
        if (text) text += " até ";
        text += formatDate(PeriodoFim);
      }

      h6Element.html(text);
      $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '<?php echo URLROOT ?>/services/database/tarefas.php?operacao=filtrar',
        beforeSend: function() {
          $("#dados").html("Carregando...");
        },
        data: {
          idCliente: idCliente,
          idAtendente: idAtendente,
          tituloTarefa: tituloTarefa,
          idTipoOcorrencia: idTipoOcorrencia,
          statusTarefa: statusTarefa,
          //Lucas 07112023 id965 - removido periodo
          PeriodoInicio: PeriodoInicio,
          PeriodoFim: PeriodoFim,
          dataOrdem: dataOrdem,
          buscaTarefa: buscaTarefa
        },
        success: function(msg) {

          var json = JSON.parse(msg);
          var linha = "";
          for (var $i = 0; $i < json.length; $i++) {
            var object = json[$i];


            var vPrevisto = formatDate(object.Previsto);
            var valorhoraInicioPrevisto = formatTime(object.horaInicioPrevisto); //criado
            var vhoraFinalPrevisto = formatTime(object.horaFinalPrevisto);
            var valorhorasPrevisto = formatTime(object.horasPrevisto); //criado
            //Gabriel 16102023 id596 ajustando if
            var valorhoraInicioPrevistoTime = valorhoraInicioPrevisto.split(":");
            var valorhoraInicioPrevistoMinutes = parseInt(valorhoraInicioPrevistoTime[0]) * 60 + parseInt(valorhoraInicioPrevistoTime[1]);
            var timeTime = time.split(":");
            var timeMinutes = parseInt(timeTime[0]) * 60 + parseInt(timeTime[1]);


            var vdataReal = formatDate(object.dataReal);
            var valorhoraInicioReal = formatTime(object.horaInicioReal); //criado
            var valorhoraFinalReal = formatTime(object.horaFinalReal); //criado
            var valorhorasReal = formatTime(object.horasReal);//criado
      
            /* Helio 07112023 - Campos ficam em Branco quando Zerados */
            if(vdataReal === "00/00/0000"){
                vdataReal = '';
            }
            if(vPrevisto === "00/00/0000"){
                vPrevisto = '';
            }

           if(valorhoraInicioPrevisto === '00:00'){
              valorhoraInicioPrevisto = ''
            }

           if(vhoraFinalPrevisto === '00:00'){
              vhoraFinalPrevisto = ''
            }

            if(valorhoraInicioReal === '00:00'){
              valorhoraInicioReal = ''
            }

            if(valorhoraFinalReal === '00:00'){
              valorhoraFinalReal = ''
            }

            if(valorhorasPrevisto === '00:00'){
              valorhorasPrevisto = ''
            } else {
              valorhorasPrevisto = '(' + valorhorasPrevisto + ')';
            }

           
            if(valorhorasReal === '00:00'){
              valorhorasReal = ''
            } else {
              valorhorasReal = '(' + valorhorasReal + ')';
            }

            vnomeTipoOcorrencia = object.nomeTipoOcorrencia;
            if (vnomeTipoOcorrencia === null) {
              vnomeTipoOcorrencia = '';
            }

            // lucas 23112023 - tratamento quando cliente vir null
            if(object.nomeCliente === null){
              object.nomeCliente = '';
            }

            linha += "<tr>";

            linha += "<td class='ts-click' data-bs-toggle='modal' data-bs-target='#alterarmodal' data-idtarefa='" + object.idTarefa + "'>";
            if ((object.idDemanda !== null) && (object.idContrato !== null)) {
              linha += object.nomeContrato + " : " + " " + object.idContrato + "  " + object.tituloContrato + " / ";
              linha += object.idDemanda + "  " +  object.tituloDemanda + "<br>"; 
              linha += object.tituloTarefa;
            }

            if((object.idDemanda !== null) && (object.idContrato === null)){
              linha += object.nomeDemanda + " : " + " " + object.idDemanda + "  " +  object.tituloDemanda + "<br>";
              linha += object.tituloTarefa;
            }

            if(object.tituloDemanda === null){
              linha += object.tituloTarefa;
            }
            
            linha += "</td>";


            linha += "<td class='ts-click' data-bs-toggle='modal' data-bs-target='#alterarmodal' data-idtarefa='" + object.idTarefa + "'>" + object.nomeUsuario + "</td>";
            linha += "<td class='ts-click' data-bs-toggle='modal' data-bs-target='#alterarmodal' data-idtarefa='" + object.idTarefa + "'>" + object.nomeCliente + "</td>";
            linha += "<td class='ts-click' data-bs-toggle='modal' data-bs-target='#alterarmodal' data-idtarefa='" + object.idTarefa + "'>" + vnomeTipoOcorrencia + "</td>";
            /* Lucas 07112023 id965 - Reajustado condição para horas */
            horas = '';
            if(vdataReal !== ""){
              horas += "<td class='ts-click' data-bs-toggle='modal' data-bs-target='#alterarmodal' data-idtarefa='" + object.idTarefa + "'>";
              if(vPrevisto !== ""){
              horas += "<span class='ts-datas ts-previsto'>Prev: " + vPrevisto + "</span><span  class='ts-horas ts-previsto'>" + valorhoraInicioPrevisto + "</span><span class='ts-horas ts-previsto'>" + vhoraFinalPrevisto + "</span><span class='ts-horas ts-previsto'>" + valorhorasPrevisto + "</span>" + "<br>";
            }
              horas += "<span class='ts-datas'>Real: " + vdataReal + "</span><span class='ts-horas'>" + valorhoraInicioReal + "</span><span class='ts-horas'>" + valorhoraFinalReal + "</span><span class='ts-horas'>" + valorhorasReal + "</span>"  + "</td>";
              //alert(horas)
            }else{

              if(vPrevisto !== ""){
                horas += "<td class='ts-click' data-bs-toggle='modal' data-bs-target='#alterarmodal' data-idtarefa='" + object.idTarefa + "'";
                if(object.Atrasado == 'SIM'){
                  horas += " style='background:firebrick;color:white'";
                }
                horas += ">";
                horas += "<span class='ts-datas'>Prev: " + vPrevisto + "</span><span class='ts-horas'>" + valorhoraInicioPrevisto + "</span><span class='ts-horas'>" + vhoraFinalPrevisto + "</span><span class='ts-horas'>" + valorhorasPrevisto + "</span>" + "</td>";
                //alert(horas)
              }
          }
          if((vdataReal === "") && (vPrevisto === "")){
                horas += "<td></td> "
              }
          linha += horas;
          
          
            // lucas id654 - Removido linha de dataReal
          
            linha += "<td>" ; 

            linha += "<id='botao'>";
            
            if (valorhoraInicioReal == "") {
              linha += "<button type='button' class='startButton btn btn-success btn-sm mr-1' data-id='" + object.idTarefa + "'><i class='bi bi-play-circle'></i></button>"
            }else{
              if (valorhoraInicioReal != "" && valorhoraFinalReal == "" && vdataReal == today) {
              //lucas 25092023 ID 358 Adicionado condição para botão com demanda associada e sem demanda asssociada 
              if ((object.idDemanda == null) && (vdataReal == today)) {
                linha += "<button type='button' class='stopButton btn btn-danger btn-sm mr-1' data-id='" + object.idTarefa + "' data-demanda='" + object.idDemanda + 
                "'><i class='bi bi-stop-circle'></i></button>"
              } else {
                linha += "<button type='button' class='btn btn-danger btn-sm mr-1' data-bs-toggle='modal' data-bs-target='#stopmodal' data-id='" + object.idTarefa + 
                "' data-demanda='" + object.idDemanda + "'><i class='bi bi-stop-circle'></i></button>"
              } 
            }else {
              linha += "<button type='button' class='novoStartButton btn btn-success btn-sm mr-1' "+ 
                " data-id='" + object.idTarefa + 
                "' data-titulo='" + object.tituloTarefa +
                "' data-cliente='" + object.idCliente +
                "' data-demanda='" + object.idDemanda +
                "' data-atendente='" + object.idAtendente +
                "' data-ocorrencia='" + object.idTipoOcorrencia +
                "' data-previsto='" + object.Previsto +
                "' data-horainicioprevisto='" + object.horaInicioPrevisto +
                "' data-horafinalprevisto='" + object.horaFinalPrevisto +
                "'><i class='bi bi-play-circle'></i></button>"
            }
          }

            linha += "</td>";

            linha += "<td>"; 
            linha += "<div class='btn-group dropstart'><button type='button' class='btn' data-toggle='tooltip' data-placement='left' title='Opções' data-bs-toggle='dropdown' " +
            " aria-expanded='false' style='box-shadow:none'><i class='bi bi-three-dots-vertical'></i></button><ul class='dropdown-menu'>"

            linha += "<id='botao'>";
            
            if (valorhoraInicioReal == "") {
              linha += "<li class='ms-1 me-1 mt-1'><button type='button' id='realizadoButton' class='btn btn-info btn-sm w-100 text-start' data-id='" + object.idTarefa + 
              "' data-demanda='" + object.idDemanda + "'><i class='bi bi-check-circle'></i> <span class='ts-btnAcoes' " + ">Realizado</span></button></li>"
            }
            linha += "<li class='ms-1 me-1 mt-1'><button type='button' class='clonarButton btn btn-success btn-sm w-100 text-start'  data-idtarefa='" + object.idTarefa + 
            "' data-status='" + object.idTipoStatus + "' data-demanda='" + object.idDemanda + "'><i class='bi bi-back'></i> <span class='ts-btnAcoes' " +
            ">Clonar</span></button></li>";
            linha += "<li class='ms-1 me-1 mt-1'><button type='button' class='btn btn-warning btn-sm w-100 text-start' data-bs-toggle='modal' data-bs-target='#alterarmodal' data-idtarefa='" + 
            object.idTarefa + "'><i class='bi bi-pencil-square'></i> <span class='ts-btnAcoes'>Alterar</span></button></li>"
            

            linha +="</ul></div>"
            linha += "</td>";


            linha += "</tr>";
          }

          $("#dados").html(linha);

          if (typeof callback === 'function') {
            callback();
          }
        }
      });
    }

    $("#FiltroClientes").change(function() {
      //Gabriel 06102023 ID 596 ajustado #buscaTarefa
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
    });

    $("#FiltroUsuario").change(function() {
      //Gabriel 06102023 ID 596 ajustado #buscaTarefa
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
    });

    $("#buscar").click(function() {
      //Gabriel 06102023 ID 596 ajustado #buscaTarefa
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
    });

    $("#FiltroOcorrencia").change(function() {
      //Gabriel 06102023 ID 596 ajustado #buscaTarefa
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
    });

    $("#FiltroDemanda").click(function() {
      //Gabriel 06102023 ID 596 ajustado #buscaTarefa
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
    });

    $("#FiltroStatusTarefa").change(function() {
      //Gabriel 06102023 ID 596 ajustado #buscaTarefa
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
    });

    $("#FiltrodataOrdem").change(function() {
      //Gabriel 06102023 ID 596 ajustado #buscaTarefa
      buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
    });


    //Gabriel 11102023 ID 596 adicionado document ready pois o modal está em indextarefa.php
    $(document).ready(function() {
      $("#filtrarButton").click(function() {
       
          //Gabriel 06102023 ID 596 ajustado #buscaTarefa
          buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
          $('#periodoModal').modal('hide');
        
      });
    });

    document.addEventListener("keypress", function(e) {
      if (e.key === "Enter") {
        //Gabriel 06102023 ID 596 ajustado #buscaTarefa
        buscar($("#FiltroClientes").val(), $("#FiltroUsuario").val(), $("#buscaTarefa").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusTarefa").val(), $("#FiltroPeriodoInicio").val(), $("#FiltroPeriodoFim").val(), $("#FiltrodataOrdem").val(), $("#buscaTarefa").val());
      }
    });

  

    //lucas 17112023 ID 965 Removido script do botao stop, está no arquivo tarefas.js  


    var inserirModal = document.getElementById("inserirModal");

    var inserirBtn = document.querySelector("button[data-bs-target='#inserirModal']");

    inserirBtn.onclick = function() {
      inserirModal.style.display = "block";
    };

    window.onclick = function(event) {
      if (event.target == inserirModal) {
        inserirModal.style.display = "none";
      }
    };

   
    //lucas 17112023 ID 965 Removido script do botao clonarButton, está no arquivo tarefas.js 


    // Lucas 131123 ID 965 Adicionado script para botão de novoStart
    $(document).on('click', '.novoStartButton', function() {
      var idTarefa = $(this).data('id');
      var tituloTarefa = $(this).data('titulo');
      var idCliente = $(this).data('cliente');
      var idDemanda = $(this).data('demanda');
      var idAtendente = $(this).data('atendente');
      var idTipoOcorrencia = $(this).data('ocorrencia');
      var previsto = $(this).data('previsto');
      var horaInicioPrevisto = $(this).data('horainicioprevisto');
      var horaFinalPrevisto = $(this).data('horafinalprevisto');    

        $.ajax({
            url: "../database/tarefas.php?operacao=inserir&acao=start",
            method: "POST",
            dataType: "json",
            data: {
              tituloTarefa: tituloTarefa,
              idCliente: idCliente,
              idDemanda: idDemanda,
              idAtendente: idAtendente,
              idTipoOcorrencia: idTipoOcorrencia,
              Previsto: previsto,
              horaInicioPrevisto: horaInicioPrevisto,
              horaFinalPrevisto: horaFinalPrevisto,

              },
              success: function(msg) {
          //alert(JSON.stringify(msg));
          if (msg.retorno == "ok") {
            window.location.reload();
          }
        },
        error: function(msg) {
          alert(JSON.stringify(msg));
        }
        });
    
    });
    

    //Lucas 17112023 ID 965 - removido variaveis
    $(document).on('click', '.stopButton', function() {
      
      var idTarefa = $(this).data('id');
      var idDemanda = $(this).data('demanda');
      $.ajax({
        //lucas 25092023 ID 358 Modificado operação de tarefas
        url: "../database/tarefas.php?operacao=realizado&acao=stop",
        method: "POST",
        dataType: "json",
        data: {
          idTarefa: idTarefa,
          idDemanda: idDemanda,
          comentario: null
        },
        success: function(msg) {
          if (msg.retorno == "ok") {
            window.location.reload();
          }
        },
        error: function(msg) {
          alert(JSON.stringify(msg));
        }
      });
    });

    //Lucas 17112023 ID 965 - removido variaveis
    $(document).on('click', '.startButton', function() {
      var idTarefa = $(this).data('id');
      $.ajax({
        url: "../database/tarefas.php?operacao=realizado&acao=start",
        method: "POST",
        dataType: "json",
        data: {
          idTarefa: idTarefa
        },
        success: function(msg) {
          //alert(JSON.stringify(msg));
          if (msg.retorno == "ok") {
            window.location.reload();
          }
        },
        error: function(msg) {
          alert(JSON.stringify(msg));
        }
      });
    });

    //Lucas 17112023 ID 965 - substituido class por id (realizadoButton)
    $(document).on('click', '#realizadoButton', function() {
      var idTarefa = $(this).data('id');
      var idDemanda = $(this).data('demanda');

      $.ajax({
        url: "../database/tarefas.php?operacao=realizado",
        method: "POST",
        dataType: "json",
        data: {
          idTarefa: idTarefa,
          idDemanda: idDemanda
        },
        success: function(msg) {
          if (msg.retorno == "ok") {
            window.location.reload();
          }
        },
        error: function(msg) {
          alert(JSON.stringify(msg));
        }
      });
    });

    $(document).ready(function() {
      $("#inserirForm").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var vurl;
        if ($("#inserirStartBtn").prop("clicked")) {
          // Lucas 141123 ID 965 - Alterado url
          vurl = "../database/tarefas.php?operacao=inserir&acao=start";
        } else {
          vurl = "../database/tarefas.php?operacao=inserir";
        }
        $.ajax({
          url: vurl,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          //gabriel 13102023 id 596 fix atualizar pagina correta
          success: refreshPage
        });
      });

      $("#inserirStartBtn").click(function() {
        $("#inserirBtn").prop("clicked", false);
        $(this).prop("clicked", true);
      });

      $("#inserirBtn").click(function() {
        $("#inserirStartBtn").prop("clicked", false);
        $(this).prop("clicked", true);
      });

      $("#alterarForm").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        for (var pair of formData.entries()) {
          console.log(pair[0] + ', ' + pair[1]);
        } 
        var vurl;
        if ($("#stopButtonModal").is(":focus")) {
          vurl = "../database/tarefas.php?operacao=stop";
        }
        if ($("#startButtonModal").is(":focus")) {
          vurl = "../database/tarefas.php?operacao=start";
        }
        if ($("#realizadoButtonModal").is(":focus")) {
          vurl = "../database/tarefas.php?operacao=realizado";
        }
        if ($("#atualizarButtonModal").is(":focus")) {
          vurl = "../database/tarefas.php?operacao=alterar";
        }
        $.ajax({
          url: vurl,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: refreshPage

        });
      });

      function refreshPage() {
        window.location.reload();
      }

      //gabriel 13102023 id 596 submit stopForm para evitar redirecionamento para demanda
      $("#stopForm").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        for (var pair of formData.entries()) {
          console.log(pair[0] + ', ' + pair[1]);
        }
        var vurl;
        if ($("#realizadoFormbutton").is(":focus")) {
          vurl = "../database/tarefas.php?operacao=realizado&acao=entregue";
        }
        if ($("#stopFormbutton").is(":focus")) {
          vurl = "../database/tarefas.php?operacao=realizado&acao=stop";
          
        }
        $.ajax({
          url: vurl,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: refreshPage
        });
      });
    });


    //Gabriel 22092023 id544 trocado setcookie por httpRequest enviado para gravar origem em session//ajax
    $("#visualizarDemandaButton").click(function() {
      var currentPath = window.location.pathname;
      $.ajax({
        type: 'POST',
        url: '../database/demanda.php?operacao=origem',
        data: {
          origem: currentPath
        },
        success: function(response) {
          console.log('Session variable set successfully.');
        },
        error: function(xhr, status, error) {
          console.error('An error occurred:', error);
        }
      });
    });

  //Lucas 10112023 ID 965 Removido script do editor de stop
  </script>


  <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>