<?php
//Gabriel 06102023 ID 596 mudanças em agenda e tarefas

include_once(__DIR__ . '/../head.php');
include_once(__DIR__ . '/../database/tarefas.php');
include_once(__DIR__ . '/../database/demanda.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');

//Lucas 22112023 id 688 - Removido visão do cliente ($ClienteSession)

$clientes = buscaClientes();
$atendentes = buscaAtendente();
$ocorrencias = buscaTipoOcorrencia();
$demandas = buscaDemandasAbertas();
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

$Periodo = null;
$filtroEntrada = null;
$idTipoOcorrencia = null;
$PeriodoInicio = null;
$PeriodoFim = null;
$PrevistoOrdem = null;
$RealOrdem = null;


if (isset($_SESSION['filtro_tarefas'])) {
    $filtroEntrada = $_SESSION['filtro_tarefas'];
    $idCliente = $filtroEntrada['idCliente'];
    $idAtendente = $filtroEntrada['idAtendente'];
    $idTipoOcorrencia = $filtroEntrada['idTipoOcorrencia'];
    $statusTarefa = $filtroEntrada['statusTarefa'];
    $Periodo = $filtroEntrada['Periodo'];
    $PeriodoInicio = $filtroEntrada['PeriodoInicio'];
    $PeriodoFim = $filtroEntrada['PeriodoFim'];
    $PrevistoOrdem = $filtroEntrada['PrevistoOrdem'];
    $RealOrdem = $filtroEntrada['RealOrdem'];
}

$tarefas = buscaTarefas(null, null, $idAtendente, $statusTarefa);

$previsaoChecked = ($Periodo === '1') ? 'checked' : '';
$realizadoChecked = ($Periodo === '0') ? 'checked' : '';
$Checked = ($Periodo === null) ? 'checked' : '';
?>

<style>
    body {
        margin-bottom: 30px;
    }

    .line {
        width: 100%;
        border-bottom: 1px solid #707070;
    }

    #tabs .tab {
        display: inline-block;
        padding: 5px 10px;
        cursor: pointer;
        position: relative;
        z-index: 5;
        border-radius: 3px 3px 0 0;
        background-color: #567381;
        color: #EEEEEE;
    }

    #tabs .whiteborder {
        border: 1px solid #707070;
        border-bottom: 1px solid #fff;
        border-radius: 3px 3px 0 0;
        background-color: #EEEEEE;
        color: #567381;
    }

    #tabs .tabContent {
        position: relative;
        top: -1px;
        z-index: 1;
        padding: 10px;
        border-radius: 0 0 3px 3px;
        color: black;
    }

    #tabs .hide {
        display: none;
    }

    #tabs .show {
        display: block;
    }

    .modal-backdrop {
        background-color: rgba(200, 200, 200, 0.5);
    }

    ::-webkit-scrollbar {
        width: 0.5em;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #000000;
    }

    ::-webkit-scrollbar {
        width: 0;
        background-color: transparent;
    }

    .fc-novo-button {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745
    }

    .filtroresponsavel {
        width: 180px;
        position: fixed;
        top: -112px;
        left: 230px;
    }

    #calendar .fc-toolbar h2 {
        font-size: 20px;
    }

    .nav-link {
        display: inline-block;
        padding: 5px 10px;
        cursor: pointer;
        position: relative;
        z-index: 5;
        border-radius: 3px 3px 0 0;
        background-color: #567381;
        color: #EEEEEE;
    }
    .nav-link .active {
        border: 1px solid #707070;
        border-bottom: 1px solid #fff;
        border-radius: 3px 3px 0 0;
        background-color: #EEEEEE;
        color: #567381;
    }
    
</style>

<body class="bg-transparent">
    <div class="container-fluid">
        <div id="tabs">
            <!-- gabriel 13102023 id 596 fix menu ao contrario -->
            <div class="tab whiteborder" id="tab-agenda">Agenda</div>
            <div class="tab" id="tab-execucao">Execução</div>
            <div class="line"></div>
            <div class="tabContent">
                <?php include_once 'agenda.php'; ?>
            </div>
            <div class="tabContent">
                <?php include_once 'tarefas.php'; ?>
            </div>
        </div>
    </div>

    <!--------- FILTRO PERIODO --------->
    <div class="modal fade bd-example-modal-lg" id="periodoModal" tabindex="-1" role="dialog"
        aria-labelledby="periodoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Filtro Periodo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?php echo null ?>" id="Radio"
                                name="FiltroPeriodo" <?php echo $Checked; ?> hidden>
                            <input class="form-check-input" type="radio" value="1" id="PrevisaoRadio"
                                name="FiltroPeriodo" <?php echo $previsaoChecked; ?>>
                            <label class="form-check-label" for="PrevisaoRadio">
                                Previsão
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" id="RealizadoRadio"
                                name="FiltroPeriodo" <?php echo $realizadoChecked; ?>>
                            <label class="form-check-label" for="RealizadoRadio">
                                Realizado
                            </label>
                        </div>
                        <div class="row" id="conteudoReal">
                            <div class="col">
                                <label class="labelForm">Começo</label>
                                <?php if ($PeriodoInicio != null) { ?>
                                <input type="date" class="data select form-control" id="FiltroPeriodoInicio"
                                    value="<?php echo $PeriodoInicio ?>" name="PeriodoInicio" autocomplete="off">
                                <?php } else { ?>
                                <input type="date" class="data select form-control" id="FiltroPeriodoInicio"
                                    name="PeriodoInicio" autocomplete="off">
                                <?php } ?>
                            </div>
                            <div class="col">
                                <label class="labelForm">Fim</label>
                                <?php if ($PeriodoFim != null) { ?>
                                <input type="date" class="data select form-control" id="FiltroPeriodoFim"
                                    value="<?php echo $PeriodoFim ?>" name="PeriodoFim" autocomplete="off">
                                <?php } else { ?>
                                <input type="date" class="data select form-control" id="FiltroPeriodoFim"
                                    name="PeriodoFim" autocomplete="off">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm" style="text-align:left;margin-left:10px">
                                <button type="button" class="btn btn-primary" onClick="limparPeriodo()">Limpar</button>
                            </div>
                            <div class="col-sm" style="text-align:right;margin-right:10px">
                                <button type="button" class="btn btn-success" id="filtrarButton"
                                    data-dismiss="modal">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!--------- MODAL STOP Tab EXECUCAO --------->
    <div class="modal fade bd-example-modal-lg" id="stopexecucaomodal" tabindex="-1" role="dialog"
        aria-labelledby="stopexecucaomodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Stop Tarefa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- gabriel 13102023 id 596 adicionado id -->
                    <form method="post" id="stopForm">
                        <div class="container-fluid p-0">
                            <div class="col">
                                <span class="tituloEditor">Comentários</span>
                            </div>
                            <div class="quill-stop" style="height:20vh !important"></div>
                            <textarea style="display: none" id="quill-stop" name="comentario"></textarea>
                        </div>
                        <div class="col-md form-group" style="margin-top: 5px;">
                            <input type="hidden" class="form-control" name="idCliente"
                                value="<?php echo $demanda['idCliente'] ?>" readonly>
                            <input type="hidden" class="form-control" name="idUsuario"
                                value="<?php echo $usuario['idUsuario'] ?>" readonly>
                            <input type="hidden" class="form-control" name="idTarefa" id="idTarefa-stopexecucao" />
                            <input type="hidden" class="form-control" name="idDemanda" id="idDemanda-stopexecucao" />
                            <input type="hidden" class="form-control" name="tipoStatusDemanda"
                                id="status-stopexecucao" />
                            <input type="time" class="form-control" name="horaInicioCobrado"
                                id="horaInicioReal-stopexecucao" step="2" readonly style="display: none;" />

                        </div>
                </div>
                <div class="modal-footer">
                    <div class="col align-self-start pl-0">
                        <!-- gabriel 13102023 id 596 fix ao dar stop vai para demanda -->
                        <button type="submit" id="realizadoFormbutton" class="btn btn-warning float-left">Entregar</button>
                    </div>
                        <!-- gabriel 13102023 id 596 fix ao dar stop vai para demanda -->
                        <button type="submit" id="stopFormbutton" class="btn btn-danger">Stop</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--------- INSERIR/AGENDAR --------->
    <div class="modal fade bd-example-modal-lg" id="inserirModal" tabindex="-1" role="dialog"
        aria-labelledby="inserirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Tarefa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container">
                    <form method="post" id="inserirForm">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class='control-label' for='inputNormal' style="margin-top: 10px;">Tarefa</label>
                                <div class="for-group" style="margin-top: 22px;">
                                    <input type="text" class="form-control" name="tituloTarefa" id="newtitulo" autocomplete="off"
                                        required>
                                </div>
                                <input type="hidden" class="form-control" name="idDemanda" value="null" id="newidDemanda">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class='control-label' for='inputNormal'>Cliente</label>
                                    <div class="form-group" style="margin-top: 40px;">
                                        <select class="form-control" name="idCliente" id="newidCliente">
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class='control-label' for='inputNormal'>Reponsável</label>
                                    <div class="form-group" style="margin-top: 20px;">
                                        <select class="form-control" name="idAtendente" id="newidAtendente">
                                            <!-- gabriel 13102023 id596 removido a possibilidade de adicionar tarefa sem responsável -->
                                            <?php
                                            foreach ($atendentes as $atendente) {
                                                ?>
                                            <option value="<?php echo $atendente['idUsuario'] ?>">
                                                <?php echo $atendente['nomeUsuario'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class='control-label' for='inputNormal'>Ocorrência</label>
                                    <div class="form-group" style="margin-top: 20px;">
                                        <select class="form-control" name="idTipoOcorrencia" id="newidTipoOcorrencia">
                                            <option value="null">Selecione</option>
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="labelForm">Data Previsão</label>
                                    <input type="date" class="data select form-control" name="Previsto"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="labelForm">Inicio</label>
                                    <input type="time" class="data select form-control" name="horaInicioPrevisto"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="labelForm">Fim</label>
                                    <input type="time" class="data select form-control" name="horaFinalPrevisto"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent" style="text-align:right">
                            <button type="submit" class="btn btn-warning" id="inserirStartBtn">Start</button>
                            <button type="submit" class="btn btn-success" id="inserirBtn">Inserir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'alterarTarefaModal.php'; ?>

    <script>

        $(document).on('click', '.stopButton', function () {
            var idTarefa = $(this).data('id');
            var tipoStatusDemanda = $(this).data('status');
            var horaInicioCobrado = $(this).data('data-execucao');
            var idDemanda = $(this).data('demanda');
            $.ajax({
                //lucas 25092023 ID 358 Modificado operação de tarefas
                url: "../database/tarefas.php?operacao=stopsemdemanda",
                method: "POST",
                dataType: "json",
                data: {
                    idTarefa: idTarefa,
                    tipoStatusDemanda: tipoStatusDemanda,
                    horaInicioCobrado: horaInicioCobrado,
                    idDemanda: idDemanda
                },
                success: function (msg) {
                    if (msg.retorno == "ok") {
                        //gabriel 13102023 id 596 fix atualizar pagina correta
                        refreshTab('execucao');
                    }
                }
            });
        });

        $(document).on('click', '.startButton', function () {
            var idTarefa = $(this).data('id');
            var tipoStatusDemanda = $(this).data('status');
            var idDemanda = $(this).data('demanda');
            $.ajax({
                url: "../database/tarefas.php?operacao=start",
                method: "POST",
                dataType: "json",
                data: {
                    idTarefa: idTarefa,
                    tipoStatusDemanda: tipoStatusDemanda,
                    idDemanda: idDemanda
                },
                success: function (msg) {
                    if (msg.retorno == "ok") {
                        //gabriel 13102023 id 596 fix atualizar pagina correta
                        refreshTab('execucao');
                    }
                }
            });
        });

        $(document).on('click', '.realizadoButton', function () {
            var idTarefa = $(this).data('id');
            var tipoStatusDemanda = $(this).data('status');
            var idDemanda = $(this).data('demanda');
            $.ajax({
                url: "../database/tarefas.php?operacao=realizado",
                method: "POST",
                dataType: "json",
                data: {
                    idTarefa: idTarefa,
                    tipoStatusDemanda: tipoStatusDemanda,
                    idDemanda: idDemanda
                },
                success: function (msg) {
                    if (msg.retorno == "ok") {
                        //gabriel 13102023 id 596 fix atualizar pagina correta
                        refreshTab('execucao');
                    }
                }
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
                    success: function (msg) {
                        refreshTab('execucao');
                    }
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
                    //gabriel 13102023 id 596 fix atualizar pagina correta
                    success: function (msg) {
                        refreshTab('execucao');
                    }
                });
            });

            //gabriel 13102023 id 596 submit stopForm para evitar redirecionamento para demanda
            $("#stopForm").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ', ' + pair[1]);
                }
                var vurl;
                if ($("#realizadoFormbutton").is(":focus")) {
                    vurl = "../database/demanda.php?operacao=realizado";
                } 
                if ($("#stopFormbutton").is(":focus")) {
                    vurl = "../database/tarefas.php?operacao=stop";
                } 
                $.ajax({
                    url: vurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        refreshTab('execucao');
                    }
                });
            });
        });


        //Gabriel 22092023 id544 trocado setcookie por httpRequest enviado para gravar origem em session//ajax
        $("#visualizarDemandaButton").click(function () {
            var currentPath = window.location.pathname;
            $.ajax({
                type: 'POST',
                url: '../database/demanda.php?operacao=origem',
                data: { origem: currentPath },
                success: function (response) {
                    console.log('Session variable set successfully.');
                },
                error: function (xhr, status, error) {
                    console.error('An error occurred:', error);
                }
            });
        });

    </script>
    <script>
        var tab;
        var tabContent;

        window.onload = function () {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            //gabriel 13102023 id 596 fix menu ao contrario
            if (id === 'execucao') {
                showTabsContent(1);
            }
        }

        document.getElementById('tabs').onclick = function (event) {
            var target = event.target;
            if (target.className == 'tab') {
                for (var i = 0; i < tab.length; i++) {
                    if (target == tab[i]) {
                        showTabsContent(i);
                        break;
                    }
                }
            }
        }

        function hideTabsContent(a) {
            for (var i = a; i < tabContent.length; i++) {
                tabContent[i].classList.remove('show');
                tabContent[i].classList.add("hide");
                tab[i].classList.remove('whiteborder');
            }
        }

        function showTabsContent(b) {
            if (tabContent[b].classList.contains('hide')) {
                hideTabsContent(0);
                tab[b].classList.add('whiteborder');
                tabContent[b].classList.remove('hide');
                tabContent[b].classList.add('show');
            }
        }


        var quillstop = new Quill('.quill-stop', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'size': ['small', false, 'large', 'huge']
                    }],
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['link', 'image', 'video', 'formula'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],
                ]
            }
        });

        /* lucas 22092023 ID 358 Modificado nome da classe do editor */
        quillstop.on('text-change', function (delta, oldDelta, source) {
            $('#quill-stop').val(quillstop.container.firstChild.innerHTML);
        });

        function refreshPage(tab, idDemanda) {
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=' + tab + '&&idDemanda=' + idDemanda;
            window.location.href = newUrl;
        }
    </script>

</body>

</html>