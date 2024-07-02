<?php
// Lucas 25102023 id643 revisao geral
// Lucas 13102023 novo padrao
include_once '../header.php';
include_once '../database/contratos.php';
include '../database/contratotipos.php';

$idContrato = $_GET['idContrato'];
$contrato = buscaContratos($idContrato, null);
$contratoTipo = buscaContratoTipos($contrato['idContratoTipo']);


include_once(ROOT . '/cadastros/database/usuario.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once '../database/contratos.php';
include_once(ROOT . '/cadastros/database/servicos.php');
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');

/* $urlContratoTipo = $_GET["tipo"];
$contratoTipo = buscaContratoTipos($urlContratoTipo); */

$ClienteSession = null;
if (isset($_SESSION['idCliente'])) {
    $ClienteSession = $_SESSION['idCliente'];
}

$usuario = buscaUsuarios(null, $_SESSION['idLogin']);
$clientes = buscaClientes();
//$contratos = buscaContratosAbertos();
$servicos = buscaServicos();
$atendentes = buscaAtendente();
// Lucas 25102023 id643 ajustado variavel $tipoocorrencias para ficar igual de demanda
$tipoocorrencias = buscaTipoOcorrencia();
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>
</head>

<body>
    <div class="container-fluid">

        <div class="row">
        <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
        <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row mt-2"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-7">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal"><?php echo $contrato['idContrato'] ?> -
                    <?php echo $contrato['tituloContrato'] ?></h2>
            </div>
            <div class="col-3">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="javascript:history.back()" role="button" class="btn btn-primary">
                    <i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <div id="ts-tabs">
            <div class="tab whiteborder" id="tab-contrato"><?php echo $contratoTipo['nomeContrato'] ?></div>
            <div class="tab" id="tab-demandasontrato"><?php echo $contratoTipo['nomeDemanda'] ?></div>
            <div class="tab" id="tab-notascontrato">Notas</div>

            <div class="line"></div>
            <div class="tabContent">
                <?php include_once 'alterar.php'; ?>
            </div>
            <div class="tabContent">
                <?php include_once 'demandascontrato.php'; ?>
            </div>
            <div class="tabContent">
                <?php include_once 'notascontrato.php'; ?>
            </div>

        </div>
    </div>

    <!-- Lucas 25102023 id643 include de modalDemanda_inserir -->
    <!--------- MODAL DEMANDA INSERIR --------->
    <?php include_once '../demandas/modalDemanda_inserir.php' ?>
    
     <!--------- MODAL INSERIR NOTAS --------->
     <div class="modal" id="inserirModalNotas" tabindex="-1" role="dialog" aria-labelledby="inserirModalNotasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="inserirFormNotaContrato">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label ts-label">Cliente</label>
                                <input type="text" class="form-control ts-input" name="nomeCliente" value="<?php echo $contrato['nomeCliente'] ?>" disabled>
                                <input type="hidden" class="form-control ts-input" name="idCliente" value="<?php echo $contrato['idCliente'] ?>" readonly>
                                <input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $contrato['idContrato'] ?>" readonly>
                            </div>
                            <div class="col-md-3 ">
                                <label class='form-label ts-label'>dataFaturamento</label>
                                <input type="date" class="form-control ts-input" name="dataFaturamento" autocomplete="off" required>
                            </div>
                            <div class="col-md-3 ">
                                <label class='form-label ts-label'>dataEmissao</label>
                                <input type="date" class="form-control ts-input" name="dataEmissao" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 ">
                                <label class='form-label ts-label'>serieNota</label>
                                <input type="text" class="form-control ts-input" name="serieNota" autocomplete="off">
                            </div>
                            <div class="col-md-6 ">
                                <label class='form-label ts-label'>numeroNota</label>
                                <input type="text" class="form-control ts-input" name="numeroNota" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3 ">
                                <label class='form-label ts-label'>serieRPS</label>
                                <input type="text" class="form-control ts-input" name="serieRPS" autocomplete="off">
                            </div>
                            <div class="col-md-3 ">
                                <label class='form-label ts-label'>numeroRPS</label>
                                <input type="text" class="form-control ts-input" name="numeroRPS" autocomplete="off">
                            </div>
                            <div class="col-md-3 ">
                                <label class='form-label ts-label'>valorNota</label>
                                <input type="text" class="form-control ts-input" name="valorNota" autocomplete="off" value="<?php echo $contrato['valorContrato'] ?>" required style="margin-top: -5px;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label ts-label">statusNota</label>
                                <select class="form-select ts-input" name="statusNota" autocomplete="off" required>
                                    <option value="0">Aberto</option>
                                    <option value="1">Emitida</option>
                                    <option value="2">Recebida</option>
                                    <option value="3">Cancelada</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class='form-label ts-label'>condicao</label>
                                <input type="text" class="form-control ts-input" name="condicao" autocomplete="off">
                            </div>
                        </div>
                </div><!--modal body-->
                <div class="modal-footer">
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!--------- MODAL ALTERAR NOTAS --------->
    <div class="modal" id="alterarModalNotas" tabindex="-1" role="dialog" aria-labelledby="alterarModalNotasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="alterarFormNotaContrato">
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <label class="form-label ts-label">idNotaServico</label>
                                <input type="text" class="form-control ts-input" id="idNotaServico" name="idNotaServico" readonly>
                                <input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $contrato['idContrato'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label ts-label">Cliente</label>
                                <input type="text" class="form-control ts-input" name="nomeCliente" id="nomeCliente" disabled>
                                <input type="hidden" class="form-control ts-input" name="idCliente" id="idCliente" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class='form-label ts-label'>dataFaturamento</label>
                                <input type="date" class="form-control ts-input" name="dataFaturamento" id="dataFaturamento" required>
                            </div>
                            <div class="col-md-3">
                                <label class='form-label ts-label'>dataEmissao</label>
                                <input type="date" class="form-control ts-input" name="dataEmissao" id="dataEmissao">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class='form-label ts-label'>serieNota</label>
                                <input type="text" class="form-control ts-input" name="serieNota" id="serieNota">
                            </div>
                            <div class="col-md-6">
                                <label class='form-label ts-label'>numeroNota</label>
                                <input type="text" class="form-control ts-input" name="numeroNota" id="numeroNotabd">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label class='form-label ts-label'>serieRPS</label>
                                <input type="text" class="form-control ts-input" name="serieRPS" id="serieRPS">
                            </div>
                            <div class="col-md-3">
                                <label class='form-label ts-label'>numeroRPS</label>
                                <input type="text" class="form-control ts-input" name="numeroRPS" id="numeroRPS">
                            </div>
                            <div class="col-md-3">
                                <label class='form-label ts-label'>valorNota</label>
                                <input type="text" class="form-control ts-input" name="valorNota" id="valorNota" required>
                            </div>

                            <div class="col-md-3">
                                <label class='form-label ts-label'>statusNota</label>
                                <input type="text" class="form-control ts-input" name="statusNota" id="statusNota" required>
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class='form-label ts-label'>condicao</label>
                                <input type="text" class="form-control ts-input" name="condicao" id="condicao">
                            </div>
                        </div>
                </div><!--modal body-->
                <div class="modal-footer">
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        var tab;
        var tabContent;

        window.onload = function() {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'demandacontrato') {
                showTabsContent(1);
            }
            if (id === 'notascontrato') {
                showTabsContent(2);
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
        var demandaContrato = new Quill('.quill-demandainserir', {
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
                    /*  [{
                       'header': [1, 2, 3, 4, 5, 6, false]
                     }], */
                    ['link', 'image'],
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

        demandaContrato.on('text-change', function(delta, oldDelta, source) {
            $('#quill-demandainserir').val(demandaContrato.container.firstChild.innerHTML);
        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>