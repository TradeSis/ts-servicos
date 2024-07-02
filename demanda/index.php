<?php
// lucas 31102023 reformulado programa 
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
$ClienteSession = null;
if (isset($_SESSION['idCliente'])) {
    $ClienteSession = $_SESSION['idCliente'];
}

$usuario = buscaUsuarios(null, $_SESSION['idLogin']);
$clientes = buscaClientes();
$atendentes = buscaAtendente();
//echo json_encode($atendentes);
$usuarios = buscaUsuarios();
$tiposstatus = buscaTipoStatus();
$tipoocorrencias = buscaTipoOcorrencia();
$cards = buscaCardsDemanda();
$contratos = buscaContratosAbertos();
$servicos = buscaServicos();

if ($_SESSION['idCliente'] == null) {
    $idCliente = null;
} else {
    $idCliente = $_SESSION['idCliente'];
}

if ($_SESSION['idCliente'] == null) {
    $idAtendente = $_SESSION['idUsuario'];
} else {
    $idAtendente = null;
}
$statusDemanda = "1"; //ABERTO

$filtroEntrada = null;
$idTipoStatus = null;
$idTipoOcorrencia = null;
$idSolicitante = null;


if (isset($_SESSION['filtro_demanda'])) {
    $filtroEntrada = $_SESSION['filtro_demanda'];
    $idCliente = $filtroEntrada['idCliente'];
    $idSolicitante = $filtroEntrada['idSolicitante'];
    $idAtendente = $filtroEntrada['idAtendente'];
    $idTipoStatus = $filtroEntrada['idTipoStatus'];
    $idTipoOcorrencia = $filtroEntrada['idTipoOcorrencia'];
    $statusDemanda = $filtroEntrada['statusDemanda'];
    $posicao = $filtroEntrada['posicao'];
}
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

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>Prioridade</th>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Solicitante</th>
                        <th>Titulo</th>
                        <th>Responsavel</th>
                        <th>Abertura</th>
                        <th>Status</th>
                        <th>Ocorrência</th>
                        <th>Data Entrega</th>
                        <th>Posição</th>
                        <th colspan="2">Ação</th>
                    </tr>
                    <tr class="ts-headerTabelaLinhaBaixo">
                        <th></th>
                        <th></th>
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
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idTipoOcorrencia" id="FiltroOcorrencia">
                                    <option value="<?php echo null ?>">
                                        <?php echo "Selecione" ?>
                                    </option>
                                    <?php
                                    foreach ($tipoocorrencias as $tipoocorrencia) {
                                    ?>
                                        <option <?php
                                                if ($tipoocorrencia['idTipoOcorrencia'] == $idTipoOcorrencia) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $tipoocorrencia['idTipoOcorrencia'] ?>">
                                            <?php echo $tipoocorrencia['nomeTipoOcorrencia'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </form>
                        </th>
                        <th></th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="posicao" id="FiltroPosicao">
                                    <option value="<?php echo null ?>"><?php echo "Selecione" ?></option>
                                    <option value="0">Atendente</option>
                                    <option value="1">Cliente</option>
                                </select>
                            </form>
                        </th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>

        <!--------- MODAL DEMANDA INSERIR --------->
        <div class="modal" id="novoinserirDemandaModal" tabindex="-1" aria-labelledby="novoinserirDemandaModalLabel" aria-hidden="true">
            <!-- Gabriel 13102023 fix modal nova demanda, ajustado para modal-lg  -->
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Inserir
                            <?php echo $contratoTipo['nomeDemanda'] ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="../database/demanda.php?operacao=inserir"> <!-- id="modalDemandaInserir" -->
                            <div class="row mt-1">
                                <div class="col-sm-8 col-md">
                                    <label class='form-label ts-label'><?php echo $contratoTipo['nomeDemanda'] ?></label>
                                    <input type="text" class="form-control ts-input" name="tituloDemanda" autocomplete="off" required>
                                    <input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $contrato['idContrato'] ?>" readonly>
                                    <input type="hidden" class="form-control ts-input" name="idContratoTipo" value="<?php echo $contratoTipo['idContratoTipo'] ?>" readonly>
                                </div>

                                <div class="col-sm-4 col-md">
                                    <label class="form-label ts-label">Cliente</label>
                                    <input type="hidden" class="form-control ts-input" name="idSolicitante" value="<?php echo $usuario['idUsuario'] ?>" readonly>

                                    <?php if (isset($contrato)) { ?>
                                        <input type="text" class="form-control ts-input" value="<?php echo $contrato['nomeCliente'] ?>" readonly>
                                        <input type="hidden" class="form-control ts-input" name="idCliente" value="<?php echo $contrato['idCliente'] ?>" readonly>
                                    <?php } else { ?>
                                        <select class="form-select ts-input" name="idCliente" autocomplete="off" <?php if (isset($contrato)) {
                                                                                                                        echo " disabled ";
                                                                                                                    } ?>required>
                                            <option value="">
                                                <?php if (isset($contrato)) {
                                                    echo $contrato['nomeCliente'];
                                                } else {
                                                    echo "Selecione";
                                                } ?>
                                            </option>
                                            <?php
                                            foreach ($clientes as $cliente) {
                                            ?>
                                                <option value="<?php echo $cliente['idCliente'] ?>">
                                                    <?php echo $cliente['nomeCliente'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="container-fluid p-0">
                                        <div class="col">
                                            <span class="tituloEditor">Descrição</span>
                                        </div>
                                        <div class="quill-demandainserir" style="height:20vh !important"></div>
                                        <textarea style="display: none" id="quill-demandainserir" name="descricao"></textarea>
                                    </div>
                                </div><!--col-md-6-->

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <label class="form-label ts-label">Previsão</label>
                                            <input type="number" class="form-control ts-input" name="horasPrevisao" value="<?php echo $demanda['horasPrevisao'] ?>">
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <label class="form-label ts-label">Ocorrência</label>
                                            <select class="form-select ts-input" name="idTipoOcorrencia" autocomplete="off">
                                                <option value="<?php echo null ?>">
                                                    <?php echo "Selecione" ?>
                                                </option>
                                                <?php
                                                foreach ($tipoocorrencias as $tipoocorrencia) {
                                                ?>
                                                    <option <?php
                                                            if ($tipoocorrencia['ocorrenciaInicial'] == 1) {
                                                                echo "selected";
                                                            }
                                                            ?> value="<?php echo $tipoocorrencia['idTipoOcorrencia'] ?>">
                                                        <?php echo $tipoocorrencia['nomeTipoOcorrencia'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div><!--fim row 1-->

                                    <div class="row mt-3">
                                        <div class="col-sm-6 col-md-6">
                                            <label class="form-label ts-label">Tamanho</label>
                                            <select class="form-select ts-input" name="tamanho">
                                                <option value="<?php echo null ?>">
                                                    <?php echo "Selecione" ?>
                                                </option>
                                                <option value="P">P</option>
                                                <option value="M">M</option>
                                                <option value="G">G</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <label class="form-label ts-label">Serviço</label>
                                            <select class="form-select ts-input" name="idServico" autocomplete="off">
                                                <option value="<?php echo null ?>">
                                                    <?php echo "Selecione" ?>
                                                </option>
                                                <?php foreach ($servicos as $servico) { ?>
                                                    <option value="<?php echo $servico['idServico'] ?>">
                                                        <?php echo $servico['nomeServico'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div><!--fim row 2-->

                                    <div class="row mt-3">
                                        <div class="col-sm-6 col-md-6">
                                            <label class="form-label ts-label">Responsável</label>
                                            <select class="form-select ts-input" name="idAtendente">
                                                <option value="<?php echo null ?>">
                                                    <?php echo "Selecione" ?>
                                                </option>
                                                <?php foreach ($atendentes as $atendente) { ?>
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

                                        <div class="col-sm-6 col-md-6">
                                            <label class="form-label ts-label">Contrato Vinculado</label>
                                            <?php
                                            if (isset($contrato)) { ?>
                                                <select class="form-select ts-input" name="idContrato" autocomplete="off" disabled>
                                                    <option value="<?php echo $contrato['idContrato'] ?>"><?php echo $contrato['tituloContrato'] ?></option>
                                                </select>
                                            <?php } else { ?>
                                                <?php if ($contratoTipo['idContratoTipo'] == 'os') { ?>
                                                    <select class="form-select ts-input" name="idContrato" autocomplete="off" required>
                                                    <?php } else { ?>
                                                        <select class="form-select ts-input" name="idContrato" autocomplete="off">
                                                        <?php } ?>
                                                        <option value="<?php echo null ?>">
                                                            <?php echo "Selecione" ?>
                                                        </option>
                                                        <?php foreach ($contratos as $contrato) {  ?>
                                                            <option data-idcliente="<?php echo $contrato['idCliente'] ?>" value="<?php echo $contrato['idContrato'] ?>">
                                                                <?php echo $contrato['tituloContrato'] ?>
                                                            </option>
                                                        <?php } ?>
                                                        </select>
                                                    <?php  } ?>

                                        </div>
                                    </div><!--fim row 3-->

                                </div><!--col-md-6-->


                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!--------- ALTERAR --------->
        <div class="modal fade bd-example-modal-lg" id="alterarmodal" tabindex="-1" aria-labelledby="alterarmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Alterar Nota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="alterarFormAplicativo">
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label class='form-label ts-label'>Nome do Aplicativo</label>
                                    <input type="text" class="form-control ts-input" name="nomeAplicativo" id="nomeAplicativo">
                                    <input type="hidden" class="form-control ts-input" name="idAplicativo" id="idAplicativo">
                                </div>
                                <div class="col-md-6">
                                    <label class='form-label ts-label'>Caminho</label>
                                    <input type="text" class="form-control ts-input" name="appLink" id="appLink">
                                </div>

                            </div>
                            <div class="row mt-3">
                                <label class="form-label ts-label">Imagem</label>
                                <label class="picture ml-4 mt-4" for="imgAplicativo" tabIndex="0">
                                    <span class="picture__image"></span>
                                </label>

                                <input type="file" name="imgAplicativo" id="imgAplicativo">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <!-- script para menu de filtros -->
    <script src="<?php echo URLROOT ?>/sistema/js/filtroTabela.js"></script>
    <!-- QUILL editor -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        var urlContratoTipo = '<?php echo $urlContratoTipo ?>';

        buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());

        function limparTrade() {
            buscar(null, null, null, null, null, null, null, null, function() {
                window.location.reload();
            });
        }

        function clickCard(statusDemanda) {
        buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(),
          statusDemanda, $("#buscaDemanda").val(), $("#FiltroPosicao").val())
      }
      
        function buscar(idCliente, idSolicitante, idAtendente, idTipoStatus, idTipoOcorrencia, statusDemanda, buscaDemanda, posicao, callback) {
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
                    idTipoOcorrencia: idTipoOcorrencia,
                    statusDemanda: statusDemanda,
                    buscaDemanda: buscaDemanda,
                    urlContratoTipo: urlContratoTipo,
                    posicao: posicao
                },
                success: function(msg) {
                    var json = JSON.parse(msg);
                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        var dataAbertura = new Date(object.dataAbertura);
                        var dataFormatada = dataAbertura.toLocaleDateString("pt-BR");

                        if (object.dataFechamento == null) {
                            var dataFechamentoFormatada = "<p>---</p>";
                        } else {
                            var dataFechamento = new Date(object.dataFechamento);
                            dataFechamentoFormatada = dataFechamento.toLocaleDateString("pt-BR") + "<br> " + dataFechamento.toLocaleTimeString("pt-BR");
                        }

                        if (object.posicao == 0) {
                            var posicao = "Atendente"
                        }
                        if (object.posicao == 1) {
                            var posicao = "Cliente"
                        }

                        linha += "<tr>";
                        linha += "<td>" + object.prioridade + "</td>";
                        linha += "<td>" + object.idDemanda + "</td>";
                        linha += "<td>" + object.nomeCliente + "</td>";
                        linha += "<td>" + object.nomeSolicitante + "</td>";
                        linha += "<td>" + object.tituloDemanda + "</td>";
                        linha += "<td>" + object.nomeAtendente + "</td>";
                        linha += "<td>" + dataFormatada + "</td>";
                        linha += "<td class='" + object.idTipoStatus + "'>" + object.nomeTipoStatus + "</td>";
                        linha += "<td>" + object.nomeTipoOcorrencia + "</td>";
                        linha += "<td>" + dataFechamentoFormatada + "</td>";
                        linha += "<td>" + posicao + "</td>";
                        linha += "<td><a class='btn btn-warning btn-sm' href='visualizar.php?idDemanda=" + object.idDemanda + "' role='button'><i class='bi bi-pencil-square'></i></a></td>";

                        linha += "</tr>";
                    }

                    $("#dados").html(linha);

                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            });
        }

        $("#FiltroTipoStatus").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        $("#FiltroClientes").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        $("#FiltroSolicitante").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        $("#FiltroOcorrencia").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        $("#FiltroUsuario").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        $("#FiltroStatusDemanda").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        $("#buscar").click(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        $("#FiltroPosicao").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
        });

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#FiltroClientes").val(), $("#FiltroSolicitante").val(), $("#FiltroUsuario").val(), $("#FiltroTipoStatus").val(), $("#FiltroOcorrencia").val(), $("#FiltroStatusDemanda").val(), $("#buscaDemanda").val(), $("#FiltroPosicao").val());
            }
        });

        // Cards com Botões acionamento individual
        $('.ts-cardColor').click(function() {
            $('.ts-cardColor').addClass('ts-cardColor-active');
            $('.ts-cardColor').removeClass('ts-shadowOff');
            $('.ts-cardColor1').removeClass('ts-cardColor-active');
            $('.ts-cardColor2').removeClass('ts-cardColor-active');
            $('.ts-cardColor3').removeClass('ts-cardColor-active');
            $('.ts-cardColor0').removeClass('ts-cardColor-active');
        });
        $('.ts-cardColor1').click(function() {
            $('.ts-cardColor1').addClass('ts-cardColor-active');
            $('.ts-cardColor1').removeClass('ts-shadowOff');
            $('.ts-cardColor').removeClass('ts-cardColor-active');
            $('.ts-cardColor2').removeClass('ts-cardColor-active');
            $('.ts-cardColor3').removeClass('ts-cardColor-active');
            $('.ts-cardColor0').removeClass('ts-cardColor-active');
        });
        $('.ts-cardColor2').click(function() {
            $('.ts-cardColor2').addClass('ts-cardColor-active');
            $('.ts-cardColor2').removeClass('ts-shadowOff');
            $('.ts-cardColor').removeClass('ts-cardColor-active');
            $('.ts-cardColor1').removeClass('ts-cardColor-active');
            $('.ts-cardColor3').removeClass('ts-cardColor-active');
            $('.ts-cardColor0').removeClass('ts-cardColor-active');
        });
        $('.ts-cardColor3').click(function() {
            $('.ts-cardColor3').addClass('ts-cardColor-active');
            $('.ts-cardColor3').removeClass('ts-shadowOff');
            $('.ts-cardColor').removeClass('ts-cardColor-active');
            $('.ts-cardColor1').removeClass('ts-cardColor-active');
            $('.ts-cardColor2').removeClass('ts-cardColor-active');
            $('.ts-cardColor0').removeClass('ts-cardColor-active');
        });
        $('.ts-cardColor0').click(function() {
            $('.ts-cardColor0').addClass('ts-cardColor-active');
            $('.ts-cardColor0').removeClass('ts-shadowOff');
            $('.ts-cardColor').removeClass('ts-cardColor-active');
            $('.ts-cardColor1').removeClass('ts-cardColor-active');
            $('.ts-cardColor2').removeClass('ts-cardColor-active');
            $('.ts-cardColor3').removeClass('ts-cardColor-active');
        });

        // Cards com Botões acionamento ligado ao Select de StatusDemanda
        let btn = document.querySelectorAll('button');
        /*   let select = document.querySelector('select'); */
        let select = document.getElementById('FiltroStatusDemanda')

        function troca(e) {
            select.value = e.currentTarget.id;
        }

        btn.forEach((el) => {
            el.addEventListener('click', troca);
        })

        function mudarSelect(valor) {
            $('.ts-cardColor').removeClass('ts-cardColor-active');
            $('.ts-cardColor1').removeClass('ts-cardColor-active');
            $('.ts-cardColor2').removeClass('ts-cardColor-active');
            $('.ts-cardColor3').removeClass('ts-cardColor-active');
            $('.ts-cardColor0').removeClass('ts-cardColor-active');
            $('.ts-cardColor' + valor).addClass('ts-cardColor-active');
            $('.ts-cardColor' + valor).removeClass('ts-shadowOff');

        }

        //selects em paralelo
        var contratos = $('select[name="idContrato"] option');
        $('select[name="idCliente"]').on('change', function() {
            var idCliente = this.value;
            if (idCliente != "") {
                var novoSelect = contratos.filter(function() {
                    return $(this).data('idcliente') == idCliente;
                });
                $('select[name="idContrato"]').html(novoSelect);
            } else {
                $('select[name="idContrato"]').html(contratos);
            }

        });
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