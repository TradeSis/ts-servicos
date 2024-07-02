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
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);
$cliente = buscaClientes($demanda["idCliente"]);
$clientes = buscaClientes();
$contratos = buscaContratosAbertos($demanda["idCliente"]);

//Lucas 22112023 id 688 - Removido visão do cliente ($ClienteSession)

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
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<style>
    .modal-fullscreen {
        padding: 10px;
    }

    .modal-xl {
        /* max-width:1200px; */
        max-width: 75vw;
        height: 98vh;
        margin-top: 0px;
        margin-bottom: 0px;
        margin-left: 0px;
    }

    @media only screen and (max-width: 785px) {
        .modal-xl {
            /* max-width:1200px; */
            max-width: 100vw;
            height: 98vh;
        }

        .divLateral {
            padding-left: 20px;

        }

    }

    @media only screen and (max-width: 600px) {

        .divLateral {
            margin-bottom: 75px;
            position: absolute;
        }

        .divLateral .col {
            height: 750px;
        }

    }

    .divLateral {
        position: sticky;
        display: flex;
        height: 95.3vh;
        margin-top: 10px;
        margin-right: 10px;
        width: 25vw;
    }

</style>

<body>
    <div class="container-fluid">

        <!-- Modal -->
        <div class="modal" id="modalDemandaVizualizar" tabindex="-1" aria-labelledby="modalDemandaVizualizarLabel" aria-hidden="true">
            <div class="col-12 col-md-3 bg-white float-end divLateral">
                <div class="col border-start">
                    <div class="modal-header">

                        <a href="../demandas/" role="button" class="btn-close"></a>

                    </div>
                    <div class="container">
                        <form id="my-form" action="../database/demanda.php?operacao=alterar" method="post">
                            <div class="row mt-3">
                                <div class="col-md-5 ps-2">
                                    <label class="form-label ts-label">Responsável</label>
                                </div>
                                <div class="col-md-7">
                                    <select class="form-select ts-input" name="idAtendente">
                                        <option value="<?php echo $demanda['idAtendente'] ?>"><?php echo $demanda['nomeAtendente'] ?></option>
                                        <?php foreach ($atendentes as $atendente) { ?>
                                            <option value="<?php echo $atendente['idUsuario'] ?>"><?php echo $atendente['nomeUsuario'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-5 ps-2">
                                    <label class="form-label ts-label">Data de Abertura</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="form-control ts-input" name="dataabertura" value="<?php echo date('d/m/Y H:i', strtotime($demanda['dataAbertura'])) ?>" readonly>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-5 ps-2">
                                    <label class="form-label ts-label">Inicio Previsto</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="date" class="form-control ts-input" name="dataPrevisaoInicio" value="<?php echo $demanda['dataPrevisaoInicio'] ?>">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-5 ps-2">
                                    <label class="form-label ts-label">Inicio</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="date" class="form-control ts-input" name="dataAbertura" value="<?php echo $demanda['dataAbertura'] ?>" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-5 ps-2">
                                    <label class="form-label ts-label">Entrega Prevista</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="date" class="form-control ts-input" name="dataPrevisaoEntrega" value="<?php echo $demanda['dataPrevisaoEntrega'] ?>">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-5 ps-2">
                                    <label class="form-label ts-label">Entrega</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="date" class="form-control ts-input" name="dataFechamento" value="<?php echo $demanda['dataFechamento'] ?>" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-1">
                                <div class="col-sm-4 col-md">
                                    <label class='form-label ts-label mb-1'>Previsão</label>
                                    <input type="time" class="form-control ts-input" name="horasPrevisao" value="<?php echo $demanda['horasPrevisao'] ?>">
                                </div>
                                <div class="col-sm-4 col-md">
                                    <label class='form-label ts-label mb-1'>Realizado</label>
                                    <input type="time" class="form-control ts-input" name="realizado">
                                </div>
                                <div class="col-sm-4 col-md">
                                    <label class='form-label ts-label mb-1'>Cobrado</label>
                                    <input type="time" class="form-control ts-input" name="tempoCobrado" value="<?php echo $demanda['tempoCobrado'] ?>">
                                </div>
                            </div>


                    </div><!-- container -->
                    <div class="modal-footer" style="margin-top: 60px;">
                        <?php
                        if ($demanda['idTipoStatus'] == TIPOSTATUS_REALIZADO) { ?>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#encerrarModal" class="btn btn-danger">Encerrar</button>
                        <?php }
                        if ($demanda['idTipoStatus'] == TIPOSTATUS_REALIZADO || $demanda['idTipoStatus'] == TIPOSTATUS_VALIDADO) { ?>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#reabrirModal" class="btn btn-warning">Reabrir</button>
                        <?php } ?>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#encaminharModal" class="btn btn-warning">Encaminhar</button>

                        <?php if (in_array($demanda['idTipoStatus'], $statusEncerrar)) { ?>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#entregarModal" class="btn btn-warning">Entregar</button>
                        <?php } ?>

                </div>
            </div>

            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen">
                <div class="modal-content">
                    <div class="container">
                        <div class="row g-3 mt-1">
                            <div class="col-md-2 d-flex">
                                <label class='form-label ts-label'>Prioridade</label>
                                <input type="number" min="1" max="99" class="form-control ts-input" name="prioridade" value="<?php echo $demanda['prioridade'] ?>">
                            </div>
                            <div class="col-md-7 d-flex">
                                <label class='form-label ts-label'>Demanda: </label>
                                <input type="text" class="form-control ts-input" name="idDemanda" value="<?php echo $demanda['idDemanda'] ?>" readonly style="width: 50px;" />
                                <input type="text" class="form-control ts-input" name="tituloDemanda" value="<?php echo $demanda['tituloDemanda'] ?>">
                            </div>
                            <div class="col-md-3 d-flex">
                                <label class="form-label ts-label">Status</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $demanda['nomeTipoStatus'] ?>" readonly>
                            </div>
                        </div>
                        <div class="row g-3 mt-1 pt-0 ">
                            <div class="col-md-3 d-flex">
                                <label class="form-label ts-label">Cliente</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $demanda['nomeCliente'] ?>" readonly>
                            </div>
                            <div class="col-md-4 d-flex">
                                <label class="form-label ts-label">Solicitante</label>
                                <input type="text" class="form-control ts-input" id="idSolicitante" value="<?php echo $demanda['nomeSolicitante'] ?>" readonly>
                            </div>
                            <div class="col-md-5 d-flex">
                                <label class="form-label ts-label">Serviço</label>
                                <select class="form-select ts-input" name="idServico" id="idServico" autocomplete="off">
                                    <option value="<?php echo $demanda['idServico'] ?>"><?php echo $demanda['nomeServico'] ?>
                                        <?php foreach ($servicos as $servico) { ?>
                                    <option value="<?php echo $servico['idServico'] ?>"><?php echo $servico['nomeServico'] ?>
                                    </option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row mt-2">
                        <div id="ts-tabs">
                            <div class="tab whiteborder" id="tab-demanda">Demanda</div>
                            <div class="tab" id="tab-tarefas">Tarefas</div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="modal-body">

                        <div id="ts-tabs">
                            <div class="tabContent">
                                <?php include_once 'demanda_descricao.php'; ?>
                            </div>
                            <div class="tabContent">
                                <?php include_once 'visualizar_tarefa.php'; ?>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="my-form"  class="btn btn-success btn-demanda">Atualizar</button>
                    </div>
                    </form>
                </div>
            </div>
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

        <!--------- MODAL ENTREGAR --------->
        <?php include_once 'modalstatus_entregar.php' ?>

        <!--Gabriel 11102023 ID 596 modal Alterar tarefa via include -->
        <!--Lucas 18102023 ID 602 alterado nome do arquivo para modalTarefa_alterar -->
        <?php include 'modalTarefa_alterar.php'; ?>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <!-- QUILL editor -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        /* $(document).ready(function() {
            $("#modalDemandaInserir").submit(function(event) {
                alert('passou aqui')
                event.preventDefault();
                var formData = new FormData(this);
                var vurl;
                if ($("#btn_atualizarDemanda").is(":focus")) {
                    vurl = "../database/demanda.php?operacao=alterar";
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
        }); */

 /*        $('#btn_atualizarDemanda').click(function() {
            //alert('passou aqui')
            
                //alert('oi')
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/demanda.php?operacao=alterar",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            
            }); 

            $('#btn_atualizarDemanda').focusout(function () {
     var dados = $(this).closest('form_demandaAtualizar').serialize();
     alert(dados)
     $.ajax({
         url: CI_ROOT + "backend/home/salva_preco",
         data: {
             dados: dados
         },
         dataType: "json",
         type: "POST",
         success: function (data) {

         }
     });
 }); */
 /* $(document).on('click', '.btn-demanda', function() {
    alert('entrou aqui')
        //window.location.href='visualizar.php?idDemanda=' + $(this).attr('data-idDemanda');
   
        //Envio form modalDemandaInserir
         $("#form_demandaAtualizar").on(function(event) {
            alert('form')
            event.preventDefault();
            var formData = new FormData(this);
            //alert (formDate)
            $.ajax({
                url: "../database/demanda.php?operacao=alterar",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: refreshPage,
            });
        }); 
    }); */
        function refreshPage() {
            window.location.reload();
        }

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
            if (id === 'comentarios') {
                showTabsContent(1);
            }
            if (id === 'tarefas') {
                showTabsContent(2);
            }
            //Gabriel 26092023 ID 575 adicionado tab mensagens
            if (id === 'mensagem') {
                showTabsContent(3);
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

        var quilldescricao = new Quill('.quill-textarea', {
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

        quilldescricao.on('text-change', function(delta, oldDelta, source) {
            $('#quill-descricao').val(quilldescricao.container.firstChild.innerHTML);
        });
    </script>
</body>

</html>