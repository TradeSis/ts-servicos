<?php
// Lucas 26102023 id643 revisao geral
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/tarefas.php');
include_once(__DIR__ . '/../database/demanda.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');



$clientes = buscaClientes();
$atendentes = buscaAtendente();
$ocorrencias = buscaTipoOcorrencia();

$idAtendente = null;
$statusTarefa = "1"; //ABERTO

// Lucas 26062024 id1092, adicionado filtro de Cliente
// Lucas 26102023 id643 adicionado buscaUsuarios para usar no select de Responsavel - trazer o usuario logado como primeira opção
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);

if ($usuario["idCliente"] == null) {
  $clientes = buscaClientes($usuario["idCliente"]);
  $idCliente = null;
} else {
  $clientes = array(buscaClientes($usuario["idCliente"]));
  $idCliente = $usuario["idCliente"];
}

if (isset($_SESSION['filtro_agenda'])) {
    $filtroEntrada = $_SESSION['filtro_agenda'];
    $idAtendente = $filtroEntrada['idAtendente'];
    $statusTarefa = $filtroEntrada['statusTarefa'];
    $idCliente = $filtroEntrada['idCliente'];
}
$tarefas = buscaTarefas(null, null, $idAtendente, $statusTarefa, $idCliente);


$demandas = buscaDemandasAbertas();


?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>
    <link rel="stylesheet" type="text/css" href="<?php echo URLROOT ?>/vendor/fullcalendar/fullcalendar.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo URLROOT ?>/sistema/css/calendario.css">
</head>


<body>


    <!--------- MENUFILTROS --------->
    <!-- Lucas 26102023 id643 ajustado estutura do filtro para o novo padrao -->
    <div id="ts-menuFiltros" class="ts-menuFiltros px-3" style="margin-top:48px">
    <label class="pl-2" for="">Filtrar por:</label>
        
            <div class="ls-label col-sm-12 mr-1"> <!-- ABERTO/FECHADO -->
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
                        } ?> value="0">Fechado</option>
                    </select>
                </form>
                    </div>

        <div class="col-sm text-end mt-2">
            <a id="limpar-button" role="button" class="btn btn-sm bg-info text-white">Limpar</a>
        </div>
    </div>

    <!-- Lucas 27062024 id 1092 adicionado div para filtros -->
    <div class="col ts-filtroCalendario mostra gap-1">
        <div class="col">
            <select class="form-select ts-input <?php if ($usuario["idCliente"] != null) {
                                                    echo "ts-displayDisable";
                                                } ?>" name="idCliente" id="FiltroCliente">
                <option value="<?php echo null ?>" data-dados='Clientes'>
                    <?php echo "Clientes" ?>
                </option>
                <?php
                foreach ($clientes as $cliente) {
                ?>
                    <option <?php
                        if ($cliente['idCliente'] == $idCliente) {
                            echo "selected";
                        }
                        ?> value="<?php echo $cliente['idCliente'] ?>" data-dados='<?php echo $cliente['nomeCliente'] ?>'>
                    <?php echo $cliente['nomeCliente'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col">
            <select class="form-select ts-input" name="idAtendente" id="FiltroAtendente">
                <option value="<?php echo null ?>" data-dados='Responsável'>
                    <?php echo "Responsável" ?>
                </option>
                <?php
                foreach ($atendentes as $atendente) {
                ?>
                    <option <?php
                        if ($atendente['idUsuario'] == $idAtendente) {
                            echo "selected";
                        }
                        ?> value="<?php echo $atendente['idUsuario'] ?>" data-dados='<?php echo $atendente['nomeUsuario'] ?>'>
                        <?php echo $atendente['nomeUsuario'] ?>
                    </option>
                    <?php } ?>
            </select>
        </div>
    </div>

    <div class="mt-3" id="calendar"></div>
    

<!--------- INSERIR/AGENDAR --------->
<!-- Lucas 26102023 id643 alterado estrutura do modal para modelo Boostrap 5 -->
<div class="modal fade bd-example-modal-lg" id="inserirModal" tabindex="-1"
    aria-labelledby="inserirModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Inserir Tarefa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" id="inserirForm">
            <div class="row mt-3">
              <div class="col-md-6">
                <label class='form-label ts-label'>Tarefa</label>
                    <input type="text" class="form-control ts-input" name="tituloTarefa" id="newtitulo" autocomplete="off" required>
                    <input type="hidden" class="form-control ts-input" name="idDemanda" value="null" id="newidDemanda">
              </div>
              <div class="col-md-6">
                  <label class='form-label ts-label'>Cliente</label>
                    <select class="form-select ts-input" name="idCliente" id="newidCliente">
                      <option value="null"></option>
                      <?php
                      foreach ($clientes as $cliente) {
                        ?>
                      <option value="<?php echo $cliente['idCliente'] ?>">
                        <?php echo $cliente['nomeCliente'] ?>
                      </option>
                      <?php } ?>
                    </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-6">
                  <label class='form-label ts-label'>Reponsável</label>
                    <select class="form-select ts-input" name="idAtendente" id="newidAtendente">
                      <!-- gabriel 13102023 id596 removido a possibilidade de adicionar tarefa sem responsável -->
                      <?php
                      foreach ($atendentes as $atendente) {
                        ?>
                        <!-- Lucas 26102023 id643 select vai trazer o usuario logado como primeira opção -->
                        <option <?php
                            if ($atendente['idUsuario'] == $usuario['idUsuario']) {
                                echo "selected";
                            }
                            ?> value="<?php echo $atendente['idUsuario'] ?>">
                            <?php echo $atendente['nomeUsuario'] ?>
                        </option>

                      <?php } ?>
                    </select>
              </div>
              <div class="col-md-6">
                  <label class='form-label ts-label'>Ocorrência</label>
                    <select class="form-select ts-input" name="idTipoOcorrencia" id="newidTipoOcorrencia" required>
                      <option value="<?php echo null ?>">Selecione</option>
                      <?php
                      foreach ($ocorrencias as $ocorrencia) {
                        ?>
                        <option value="<?php echo $ocorrencia['idTipoOcorrencia'] ?>">
                          <?php echo $ocorrencia['nomeTipoOcorrencia'] ?>
                        </option>
                      <?php } ?>
                    </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-4">
                  <label class="form-label ts-label">Data Previsão</label>
                  <input type="date" class="form-control ts-input" name="Previsto" autocomplete="off" required>
              </div>
              <div class="col-md-4">
                  <label class="form-label ts-label">Inicio</label>
                  <input type="time" class="form-control ts-input" name="horaInicioPrevisto" autocomplete="off">
              </div>
              <div class="col-md-4">
                  <label class="form-label ts-label">Fim</label>
                  <input type="time" class="form-control ts-input" name="horaFinalPrevisto" autocomplete="off">
              </div>
            </div>
            </div><!--modal body-->
            <div class="modal-footer">
              <button type="submit" class="btn btn-warning" id="inserirStartBtn">Start</button>
              <button type="submit" class="btn btn-success" id="inserirBtn">Inserir</button>
            </div>
          </form>
        
      </div>
    </div>
  </div>

  <!--Lucas 18102023 ID 602 alterado nome do arquivo para modalTarefa_alterar -->
  <?php 
  if($idCliente == null){
    include 'modalTarefa_alterar.php';
  }else{
    include 'modalTarefa_visualizar.php';
  }
   ?>

  <!-- LOCAL PARA COLOCAR OS JS -->

  <?php include_once ROOT . "/vendor/footer_js.php"; ?>
  <!-- QUILL editor -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <!-- Calendario -->
  <script type="text/javascript" src="<?php echo URLROOT ?>/vendor/fullcalendar/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo URLROOT ?>/vendor/fullcalendar/fullcalendar.min.js"></script>
  <script type="text/javascript" src="<?php echo URLROOT ?>/vendor/fullcalendar/pt-br.min.js"></script>

  <script type="text/javascript">

        $(document).on('click', '.fc-month-button', function () {
            gravaUltimo('month');
        });
        $(document).on('click', '.fc-agendaWeek-button', function () {
            gravaUltimo('agendaWeek');
        });
        $(document).on('click', '.fc-agendaDay-button', function () {
            gravaUltimo('agendaDay');
        });
        $(document).on('click', '.fc-schedule-button', function () {
            gravaUltimo('schedule');
        });

        //Gabriel 22092023 id542 function gravaUltimo em session
        function gravaUltimo(tab) {
            $.ajax({
                type: 'POST',
                url: '../database/tarefas.php?operacao=ultimoTab',
                data: { ultimoTab: tab },
                success: function (response) {
                    console.log('Session variable set successfully.');
                },
                error: function (xhr, status, error) {
                    console.error('An error occurred:', error);
                }
            });
        }

        $(document).ready(function () {
            //Gabriel 22092023 id542 verifica se possui $_SESSION['ultimoTab'] se não, padrão (mês)
            var vdefaultView = '<?php echo isset($_SESSION['ultimoTab']) ? $_SESSION['ultimoTab'] : 'schedule' ?>';
            var today = new Date();
            var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 3, 0);
            $("#calendar").fullCalendar({
                header: {
                    left: "filtro, prev,next today, filtro2",
                    center: "title",
                    right: "month,agendaWeek,agendaDay,schedule, novo"
                },
                locale: 'pt-br',
                defaultView: vdefaultView,
                navLinks: true,
                editable: true,
                eventLimit: false,
                selectable: true,
                selectHelper: false,
                views: {
                    month: {
                        timeFormat: 'HH:mm'
                    },
                    agendaWeek: {
                        minTime: "08:00:00",
                        maxTime: "20:00:00"
                    },
                    agendaDay: {
                        minTime: "08:00:00",
                        maxTime: "20:00:00"
                    },
                    schedule: {
                        type: 'list',
                        visibleRange: {
                            start: today,
                            end: lastDayOfMonth
                        },
                        buttonText: 'Programação'
                    }
                },
                customButtons: {
                    filtro: {
                        text: 'Filtro',
                        click: function () {
                            /* alert('oi') */
                            //Gabriel 06102023 ID 596 ajustado para ID ao invés de classe
                            $('#ts-menuFiltros').toggleClass('mostra');
                        }
                    },

                    //Lucas 27062024 id1092 adicionado novo bot�o para filtros 
                    filtro2: {
                        text: $('#FiltroCliente :selected').data('dados') + ' | ' + $('#FiltroAtendente :selected').data('dados'),
                        click: function () {
                            $('.ts-filtroCalendario').toggleClass('mostra');
                        }
                    },
                    //Lucas 26062024 id1092 condi��o para cliente

                    <?php if($idCliente == null){ ?>
                        novo: {
                        text: 'Novo',
                        click: function () {
                            //Gabriel 11102023 ID 596 alterado para utilizar o mesmo modal de inserir
                            $('#inserirModal').modal('show');
                        }
                    }
                    <?php } ?>
          
                },
                events: [
                    <?php
                    $colors = array('#FF6B6B', '#77DD77', '#6CA6CD', '#FFD700', '#FF69B4', '#00CED1');
                    // helio 26092023 - inicio teste de cores
                    $cor_previsto = '#77DD77';
                    $cor_executando = '#FF6B6B';
                    $cor_diatodo = '#6CA6CD';
                    $colorIndex = 0;
                    foreach ($tarefas as $tarefa) {
                        $color = $colors[$colorIndex % count($colors)];
                        $colorIndex++;

                        if ($tarefa['idDemanda'] !== null) {
                            $tituloTarefa = empty($tarefa['tituloTarefa']) ? $tarefa['tituloDemanda'] . " (" . $tarefa['nomeUsuario'] . ")" : $tarefa['tituloTarefa'];
                        } else {
                            $tituloTarefa = empty($tarefa['tituloTarefa']) ? $tarefa['tituloTarefa'] . " (" . $tarefa['nomeUsuario'] . ")" : $tarefa['tituloTarefa'];
                        }

                        // substituindo dataPrevisto por Real, quando Real existir
                        if ($tarefa['dataReal'] != null) {
                            $dataPrevisto = $tarefa['dataReal'];
                            $allDay = false;
                            $dtf = $tarefa['horaFinalReal'];
                            // sem realfinal, coloca sempre mais 1 hora, para melhorar visualmente
                            if ($tarefa['horaFinalReal'] == null) {
                                $dtf = date('H:00:00', strtotime('1 hour'));
                                $color = $cor_executando; // helio 26092023 - inicio teste de cores
                            }
                            $horaInicioPrevisto = is_null($tarefa['horaInicioReal']) ? "08:00:00" : $tarefa['horaInicioReal'];
                            $horaFinalPrevisto = is_null($tarefa['horaFinalReal']) ? $dtf : $tarefa['horaFinalReal'];
                        } else {
                            $cor = $cor_previsto; // helio 26092023 - inicio teste de cores
                            if ($tarefa['horaInicioPrevisto'] == null) {
                                $allDay = true;
                            } else {
                                $allDay = false;
                            } // teste de allDay
                            $dataPrevisto = $tarefa['Previsto'];
                            $horaInicioPrevisto = is_null($tarefa['horaInicioPrevisto']) ? "08:00:00" : $tarefa['horaInicioPrevisto'];
                            $horaFinalPrevisto = is_null($tarefa['horaFinalPrevisto']) ? "19:00:00" : $tarefa['horaFinalPrevisto'];
                        }
                        if ($allDay == true) {
                            $color = $cor_diatodo;
                        }
                        ?>
                        {
                            allDay: <?php if ($allDay == true) {
                                echo 'true';
                            } else {
                                echo 'false';
                            } // teste de allDay ?>,
                            _id: '<?php echo $tarefa['idTarefa']; ?>',
                            title: '<?php echo $tituloTarefa ?>',
                            start: '<?php echo $dataPrevisto . ' ' . $horaInicioPrevisto; // uso dataPrevisto com real/previsto ?>',
                            end: '<?php echo $dataPrevisto . ' ' . $horaFinalPrevisto; // uso dataPrevisto com real/previsto ?>',
                            idTarefa: '<?php echo $tarefa['idTarefa']; ?>',
                            color: '<?php echo $color; ?>'
                            //Gabriel 11102023 ID 596 removido dados desnecessários
                        },
                    <?php } ?>
                ],
                eventRender: function (event, element) {
                    element.css('font-weight', 'bold');
                },
                eventClick: function (calEvent, jsEvent, view) {
                    //Gabriel 11102023 ID 596 chama o mesmo script que preenche o alterarModal
                    var idTarefa = calEvent.idTarefa;
                    BuscarAlterar(idTarefa);
                }
            });
        });

        function loadPage(url) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", url, false);
            xhr.send();
            if (xhr.status === 200) {
                var content = xhr.responseText;
                document.open();
                document.write(content);
                document.close();
            }
        }
        //Gabriel 11102023 ID 596 alterado para utilizar o mesmo modal de inserir
        var inserirModal = document.getElementById("inserirModal");

        var inserirBtn = document.querySelector("button[data-target='#inserirModal']");

        inserirBtn.onclick = function () {
            inserirModal.style.display = "block";
        };


        window.onclick = function (event) {
            if (event.target == inserirModal) {
                inserirModal.style.display = "none";
            }
        };

        $('.btnAbre').click(function () {
            //Gabriel 06102023 ID 596 ajustado para ID ao invés de classe
            $('#menuFiltros').toggleClass('mostra');
            $('.diviFrame').toggleClass('mostra');
        });

        function refreshPage() {
            window.location.reload();
        }

    </script>

    <script>
        $(document).ready(function () {
            buscar($("#FiltroAtendente").val(), $("#FiltroStatusTarefa").val(), $("#FiltroCliente").val());

            $("#FiltroAtendente").change(function () {
                buscar($("#FiltroAtendente").val(), $("#FiltroStatusTarefa").val(), $("#FiltroCliente").val());
                window.location.reload();
            });

            $("#FiltroStatusTarefa").change(function () {
                buscar($("#FiltroAtendente").val(), $("#FiltroStatusTarefa").val(), $("#FiltroCliente").val());
                window.location.reload();
            });

            $("#FiltroCliente").change(function () {
                buscar($("#FiltroAtendente").val(), $("#FiltroStatusTarefa").val(), $("#FiltroCliente").val());
                window.location.reload();
            });

            function buscar(idAtendente, statusTarefa, idCliente) {
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../database/tarefas.php?operacao=filtroAgenda',
                    beforeSend: function () {
                        $("#dados").html("Carregando...");
                    },
                    data: {
                        idAtendente: idAtendente,
                        statusTarefa: statusTarefa,
                        idCliente: idCliente
                    },
                    success: function (data) {
                        $("#dados").html(data);
                    },
                    error: function (e) {
                        alert('Erro: ' + JSON.stringify(e));
                    }
                });
            }

            $("#limpar-button").click(function () {
                buscar(null, null);
                window.location.reload();
            });
        });

        $(document).ready(function () {
            $("#inserirForm").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                var vurl;
                if ($("#inserirStartBtn").prop("clicked")) {
                    vurl = "../database/tarefas.php?operacao=inserirStart";
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

            $("#inserirStartBtn").click(function () {
                $("#inserirBtn").prop("clicked", false);
                $(this).prop("clicked", true);
            });

            $("#inserirBtn").click(function () {
                $("#inserirStartBtn").prop("clicked", false);
                $(this).prop("clicked", true);
            });

            $("#alterarForm").submit(function (event) {
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
        });

    </script>
</body>

</html>