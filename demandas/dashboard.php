<?php
// lucas 07062024 id 742 visao do mes atual
// helio 01022023 alterado para include_once
// gabriel 03022023 alterado visualizar

include_once '../head.php';
include_once '../database/tarefas.php';
include_once '../database/demanda.php';

include_once '../database/contratotipos.php';
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');


$contratotipos = buscaContratoTipos();
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);
// Helio 29/07/2024 - quando está gravanbco novo login, não está gravando o usuario no mysql
// paliativo
if (isset($usuario)) {
  if ($usuario["idUsuario"] == null) {
      echo "Usuario não encontrado!" ."<HR>";
      return;
  } 
} else {
  return;
}

if ($usuario["idCliente"] == null) {
  $clientes = buscaClientes($usuario["idCliente"]);
} else {
  $clientes = array(buscaClientes($usuario["idCliente"]));
}

?>

<!doctype html>
<html lang="pt-BR">

<head>
  <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

  <div class="container-fluid">

    <div class="row d-flex pt-1 pb-2 mb-1 border-bottom">


      <div class="col-1 d-none">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#periodoModal"><i class="bi bi-calendar3"></i></button>
      </div>

      <div class="col-2">
        <select class="form-select ts-input mt-1 pt-1 <?php if ($usuario["idCliente"] != null) {
                                                        echo "ts-displayDisable";
                                                      } ?>" name="idCliente" id="FiltroCliente">
          <option value="<?php echo null ?>">
            <?php echo "Todos Clientes" ?>
          </option>
          <?php
          foreach ($clientes as $cliente) {
          ?>
            <option <?php
                    if ($cliente['idCliente'] == $usuario["idCliente"]) {
                      echo "selected";
                    }
                    ?> value="<?php echo $cliente['idCliente'] ?>">
              <?php echo $cliente['nomeCliente'] ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <div class="col-2">
        <select class="form-select ts-input mt-1 pt-1" name="idContratoTipo" id="FiltroContratoTipo">
          <option value="<?php echo null ?>">
            <?php echo "Todos Tipos" ?>
          </option>
          <?php
          foreach ($contratotipos as $contratotipo) {
          ?>
            <option <?php
                    ?> value="<?php echo $contratotipo['idContratoTipo'] ?>">
              <?php echo $contratotipo['nomeContrato'] ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <div class="col-8 d-flex gap-2 align-items-end justify-content-end">
        <div class="col-1 p-0">
          <input type="text" class="form-control ts-input" name="anoImposto" id="FiltroDataAno" placeholder="Ano" autocomplete="off" required>
        </div>

        <div class="col-2">
          <select class="form-select ts-input" name="mesImposto" id="FiltroDataMes">
            <option value="01">Janeiro</option>
            <option value="02">Fevereiro</option>
            <option value="03">MarÃ§o</option>
            <option value="04">Abril</option>
            <option value="05">Maio</option>
            <option value="06">Junho</option>
            <option value="07">Julho</option>
            <option value="08">Agosto</option>
            <option value="09">Setembro</option>
            <option value="10">Outubro</option>
            <option value="11">Novembro</option>
            <option value="12">Dezembro</option>
          </select>
        </div>
        <div class="col-1">
          <button type="submit" class="btn btn-primary btn-sm" id="filtrardata">Filtrar </button>
        </div>
      </div>

    </div>

    <!-- CARDS -->
    <div class="row row-cols-1 row-cols-md-6 pt-2">

      <div class="col">
        <div class="card border-left-success ts-cardColor-active ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-success text-center">BACKLOG</div>
          <h5 class="pt-2 text-center" id="card_backlog"></h5>
          <button class="ts-cardLink" data-card="backlog" data-nomeCard="Backlog"></button>
        </div>
      </div>

      <div class="col">
        <div class="card border-left-success ts-cardColor-active ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-success text-center">ABERTAS NO MES</div>
          <h5 class="pt-2 text-center" id="card_abertasnomes"></h5>
          <button class="ts-cardLink" data-card="abertasnomes" data-nomeCard="Abertas no mes"></button>
        </div>
      </div>

      <div class="col">
        <div class="card border-left-success ts-cardColor-active ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-success text-center">FECHADAS DO MES</div>
          <h5 class="pt-2 text-center" id="card_fechadasdomes"></h5>
          <button class="ts-cardLink" data-card="fechadasdomes" data-nomeCard="Fechadas do mes"></button>
        </div>
      </div>

      <div class="col">
        <div class="card border-left-success ts-cardColor-active ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-success text-center">SALDO NO MES</div>
          <h5 class="pt-2 text-center" id="card_saldodomes"></h5>
          <button class="ts-cardLink" data-card="saldodomes" data-nomeCard="Saldo do mes"></button>
        </div>
      </div>

      <div class="col">
        <div class="card border-left-success ts-cardColor-active ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-success text-center">FECHADAS NO MES</div>
          <h5 class="pt-2 text-center" id="card_fechadasnomes"></h5>
          <button class="ts-cardLink" data-card="fechadasnomes" data-nomeCard="Fechadas no mes"></button>
        </div>
      </div>

      <div class="col">
        <div class="card border-left-success ts-cardColor-active ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-success text-center">SALDO</div>
          <h5 class="pt-2 text-center" id="card_saldo"></h5>
          <button class="ts-cardLink" data-card="saldo" data-nomeCard="Saldo"></button>
        </div>
      </div>

    </div> <!-- fim- cards -->

    <div class="container mt-3">
      <div class="row align-items-center justify-content-center">
        <div class="col">
          <table class="table border">
            <tbody id='dados_totais' class="fonteCorpo">
          </table>

        </div>
        <div class="col mt-0 pt-0" id="piechart">
        </div>
      </div>

      <!-- Modal Tabela de Demandas-->
      <div class="modal fade" id="cardDemandaModal" tabindex="-1" aria-labelledby="cardDemandaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-xxl-down">
          <div class="modal-content">
            <div class="modal-header mb-0 pb-0 d-flex">

              <div class="col-6 col-sm-3">
                <h5 class="modal-title" id="titulomodal"></h5>
                <h5 id="tabelaTotalDemandas"></h5>
              </div>

              <div class="col-6 col-sm-3 p-0 d-flex">
                <label class="pb-0">Clientes:</label>
                <select class="form-select ts-input ts-inputComFormatoTexto border-bottom-0 background: #eee;" id="tabelaCliente">
                  <option value="<?php echo null ?>">
                    <?php echo "Todos" ?>
                  </option>
                  <?php
                  foreach ($clientes as $cliente) {
                  ?>
                    <option <?php
                            ?> value="<?php echo $cliente['idCliente'] ?>">
                      <?php echo $cliente['nomeCliente'] ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-6 col-sm-3 p-0 d-flex">
                <label class="pb-0">Tipo:</label>
                <select class="form-select ts-input ts-inputComFormatoTexto border-bottom-0" id="tabelaTipoContrato">
                  <option value="<?php echo null ?>">
                    <?php echo "Todos" ?>
                  </option>
                  <?php
                  foreach ($contratotipos as $contratotipo) {
                  ?>
                    <option <?php
                            ?> value="<?php echo $contratotipo['idContratoTipo'] ?>">
                      <?php echo $contratotipo['nomeContrato'] ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="col col-sm-3 d-flex">
                <label class="pb-0">Data:</label>
                <input type="text" class="form-control ts-input border-bottom-0 text-center ts-inputComFormatoTexto" id="tabelaAno" style="width: 60px;">
                de
                <select class="form-select ts-input border-bottom-0 ts-inputComFormatoTexto" id="tabelaMes">
                  <option value="01">Janeiro</option>
                  <option value="02">Fevereiro</option>
                  <option value="03">MarÃ§o</option>
                  <option value="04">Abril</option>
                  <option value="05">Maio</option>
                  <option value="06">Junho</option>
                  <option value="07">Julho</option>
                  <option value="08">Agosto</option>
                  <option value="09">Setembro</option>
                  <option value="10">Outubro</option>
                  <option value="11">Novembro</option>
                  <option value="12">Dezembro</option>
                </select>
              </div>

              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-0 p-0">

              <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
                <table class="table table-sm table-hover">
                  <thead class="ts-headertabelafixo">
                    <tr>
                      <th style="width: 2px;"></th>
                      <th class="col-3">Titulo</th>
                      <th>Responsavel</th>
                      <th>Cliente</th>
                      <th>Solicitante</th>
                      <th>ServiÃÂ§o</th>
                      <th class="col-2">Datas</th>
                      <th>Status</th>
                    </tr>

                  </thead>

                  <tbody id='dados' class="fonteCorpo">

                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div><!-- container-fluid -->


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- Google charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- script para menu de filtros -->
    <script src="<?php echo URLROOT ?>/sistema/js/filtroTabela.js"></script>

    <script>
      const date = new Date();
      const year = date.getFullYear();
      const currentMonth = date.getMonth() + 1;

      const FiltroDataAno = document.getElementById("FiltroDataAno");
      FiltroDataAno.value = year;

      const FiltroDataMes = document.getElementById("FiltroDataMes");
      FiltroDataMes.value = (currentMonth <= 9 ? "0" + currentMonth : currentMonth);

      buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());

      function buscar(FiltroContratoTipo, FiltroCliente, FiltroDataAno, FiltroDataMes) {

        $('#tabelaAno').val($("#FiltroDataAno").val());
        $('#tabelaMes').val($("#FiltroDataMes").val());
        $('#tabelaTipoContrato').val($("#FiltroContratoTipo").val());
        $('#tabelaCliente').val($("#FiltroCliente").val());

        $.ajax({
          type: 'POST',
          dataType: 'html',
          url: '<?php echo URLROOT ?>/servicos/database/demanda.php?operacao=dashboard',
          beforeSend: function() {
            $("#dados").html("Carregando...");
          },
          data: {
            idContratoTipo: FiltroContratoTipo,
            idCliente: FiltroCliente,
            ano: FiltroDataAno,
            mes: FiltroDataMes
          },
          success: function(msg) {
            //console.log(JSON.stringify(msg, null, 2));
            var json = JSON.parse(msg);

            var linha = "";
            for (var $i = 0; $i < json['totaisTabela'].length; $i++) {
              var object = json['totaisTabela'][$i];

              linha += "<tr>";
              linha += "<td>" + object.nomeTipoStatus + "</td>";
              linha += "<td>" + object.Total + "</td>";
              linha += "</tr>";
            };
            $("#dados_totais").html(linha);

            $("#card_backlog").html(json.backlog);
            $("#card_abertasnomes").html(json.abertasnomes);
            $("#card_fechadasdomes").html(json.fechadasdomes);
            $("#card_saldodomes").html(json.saldodomes);
            $("#card_fechadasnomes").html(json.fechadasnomes);
            $("#card_saldo").html(json.saldo);

          }
        });

        //AJAX GRAFICO
        $.ajax({
          type: 'POST',
          dataType: 'html',
          url: '<?php echo URLROOT ?>/servicos/database/demanda.php?operacao=dashboard',
          beforeSend: function() {
            $("#dados").html("Carregando...");
          },
          data: {
            idContratoTipo: FiltroContratoTipo,
            idCliente: FiltroCliente,
            ano: FiltroDataAno,
            mes: FiltroDataMes
          },
          success: function(msg) {
            $('#piechart').hide();
            //console.log(JSON.stringify(msg, null, 2));
            var json = JSON.parse(msg);

            for (var $i = 0; $i < json['totaisTabela'].length; $i++) {
              var object = json['totaisTabela'];

              console.log(JSON.stringify(object, null, 2));
              if (object.length > 0) {

                google.charts.load('current', {
                  'packages': ['corechart']
                });
                google.charts.setOnLoadCallback(drawPieChart);

                function drawPieChart() {
                  var jsonDataObj = object;
                  var data = new google.visualization.DataTable();

                  data.addColumn('string', 'Nome');
                  data.addColumn('number', 'Total');

                  for (var i = 0; i < jsonDataObj.length; i++) {
                    var row = jsonDataObj[i];
                    data.addRow([row.nomeTipoStatus, parseInt(row.Total)]);
                  }

                  var options = {
                    width: 500,
                    height: 400,
                  };

                  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                  chart.draw(data, options);
                }
                $('#piechart').show();
              }

            };

          }
        });
      }

      $("#FiltroContratoTipo").change(function() {
        buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
      });

      $("#FiltroCliente").change(function() {
        buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
      });

      $("#filtrardata").click(function() {
        buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
      });

      document.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
          buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
        }
      });

      //MODAL 
      $(document).on('click', '.ts-cardLink', function() {
        var card = $(this).attr("data-card");
        var nomeCard = $(this).attr("data-nomeCard");

        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '<?php echo URLROOT ?>/servicos/database/demanda.php?operacao=dashboardtabela',
          data: {
            card: card,
            idCliente: $("#FiltroCliente").val(),
            idSolicitante: null,
            idAtendente: null,
            idTipoStatus: null,
            idServico: null,
            statusDemanda: null,
            buscaDemanda: null,
            urlContratoTipo: $("#FiltroContratoTipo").val(),
            mes: $("#FiltroDataMes").val(),
            ano: $("#FiltroDataAno").val()
          },
          success: function(msg) {
            console.log(JSON.stringify(msg, null, 2));
            var json = msg;
            var linha = "";
            var contador = 0;
            for (var $i = 0; $i < json.length; $i++) {
              var object = json[$i];
              contador += 1;
              linha += "<tr>";

              linha += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "' id='visualizar'>" + object.prioridade + "</td>";
              linha += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "' id='visualizar'>";
              if ((object.idDemanda !== null) && (object.idContrato !== null)) {
                linha += object.nomeContrato + " : " + " " + object.idContrato + "  " + object.tituloContrato + "<br>";
                linha += object.idDemanda + "  " + object.tituloDemanda;
              }
              if ((object.idDemanda !== null) && (object.idContrato === null)) {
                linha += object.nomeDemanda + " : " + " " + object.idDemanda + "  " + object.tituloDemanda;
              }
              datas = '';
              datas += "</td>";
              datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "' id='visualizar'>" + object.nomeAtendente + "</td>";
              datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "' id='visualizar'>" + object.nomeCliente + "</td>";
              datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "' id='visualizar'>" + object.nomeSolicitante + "</td>";
              datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "' id='visualizar'>" + object.nomeServico + "</td>";
              datas += "<td id='visualizar' class='ts-click' data-idDemanda='" + object.idDemanda + "'"

              if ((object.atrasada == true) && (object.dataPrevisaoEntregaFormatada != null)) {
                datas += " style='background:firebrick;color:white'";
              }

              datas += ">" + 'Abertura: ' + object.dataAberturaFormatada + '<br>'
              if (object.dataPrevisaoEntrega == null) {} else {
                datas += 'Previsao : ' + ' ' + object.dataPrevisaoEntregaFormatada + '<br>'
              }

              if (object.dataFechamento == null) {} else {
                datas += 'Entrega : ' + ' ' + object.dataFechamentoFormatada
              }

              linha += datas;
              linha += "</td>";

              linha += "<td  data-idDemanda='" + object.idDemanda + "' class='" + object.idTipoStatus + "' id='visualizar'>" + object.nomeTipoStatus + "</td>";
            }

            $("#tabelaTotalDemandas").html(nomeCard + ": " + contador);
            $("#dados").html(linha);
            $('#cardDemandaModal').modal('show');

          }

        });
      });

      //lucas 10062024 - abre o visualizar de visaocli/visualizar.php
      $(document).on('click', '#visualizar', function() {
        window.location.href = '../visaocli/visualizar.php?idDemanda=' + $(this).attr('data-idDemanda') + '&&origem=dashboard';
      });
    </script>


    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>