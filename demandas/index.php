<?php
//lucas 28112023 id706 - Melhorias Demandas 2
// lucas 31102023 id650/erros
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/demanda.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(__DIR__ . '/../database/tipostatus.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');
include_once '../database/contratotipos.php';
include_once '../database/contratos.php';
include_once(ROOT . '/cadastros/database/servicos.php');

$urlContratoTipo = null;
if (isset($_GET["tipo"])) {
  $urlContratoTipo = $_GET["tipo"];
  $contratoTipo = buscaContratoTipos($urlContratoTipo);
} else {
  $contratoTipo = buscaContratoTipos('contratos');
}

//Lucas 22112023 id 688 - Removido visão do cliente ($ClienteSession)

$usuario = buscaUsuarios(null, $_SESSION['idLogin']);

if ($usuario["idCliente"] == null) {
  $clientes = buscaClientes($usuario["idCliente"]);
} else {
  $clientes = array(buscaClientes($usuario["idCliente"]));
}
$atendentes = buscaAtendente();
$usuarios = buscaUsuarios();
$tiposstatus = buscaTipoStatus();
$tipoocorrencias = buscaTipoOcorrencia();
$cards = buscaCardsDemanda();
$contratos = buscaContratosAbertos();
$servicos = buscaServicos();

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
$statusDemanda = "1"; //ABERTO

$filtroEntrada = null;
$idTipoStatus = null;
$idServico = null;
$idSolicitante = null;


if (isset($_SESSION['filtro_demanda'])) {
  $filtroEntrada = $_SESSION['filtro_demanda'];
  $idCliente = $filtroEntrada['idCliente'];
  $idSolicitante = $filtroEntrada['idSolicitante'];
  $idAtendente = $filtroEntrada['idAtendente'];
  $idTipoStatus = $filtroEntrada['idTipoStatus'];
  $idServico = $filtroEntrada['idServico'];
  $statusDemanda = $filtroEntrada['statusDemanda'];
}
?>

<!doctype html>
<html lang="pt-BR">

<head>

  <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
  <div class="container-fluid">

    <div class="row">
      <!-- <BR> MENSAGENS/ALERTAS -->
    </div>

    <div class="row row-cols-1 row-cols-md-5 pt-2">
      <!-- BOTOES AUXILIARES -->
      <div class="col">
        <div class="ts-cardColor card border-left-success ts-shadowOff ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-success">TODOS</div>
          <div class="h5 mb-0  text-gray-800 ml-1">
            <?php echo $cards['totalDemandas'] ?>
          </div>
          <button class="ts-cardLink" onClick="clickCard(this.value)" value="" id=""></button>
        </div>
      </div>

      <div class="col">
        <div class="ts-cardColor1 ts-cardColor-active card border-left-success  ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-primary">ABERTO</div>
          <div class="h5 mb-0  text-gray-800 ml-1">
            <?php echo $cards['totalAbertas'] ?>
          </div>
          <button class="ts-cardLink" onClick="clickCard(this.value)" value="1" id="1"></button>
        </div>
      </div>

      <div class="col">
        <div class="ts-cardColor2 card border-left-success ts-shadowOff ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-info">EXECUÇÃO</div>
          <div class="h5 mb-0  text-gray-800 ml-1">
            <?php echo $cards['totalExecucao'] ?>
          </div>
          <button class="ts-cardLink" onClick="clickCard(this.value)" value="2" id="2"></button>
        </div>
      </div>

      <div class="col">
        <div class="ts-cardColor3 card border-left-success ts-shadowOff ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-warning">ENTREGUE</div>
          <div class="h5 mb-0  text-gray-800 ml-1">
            <?php echo $cards['totalEntregue'] ?>
          </div>
          <button class="ts-cardLink" onClick="clickCard(this.value)" value="3" id="3"></button>
        </div>
      </div>

      <div class="col">
        <div class="ts-cardColor0 card border-left-success ts-shadowOff ts-cardsTotais p-1">
          <div class="text-xs fw-bold text-danger pl-4">FECHADO</div>
          <div class="h5 mb-0  text-gray-800 ml-1">
            <?php echo $cards['totalFechado'] ?>
          </div>
          <button class="ts-cardLink" onClick="clickCard(this.value)" value="0" id="0"></button>
        </div>
      </div>

    </div> <!-- fim- BOTOES AUXILIARES -->

    <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

      <div class="col-2 col-lg-1 order-lg-1">
        <button class="btn btn-outline-secondary ts-btnFiltros" type="button"><i class="bi bi-funnel"></i></button>
      </div>

      <div class="col-4 col-lg-3 order-lg-2">
        <h2 class="ts-tituloPrincipal"><?php echo $contratoTipo['nomeDemanda'] ?></h2>
        <span>Filtro Aplicado</span>
      </div>

      <div class="col-6 col-lg-2 order-lg-3">
        <form class="text-end" action="" method="post">
          <div class="input-group">
            <select class="form-select ts-input" name="exportoptions" id="exportoptions">
              <option value="excel">Excel</option>
              <option value="pdf">PDF</option>
              <option value="csv">csv</option>
            </select>
            <button class="btn btn-warning" id="export" name="export" type="submit">Gerar</button>
          </div>
        </form>
      </div>

      <div class="col-12 col-lg-6 order-lg-4">
        <div class="input-group">
          <input type="text" class="form-control ts-input" id="buscaDemanda" placeholder="Buscar por id ou titulo">
          <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
          <button type="button" class="ms-4 btn btn-success ml-4" data-bs-toggle="modal" data-bs-target="#novoinserirDemandaModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
        </div>
      </div>

    </div>

    <!-- MENUFILTROS -->
    <div class="ts-menuFiltros mt-2 px-3">
      <label>Filtrar por:</label>

      <div class="ls-label col-sm-12"> <!-- ABERTO/FECHADO -->
        <form class="d-flex" action="" method="post">

          <select class="form-control" name="statusDemanda" id="FiltroStatusDemanda" onchange="mudarSelect(this.value)">
            <option value="<?php echo null ?>">
              <?php echo "Todos" ?>
            </option>
            <option <?php if ($statusDemanda == "1") {
                      echo "selected";
                    } ?> value="1">Aberto</option>
            <option <?php if ($statusDemanda == "2") {
                      echo "selected";
                    } ?> value="2">Execução</option>
            <option <?php if ($statusDemanda == "3") {
                      echo "selected";
                    } ?> value="3">Entregue</option>
            <option <?php if ($statusDemanda == "0") {
                      echo "selected";
                    } ?> value="0">Fechado</option>
          </select>

        </form>
      </div>

      <div class="col-sm text-end mt-2">
        <a onClick="limparTrade()" role=" button" class="btn btn-sm bg-info text-white">Limpar</a>
      </div>
    </div>

    <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
      <table class="table table-sm table-hover">
        <thead class="ts-headertabelafixo">
          <tr class="ts-headerTabelaLinhaCima">
            <th></th>
            <th class="col-3">Titulo</th>
            <th>Responsavel</th>
            <th>Cliente</th>
            <th>Solicitante</th>
            <th>Serviço</th>
            <th class="col-2">Datas</th>
            <th>Status</th>
            <th colspan="2"></th>
          </tr>
          <tr class="ts-headerTabelaLinhaBaixo">
            <th></th>
            <th></th>
            <th>
              <form action="" method="post">
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idAtendente" id="FiltroUsuario">
                  <option value="<?php echo null ?>">
                    <?php echo "Selecione" ?>
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
                    <?php echo "Selecione" ?>
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
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idSolicitante" id="FiltroSolicitante">
                  <option value="<?php echo null ?>">
                    <?php echo "Selecione" ?>
                  </option>
                  <?php
                  foreach ($usuarios as $usuariofiltro) {
                  ?>
                    <option <?php
                            if ($usuariofiltro['idUsuario'] == $idSolicitante) {
                              echo "selected";
                            }
                            ?> value="<?php echo $usuariofiltro['idUsuario'] ?>">
                      <?php echo $usuariofiltro['nomeUsuario'] ?>
                    </option>
                  <?php } ?>
                </select>
              </form>
            </th>
            <th>
              <form action="" method="post">
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idServico" id="FiltroServico">
                  <option value="<?php echo null ?>">
                    <?php echo "Selecione" ?>
                  </option>
                  <?php
                  foreach ($servicos as $servico) {
                  ?>
                    <option <?php
                            if ($servico['idServico'] == $idServico) {
                              echo "selected";
                            }
                            ?> value="<?php echo $servico['idServico'] ?>">
                      <?php echo $servico['nomeServico'] ?>
                    </option>
                  <?php } ?>
                </select>
              </form>
            </th>
            <th></th>
            <th>
              <form action="" method="post">
                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idTipoStatus" id="FiltroTipoStatus" autocomplete="off">
                  <option value="<?php echo null ?>">
                    <?php echo "Selecione" ?>
                  </option>
                  <?php foreach ($tiposstatus as $tipostatus) { ?>
                    <option <?php
                            if ($tipostatus['idTipoStatus'] == $idTipoStatus) {
                              echo "selected";
                            }
                            ?> value="<?php echo $tipostatus['idTipoStatus'] ?>">
                      <?php echo $tipostatus['nomeTipoStatus'] ?>
                    </option>
                  <?php } ?>
                </select>
              </form>
            </th>
            <th></th>
            <th></th>
          </tr>
        </thead>

        <tbody id='dados' class="fonteCorpo">

        </tbody>
      </table>
    </div>

    <?php include_once 'modalDemanda_inserir.php' ?>



  </div><!--container-fluid-->

  <!-- LOCAL PARA COLOCAR OS JS -->

  <?php include_once ROOT . "/vendor/footer_js.php"; ?>
  <!-- script para menu de filtros -->
  <script src="<?php echo URLROOT ?>/sistema/js/filtroTabela.js"></script>
 
  <!-- Cards funcionado como botões -->
  <script src="../js/demanda_cards.js"></script>

  <script>
    var urlContratoTipo = '<?php echo $urlContratoTipo ?>';

    buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());

    function limparTrade() {
      buscar(null, null, null, null, null, null, null, function() {
        window.location.reload();
      });
    }

    function clickCard(statusDemanda) {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(),
        statusDemanda, $("#buscaDemanda").val())
    }

    function buscar(idCliente, idSolicitante, idAtendente, idTipoStatus, idServico, statusDemanda, buscaDemanda, callback) {
      //alert(posicao)
      $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '<?php echo URLROOT ?>/services/database/demanda.php?operacao=filtrar',
        beforeSend: function() {
          $("#dados").html("Carregando...");
        },
        data: {
          idCliente: idCliente,
          idSolicitante: idSolicitante,
          idAtendente: idAtendente,
          idTipoStatus: idTipoStatus,
          idServico: idServico,
          statusDemanda: statusDemanda,
          buscaDemanda: buscaDemanda,
          urlContratoTipo: urlContratoTipo,
        },
        success: function(msg) {
          var json = JSON.parse(msg);
          var linha = "";
          for (var $i = 0; $i < json.length; $i++) {
            var object = json[$i];

            linha += "<tr>";  
            /* helio 09112023 - classe ts-click para quando clicar,
               data-idDemanda para guardar o id da demanda */
            linha += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "'>" + object.prioridade + "</td>";
            linha += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "'>";
            if ((object.idDemanda !== null) && (object.idContrato !== null)) {
              linha += object.nomeContrato + " : " + " " + object.idContrato + "  " + object.tituloContrato + "<br>";
              linha += object.idDemanda + "  " +  object.tituloDemanda; 
            }
            if((object.idDemanda !== null) && (object.idContrato === null)){
              linha += object.nomeDemanda + " : " + " " + object.idDemanda + "  " +  object.tituloDemanda;
            }
            datas = '';
            datas += "</td>";
            datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "'>" + object.nomeAtendente + "</td>";
            datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "'>" + object.nomeCliente + "</td>";
            datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "'>" + object.nomeSolicitante + "</td>";
            datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "'>" + object.nomeServico + "</td>";
            datas += "<td class='ts-click' data-idDemanda='" + object.idDemanda + "'" 

            if((object.atrasada == true) && (object.dataPrevisaoEntregaFormatada != null)){
              datas += " style='background:firebrick;color:white'";
            }
            
            datas += ">" + 'Abertura: ' + object.dataAberturaFormatada + '<br>' 
            if (object.dataPrevisaoEntrega == null) {
            }else{
              datas += 'Previsao : ' + ' ' + object.dataPrevisaoEntregaFormatada + '<br>' 
            }

            if (object.dataFechamento == null) {
            }else{
              datas += 'Entrega : ' + ' ' + object.dataFechamentoFormatada 
            }
            
            linha += datas;
            linha +=  "</td>";

            linha += "<td  data-idDemanda='" + object.idDemanda + "' class='" + object.idTipoStatus + "'>" + object.nomeTipoStatus + "</td>";

            linha += "<td>"; 
            linha += "<div class='btn-group dropstart'><button type='button' class='btn' data-toggle='tooltip' data-placement='left' title='Opções' data-bs-toggle='dropdown' " +
            " aria-expanded='false' style='box-shadow:none'><i class='bi bi-three-dots-vertical'></i></button><ul class='dropdown-menu'>"

            linha += "<li class='ms-1 me-1 mt-1'><a class='btn btn-warning btn-sm w-100 text-start' href='visualizar.php?idDemanda=" + object.idDemanda + 
            "' role='button'><i class='bi bi-pencil-square'></i> Alterar</a></li>";

            linha += "</tr>";
            linha +="</ul></div>"
            linha += "</td>";
          }

          $("#dados").html(linha);

          if (typeof callback === 'function') {
            callback();
          }
        }
      });
    }

    /* helio 09112023 - ao clicar em ts-click, chama visualizar */
    $(document).on('click', '.ts-click', function() {
        window.location.href='visualizar.php?idDemanda=' + $(this).attr('data-idDemanda');
    });


    $("#FiltroTipoStatus").change(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    $("#FiltroClientes").change(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    $("#FiltroSolicitante").change(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    $("#FiltroServico").change(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    $("#FiltroUsuario").change(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    $("#FiltroStatusDemanda").change(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    $("#buscar").click(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    $("#FiltroPosicao").change(function() {
      buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
    });

    document.addEventListener("keypress", function(e) {
      if (e.key === "Enter") {
        buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroServico").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val());
      }
    });



    //Gabriel 22092023 id544 trocado setcookie por httpRequest enviado para gravar origem em session//ajax
    $(document).on('click', '#visualizarDemandaButton', function() {
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



    //**************exporta excel 
    function exportToExcel() {
      var idAtendenteValue = <?php echo $usuario["idCliente"] === NULL ? '$("#FiltroUsuario").val()' : 'null' ?>;
      var tamanhoValue = <?php echo $usuario["idCliente"] === NULL ? '$("#FiltroTamanho").val()' : 'null' ?>;
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../database/demanda.php?operacao=filtrar',
        data: {
          idCliente: $("#FiltroClientes").val(),
          idSolicitante: $("#FiltroSolicitante").val(),
          idAtendente: idAtendenteValue,
          idTipoStatus: $("#FiltroTipoStatus").val(),
          idServico: $("#FiltroServico").val(),
          statusDemanda: $("#FiltroStatusDemanda").val(),
          buscaDemanda: $("#buscaDemanda").val(),
          tamanho: tamanhoValue,
          urlContratoTipo: urlContratoTipo
        },
        success: function(json) {
          var excelContent =
            "<html xmlns:x='urn:schemas-microsoft-com:office:excel'>" +
            "<head>" +
            "<meta charset='UTF-8'>" +
            "</head>" +
            "<body>" +
            "<table>";

          excelContent += "<tr><th>Prioridade</th><th>ID</th><th>Cliente</th><th>Solicitante</th><th>Demanda</th><th>Responsavel</th><th>Abertura</th><th>Status</th><th>Ocorrencia</th><th>Tamanho</th></tr>";

          for (var i = 0; i < json.length; i++) {
            var object = json[i];
            excelContent += "<tr><td>" + object.prioridade + "</td>" +
              "<td>" + object.idDemanda + "</td>" +
              "<td>" + object.nomeCliente + "</td>" +
              "<td>" + object.nomeSolicitante + "</td>" +
              "<td>" + object.tituloDemanda + "</td>" +
              "<td>" + object.nomeAtendente + "</td>" +
              "<td>" + object.dataAbertura + "</td>" +
              "<td>" + object.nomeTipoStatus + "</td>" +
              "<td>" + object.nomeServico + "</td>" +
              "<td>" + object.tamanho + "</td></tr>";
          }

          excelContent += "</table></body></html>";

          var excelBlob = new Blob([excelContent], {
            type: 'application/vnd.ms-excel'
          });
          var excelUrl = URL.createObjectURL(excelBlob);
          var link = document.createElement("a");
          link.setAttribute("href", excelUrl);
          link.setAttribute("download", "demandas.xls");
          document.body.appendChild(link);

          link.click();

          document.body.removeChild(link);
        },
        error: function(e) {
          alert('Erro: ' + JSON.stringify(e));
        }
      });
    }



    //**************exporta csv
    function exportToCSV() {
      var idAtendenteValue = <?php echo $usuario["idCliente"] === NULL ? '$("#FiltroUsuario").val()' : 'null' ?>;
      var tamanhoValue = <?php echo $usuario["idCliente"] === NULL ? '$("#FiltroTamanho").val()' : 'null' ?>;
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../database/demanda.php?operacao=filtrar',
        data: {
          idCliente: $("#FiltroClientes").val(),
          idSolicitante: $("#FiltroSolicitante").val(),
          idAtendente: idAtendenteValue,
          idTipoStatus: $("#FiltroTipoStatus").val(),
          idServico: $("#FiltroServico").val(),
          statusDemanda: $("#FiltroStatusDemanda").val(),
          buscaDemanda: $("#buscaDemanda").val(),
          tamanho: tamanhoValue,
          urlContratoTipo: urlContratoTipo
        },
        success: function(json) {
          var csvContent = "data:text/csv;charset=utf-8,\uFEFF";
          csvContent += "Prioridade,ID,Cliente,Solicitante,Demanda,Responsavel,Abertura,Status,Ocorrencia,Tamanho,Previsao\n";

          for (var i = 0; i < json.length; i++) {
            var object = json[i];
            csvContent += object.prioridade + "," +
              object.idDemanda + "," +
              object.nomeCliente + "," +
              object.nomeSolicitante + "," +
              object.tituloDemanda + "," +
              object.nomeAtendente + "," +
              object.dataAbertura + "," +
              object.nomeTipoStatus + "," +
              object.nomeServico + "," +
              object.tamanho + "," +
              object.horasPrevisao + "\n";
          }

          var encodedUri = encodeURI(csvContent);
          var link = document.createElement("a");
          link.setAttribute("href", encodedUri);
          link.setAttribute("download", "demandas.csv");
          document.body.appendChild(link);

          link.click();

          document.body.removeChild(link);
        },
        error: function(e) {
          alert('Erro: ' + JSON.stringify(e));
        }
      });
    }

    //**************exporta PDF
    function exportToPDF() {
      var idAtendenteValue = <?php echo $usuario["idCliente"] === NULL ? '$("#FiltroUsuario").val()' : 'null' ?>;
      var tamanhoValue = <?php echo $usuario["idCliente"] === NULL ? '$("#FiltroTamanho").val()' : 'null' ?>;
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../database/demanda.php?operacao=filtrar',
        data: {
          idCliente: $("#FiltroClientes").val(),
          idSolicitante: $("#FiltroSolicitante").val(),
          idAtendente: idAtendenteValue,
          idTipoStatus: $("#FiltroTipoStatus").val(),
          idServico: $("#FiltroServico").val(),
          statusDemanda: $("#FiltroStatusDemanda").val(),
          buscaDemanda: $("#buscaDemanda").val(),
          tamanho: tamanhoValue,
          urlContratoTipo: urlContratoTipo
        },
        success: function(json) {
          var tableContent =
            "<table>" +
            "<tr><th>Prioridade</th><th>ID</th><th>Cliente</th><th>Solicitante</th><th>Demanda</th><th>Responsavel</th><th>Abertura</th><th>Status</th><th>Ocorrencia</th><th>Tamanho</th></tr>";

          for (var i = 0; i < json.length; i++) {
            var object = json[i];
            tableContent += "<tr><td>" + object.prioridade + "</td>" +
              "<td>" + object.idDemanda + "</td>" +
              "<td>" + object.nomeCliente + "</td>" +
              "<td>" + object.nomeSolicitante + "</td>" +
              "<td>" + object.tituloDemanda + "</td>" +
              "<td>" + object.nomeAtendente + "</td>" +
              "<td>" + object.dataAbertura + "</td>" +
              "<td>" + object.nomeTipoStatus + "</td>" +
              "<td>" + object.nomeServico + "</td>" +
              "<td>" + object.tamanho + "</td></tr>";
          }

          tableContent += "</table>";

          var printWindow = window.open('', '', 'width=800,height=600');
          printWindow.document.open();
          printWindow.document.write('<html><head><title>Demandas</title></head><body>');
          printWindow.document.write(tableContent);
          printWindow.document.write('</body></html>');
          printWindow.document.close();

          printWindow.onload = function() {
            printWindow.print();
            printWindow.onafterprint = function() {
              printWindow.close();
            };
          };

          printWindow.onload();
        },
        error: function(e) {
          alert('Erro: ' + JSON.stringify(e));
        }
      });
    }





    $("#export").click(function() {
      var selectedOption = $("#exportoptions").val();
      if (selectedOption === "excel") {
        exportToExcel();
      } else if (selectedOption === "pdf") {
        exportToPDF();
      } else if (selectedOption === "csv") {
        exportToCSV();
      }
    });

    //Gabriel 27052024 removido apontamento quill inexistente/antigo
  </script>

  <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>