<?php
//Lucas 22112023 id 688 - Melhorias em Demandas
//Gabriel 11102023 ID 596 mudanças em agenda e tarefas
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat
//lucas 25092023 ID 358 Demandas/Comentarios
// Gabriel 22092023 id 544 Demandas - Botão Voltar
//lucas 22092023 ID 358 Demandas/Comentarios 

include_once '../header.php';
include_once '../database/demanda.php';
include_once '../database/contratos.php';
include_once '../database/tarefas.php';
include_once '../database/tipostatus.php';
include_once '../database/tipoocorrencia.php';

include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(ROOT . '/cadastros/database/servicos.php');

$usuario = buscaUsuarios(null, $_SESSION['idLogin']);

$idDemanda = $_GET['idDemanda'];
$idAtendente = $_SESSION['idLogin'];
$ocorrencias = buscaTipoOcorrencia();
$tiposstatus = buscaTipoStatus();
$demanda = buscaDemandas($idDemanda);

if ($idDemanda !== "") {
    $tarefas = buscaTarefas($idDemanda);
    $horas = buscaHoras($idDemanda);
    $comentarios = buscaComentarios($idDemanda);
}

$servicos = buscaServicos();
$idTipoStatus = $demanda['idTipoStatus'];
$atendentes = buscaAtendente();
$cliente = buscaClientes($demanda["idCliente"]);
$clientes = buscaClientes();
$contratos = buscaContratosAbertos($demanda["idCliente"]);
$horasReal = buscaTotalHorasReal(null, $idDemanda);
if($horasReal['totalHorasReal'] !== null){
	$totalHorasReal = date('H:i', strtotime($horasReal['totalHorasReal']));
}else{
	$totalHorasReal = "00:00";
}
//Lucas 22112023 id 688 - Removido visão do cliente ($ClienteSession)

if ($demanda['dataFechamento'] == null) {
    $dataFechamento =  'dd/mm/aaaa';
} else {
    $dataFechamento = date('d/m/Y H:i', strtotime($demanda['dataFechamento']));
}
$statusEncerrar = array(
    TIPOSTATUS_FILA,
    TIPOSTATUS_PAUSADO,
    TIPOSTATUS_RETORNO,
    TIPOSTATUS_RESPONDIDO,
    TIPOSTATUS_AGENDADO
);
?>

<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="container-fluid">

        <!-- Modal -->
        <div class="modal" id="modalDemandaVizualizar" tabindex="-1" aria-hidden="true" style="margin: 5px;">
            <div class="col-12 col-md-3 float-end ts-divLateralModalDemanda">
                <div class="col ">
                    <form id="my-form" action="../database/demanda.php?operacao=alterar" method="post">
                        <div class="modal-header p-2 pe-3 border-start">
                            <div class="col-md-6 d-flex pt-1">
                                <label class='form-label ts-label'>Prioridade</label>
                                <input type="number" min="1" max="99" class="form-control ts-inputSemBorda" name="prioridade" value="<?php echo $demanda['prioridade'] ?>">
                            </div>
                            <div class="col-md-2 border-start d-flex me-2">
                                <a href="../demandas/" role="button" class="btn-close"></a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Responsável</label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-select ts-input ts-selectDemandaModalVisualizar" name="idAtendente" disabled>
                                    <option value="<?php echo $demanda['idAtendente'] ?>"><?php echo $demanda['nomeAtendente'] ?></option>
                                    <?php foreach ($atendentes as $atendente) { ?>
                                        <option value="<?php echo $atendente['idUsuario'] ?>"><?php echo $atendente['nomeUsuario'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Data de Abertura</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control ts-inputSemBorda" name="dataabertura" value="<?php echo date('d/m/Y H:i', strtotime($demanda['dataAbertura'])) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Inicio Previsto</label>
                            </div>
                            <div class="col-md-7">
                                <input type="date" class="form-control ts-inputSemBorda" name="dataPrevisaoInicio" value="<?php echo $demanda['dataPrevisaoInicio'] ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Inicio</label>
                            </div>
                            <div class="col-md-7">
                                <input type="date" class="form-control ts-inputSemBorda" value="<?php echo $demanda['dataInicio'] ?>" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Entrega Prevista</label>
                            </div>
                            <div class="col-md-7">
                                <input type="date" class="form-control ts-inputSemBorda" name="dataPrevisaoEntrega" value="<?php echo $demanda['dataPrevisaoEntrega'] ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Entrega</label>
                            </div>
                            <div class="col-md-7">
                                <input type="datetime" class="form-control ts-inputSemBorda" name="dataFechamento" value="<?php echo $dataFechamento ?>" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Previsão</label>
                            </div>
                            <div class="col-md-7">
                                <input type="time" class="form-control ts-inputSemBorda" name="horasPrevisao" value="<?php echo $demanda['horasPrevisao'] ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Realizado</label>
                            </div>
                            <div class="col-md-7">
                                <input type="time" class="form-control ts-inputSemBorda" name="realizado" value="<?php echo $totalHorasReal ?>" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5 ps-3">
                                <label class="form-label ts-label">Cobrado</label>
                            </div>
                            <div class="col-md-7">
                                <input type="time" class="form-control ts-inputSemBorda" name="tempoCobrado" value="<?php echo $demanda['tempoCobrado'] ?>">
                            </div>
                        </div>


                        <div class="modal-footer">
                            <?php
                            if ($demanda['idTipoStatus'] == TIPOSTATUS_REALIZADO) { ?>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#encerrarModal" class="btn btn-sm btn-danger">Encerrar</button>
                            <?php }
                            if ($demanda['idTipoStatus'] !== TIPOSTATUS_AGUARDANDOSOLICITANTE) { ?>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#devolverModal" class="btn btn-sm btn-primary">Devolver</button>
                            <?php }
                            if ($demanda['idTipoStatus'] == TIPOSTATUS_REALIZADO || $demanda['idTipoStatus'] == TIPOSTATUS_VALIDADO) { ?>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#reabrirModal" class="btn btn-sm btn-warning">Reabrir</button>
                            <?php } ?>

                            <?php if (in_array($demanda['idTipoStatus'], $statusEncerrar)) { ?>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#entregarModal" class="btn btn-sm btn-warning">Entregar</button>
                            <?php } ?>

                        </div>

                        <div class="modal-footer">
                            <div class="col align-self-start pl-0">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#encaminharModal" class="btn btn-warning">Encaminhar</button>
                            </div>
                            <button type="submit" form="my-form" class="btn btn-success">Atualizar</button>
                        </div>
                        <?php
                        if ($usuario['idCliente'] == null) { ?>
                        <div class="modal-footer">
                            <div class="col align-self-start pl-0">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#subdemandaModal" class="btn btn-info">Criar Subdemanda</button>
                            </div>
                        </div>
                        <?php } ?>
                </div>
            </div>

            <div class="modal-dialog modal-dialog-scrollable modal-fullscreen"> <!-- Modal 1 -->
                <div class="modal-content" style="background-color: #F1F2F4;">
                
                    <div class="container">
                        <div class="row pb-1">
                            <?php if (isset($demanda['tituloContrato'])) { ?>
                            <!-- gabriel 05022024 id738 - adicionado select para alterar contrato -->
                            <div class="col-md-9 d-flex">
                                <span class="ts-subTitulo"><strong><?php echo $demanda['nomeContrato'] ?>: </strong></span>
                                <select class="form-select ts-input ts-selectDemandaModalVisualizar" name="idContrato" id="idContrato" autocomplete="off">
                                <option value="<?php echo $demanda['idContrato'] ?>"><?php echo $demanda['tituloContrato'] ?> </option>
                                <?php foreach ($contratos as $contrato) { ?>
                                    <option value="<?php echo $contrato['idContrato'] ?>"><?php echo $contrato['tituloContrato'] ?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <?php } ?>
                            <?php if ($demanda['idDemandaSuperior'] !== null) { ?>
                            <div class="col-md-3 d-flex">
                                <span class="ts-subTitulo"><strong>Demanda Superior: </strong> <?php echo $demanda['idDemandaSuperior'] ?></span>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-9 d-flex">
                                <span class="ts-tituloPrincipalModal"><?php echo $demanda['idDemanda'] ?></span>
                                <input type="hidden" class="form-control ts-inputSemBorda" name="idDemanda" value="<?php echo $demanda['idDemanda'] ?>">
                                <input type="text" class="form-control ts-inputSemBorda ts-tituloPrincipalModal" name="tituloDemanda" value="<?php echo $demanda['tituloDemanda'] ?>" style="z-index: 1;">
                            </div>
                            <div class="col-md-3 d-flex">
                                <span class="ts-subTitulo"><strong>Status: </strong> <?php echo $demanda['nomeTipoStatus'] ?></span>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="hidden" class="form-control ts-input" name="idCliente" value="<?php echo $demanda['idCliente'] ?>">
                                <span class="ts-subTitulo"><strong>Cliente : </strong><span><?php echo $demanda['nomeCliente'] ?></span>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" class="form-control ts-input" name="idSolicitante" id="idSolicitante" value="<?php echo $demanda['idSolicitante'] ?>" readonly>
                                <span class="ts-subTitulo"><strong>Solicitante : </strong> <?php echo $demanda['nomeSolicitante'] ?></span>
                            </div>

                            <div class="col-md-5 d-flex">
                                <span class="ts-subTitulo"><strong>Serviço: </strong></span>
                                <select class="form-select ts-input ts-selectDemandaModalVisualizar" name="idServico" id="idServico" autocomplete="off">
                                <?php foreach ($servicos as $servico) { ?>
                                    <option <?php if ($servico['idServico'] == $demanda['idServico']) { echo "selected"; } ?>
                                        value="<?php echo $servico['idServico'] ?>"><?php echo $servico['nomeServico'] ?>
                                    </option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    </form>

                    <!-- <hr style="border-top: 2px solid #000000;"> -->
                    <div class="row mt-1">
                        <div id="ts-tabs">
                            <div class="tab whiteborder" id="tab-demanda">Demanda</div>
                            <div class="tab" id="tab-tarefas">Tarefas</div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="modal-body">

                        <div id="ts-tabs">
                            <div class="tabContent" style="margin-top: -10px;">
                                <?php include_once 'demanda_descricao.php'; ?>
                            </div>
                            <div class="tabContent p-0" style="margin-top: -10px;">
                                <?php include_once 'visualizar_tarefa.php'; ?>
                            </div>

                        </div>

                    </div>
                </div>
            </div><!-- Modal 1 -->


        </div>
        <!--------- INSERIR/NOVA --------->
        <?php include_once 'modalTarefa_inserirAgendar.php' ?>

        <!--------- MODAL STOP --------->
        <?php include_once 'modalTarefa_stop.php' ?>

        <!--------- MODAL ENCERRAR --------->
        <?php include_once 'modalstatus_encerrar.php' ?>

        <!--------- MODAL REABRIR --------->
        <?php include_once 'modalstatus_reabrir.php' ?>

        <!--------- MODAL ENCAMINHAR --------->
        <?php include_once 'modalstatus_encaminhar.php' ?>

        <!--------- MODAL SUBDEMANDA --------->
        <?php include_once 'modalDemanda_subdemanda.php' ?>

        <!--------- MODAL ENTREGAR --------->
        <?php include_once 'modalstatus_entregar.php' ?>

        <!--------- MODAL DEVOLVER --------->
        <?php include_once 'modalstatus_devolver.php' ?>

        <!--Gabriel 11102023 ID 596 modal Alterar tarefa via include -->
        <!--Lucas 18102023 ID 602 alterado nome do arquivo para modalTarefa_alterar -->
        <?php include 'modalTarefa_alterar.php'; ?>
    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script src="visualizar.js"></script>

    <script>
        var myModal = new bootstrap.Modal(document.getElementById("modalDemandaVizualizar"), {});
        document.onreadystatechange = function() {
            myModal.show();
        };

        var tab;
        var tabContent;

        window.onload = function() {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'tarefas') {
                showTabsContent(1);
            }
        }

        document.getElementById('ts-tabs').onclick = function(event) {
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
    </script>

    <script>
        //Lucas 10112023 ID 965 Removido script  do editor - encerrado, reabrir, encaminhar e stop        

        //Gabriel 11102023 ID 596 script para tratar o envio e retorno do form alterar tarefa
        $(document).ready(function() {
            $("#alterarForm").submit(function(event) {
                //alert('passou aqui')
                event.preventDefault();
                var formData = new FormData(this);
                var vurl;
                if ($("#stopButtonModal").is(":focus")) {
                    vurl = "../database/tarefas.php?operacao=stop";
                }
                if ($("#startButtonModal").is(":focus")) {
                    vurl = "../database/tarefas.php?operacao=start";
                }
                if ($("#realizadoButtonModal").is(":focus")) {
                    vurl = "../database/tarefas.php?operacao=realizado&acao=realizado";
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
                    success: refreshPage('tarefas', <?php echo $idDemanda ?>)

                });
            });
        });

        function refreshPage(tab, idDemanda) {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=' + tab + '&&idDemanda=' + idDemanda;
            window.location.href = newUrl;
        }

    </script>

</body>

</html>