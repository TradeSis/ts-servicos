<?php
// Lucas 25102023 id643 revisao geral
// Lucas 13102023 novo padrao
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/orcamento.php');
include_once(__DIR__ . '/../database/orcamentoStatus.php');
include_once(__DIR__ . '/../database/contratotipos.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(ROOT . '/cadastros/database/servicos.php');
$idOrcamento = $_GET['idOrcamento'];
$orcamento = buscaOrcamentos($idOrcamento);
$orcamentoitens = buscaOrcamentoItens($idOrcamento);
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);

//Lucas 22112023 id 688 - Removido visão do cliente ($ClienteSession)

$clientes = buscaClientes();
$contratoTipos = buscaContratoTipos();
$orcamentosStatus = buscaOrcamentoStatus();
$servicos = buscaServicos();

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="ts-fundoVisualizar">
    <div class="container-fluid p-0 m-0">

        <div class="row p-0 m-0">
            <div class="col-12 col-md-9 ">
                <form action="../database/orcamento.php?operacao=alterar" method="post">
                    <div class="container">
                        <div class="row g-3">
                            <div class="col-md-9 d-flex">
                                <span class="ts-tituloPrincipalModal"><?php echo $orcamento['idOrcamento'] ?></span>
                                <input type="hidden" class="form-control ts-inputSemBorda" name="idOrcamento" value="<?php echo $orcamento['idOrcamento'] ?>">
                                <input type="text" class="form-control ts-inputSemBorda ts-tituloPrincipalModal" name="tituloOrcamento" value="<?php echo $orcamento['tituloOrcamento'] ?>" style="z-index: 1;">
                            </div>
                            <div class="col-md-3 d-flex">
                                <span class="ts-subTitulo"><strong>Status: </strong></span>
                                <select class="form-select ts-input ts-selectDemandaModalVisualizar" name="idOrcamentoStatus" id="idOrcamentoStatus" autocomplete="off" <?php if ($_SESSION['administradora'] == 0) echo 'disabled'; ?>>
                                    <?php foreach ($orcamentosStatus as $orcamentoStatus) {
                                    if ($orcamentoStatus['idOrcamentoStatus'] < $orcamento['idOrcamentoStatus']) { continue; } ?>
                                    <option <?php if ($orcamentoStatus['idOrcamentoStatus'] == $orcamento['idOrcamentoStatus']) { echo "selected"; } ?>
                                    value="<?php echo $orcamentoStatus['idOrcamentoStatus'] ?>"><?php echo $orcamentoStatus['nomeOrcamentoStatus'] ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="hidden" class="form-control ts-input" name="idCliente" value="<?php echo $orcamento['idCliente'] ?>">
                                <span class="ts-subTitulo"><strong>Cliente : </strong><span><?php echo $orcamento['nomeCliente'] ?></span>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" class="form-control ts-input" name="idSolicitante" id="idSolicitante" value="<?php echo $orcamento['idSolicitante'] ?>" readonly>
                                <span class="ts-subTitulo"><strong>Solicitante : </strong> <?php echo $orcamento['nomeSolicitante'] ?></span>
                            </div>

                            <div class="col-md-5 d-flex">
                                <?php if ($_SESSION['administradora'] == 1) { ?>
                                    <span class="ts-subTitulo"><strong>Serviço: </strong></span>
                                    <select class="form-select ts-input ts-selectDemandaModalVisualizar" name="idServico" id="idServico" autocomplete="off">
                                            <?php foreach ($servicos as $servico) { ?>
                                        <option <?php if ($servico['idServico'] == $orcamento['idServico']) echo "selected"; ?> value="<?php echo $servico['idServico'] ?>"><?php echo $servico['nomeServico'] ?>
                                        </option>
                                    <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <span class="ts-subTitulo"><strong>Serviço : </strong> <?php echo $orcamento['nomeServico'] ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div id="ts-tabs">
                            <div class="tab aba1 whiteborder" id="tab-orcamento">Orçamento</div>
                            <div class="tab aba2" id="tab-itens">Itens</div>
                        </div>
                        <div id="ts-tabs">
                            <div class="line"></div>
                            <div class="tabContent aba1_conteudo">
                                <div class="container-fluid p-0 ts-containerDescricaoDemanda">
                                    <div class="row">
                                        <div class="col">
                                            <span class="tituloEditor">Descrição</span>
                                        </div>
                                        <div class="col text-end">
                                            <a class="ts-btnDescricaoEditar"><i class="bi bi-pen"></i>&#32;Editar</a>
                                        </div>
                                    </div>
                                    <div id="ql-toolbarOrcamentoDescricao">
                                        <?php include ROOT."/sistema/quilljs/ql-toolbar-min.php"  ?>
                                        <input type="file" id="anexarOrcamentoDescricao" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileOrcamentoDescricao()" style=" display:none">
                                        <label for="anexarOrcamentoDescricao">
                                            <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                                        </label>
                                    </div>
                                    <div id="ql-editorOrcamentoDescricao" class="ts-displayDisable" style="height: auto!important;">
                                        <?php echo $orcamento['descricao'] ?>
                                    </div>
                                    <textarea style="display: none" id="quill-orcamentoDescricao" name="descricao"><?php echo $orcamento['descricao'] ?></textarea>
                                </div>
                            </div>
                            <div class="tabContent aba2_conteudo" style="display: none;">
                                <?php include_once 'orcamentoitens.php'; ?>
                            </div>

                        </div>
                    </div>

            </div>
            <div class="col-12 col-md-3"
                style="height: 100vh;box-shadow: 0px 10px 15px -3px rgba(0,0,0,0.1);">
                <div class="modal-header p-2 pe-3">
                    <div class="col-md-6 d-flex pt-1">
                    </div>
                    <div class="col-md-2 border-start d-flex me-2">
                        <a href="../orcamento/" role="button" class="btn-close"></a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Contrato</label>
                    </div>
                    <div class="col-md-7">
                        <input type="number" class="form-control ts-inputSemBorda" name="idContrato"
                            value="<?php echo $orcamento['idContrato'] ?>" disabled>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Abertura</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control ts-inputSemBorda" name="dataAbertura"
                            value="<?php echo date('d/m/Y H:i', strtotime($orcamento['dataAbertura'])) ?>" disabled>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Aprovação</label>
                    </div>
                    <div class="col-md-7">
                        <input type="date" class="form-control ts-inputSemBorda" name="dataAprovacao"
                            value="<?php echo $orcamento['dataAprovacao'] ?>" disabled>
                    </div>
                </div>
                <?php if ($_SESSION['administradora'] == 1) { ?>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Horas</label>
                    </div>
                    <div class="col-md-7">
                        <input type="number" class="form-control ts-inputSemBorda" name="horas"
                            value="<?php echo $orcamento['horas'] ?>">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Valor Hora</label>
                    </div>
                    <div class="col-md-7">
                        <input type="number" class="form-control ts-inputSemBorda" name="valorHora"
                            value="<?php echo $orcamento['valorHora'] ?>">
                    </div>
                </div>
                <?php } ?>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Valor Orçamento</label>
                    </div>
                    <div class="col-md-7">
                        <input type="number" class="form-control ts-inputSemBorda" name="valorOrcamento"
                            value="<?php echo $orcamento['valorOrcamento'] ?>" <?php if ($_SESSION['administradora'] == 0) echo 'disabled'; ?>>
                    </div>
                </div>

                <hr class="mt-4">

                <div class="modal-footer">
                    <?php if ($orcamento['dataAprovacao'] == NULL) {   ?>
                        <?php if (!($orcamento['idOrcamentoStatus'] == ORCAMENTOSTATUS_ORCAR && $_SESSION['administradora'] == 0 || 
                                    $orcamento['idOrcamentoStatus'] == ORCAMENTOSTATUS_BACKLOG && $_SESSION['administradora'] == 0)) { ?>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#reprovarModal" class="btn btn-sm btn-danger">Não Aprovar</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#aprovarModal" class="btn btn-sm btn-primary">Aprovar</button>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="modal-footer">
                    <?php if ($orcamento['idOrcamentoStatus'] == ORCAMENTOSTATUS_APROVADO && $orcamento['idContrato'] == NULL && $_SESSION['administradora'] == 1) {   ?>
                        <div class="col align-self-start pl-0">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#gerarContratoModal"
                                class="btn btn-warning">Gerar Contrato</button>
                        </div>
                    <?php } ?>
                    <?php if ($orcamento['idOrcamentoStatus'] == ORCAMENTOSTATUS_BACKLOG) {   ?>
                        <div class="col align-self-start pl-0">
                            <button type="button" class="btn btn-warning" id="pedirButton" data-id="<?php echo $orcamento['idOrcamento'] ?>">Pedir Orçamento</button>
                        </div>
                    <?php } ?>
                    <?php if ($orcamento['idContrato'] == NULL || $orcamento['idOrcamentoStatus'] == ORCAMENTOSTATUS_APROVADO) { ?>
                        <?php if (!($orcamento['idOrcamentoStatus'] == ORCAMENTOSTATUS_ORCAR && $_SESSION['administradora'] == 0)) { ?>
                            <button type="submit" class="btn btn-success">Atualizar</button>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            </form>
        </div>

        <!--------- MODAL ORCAMENTO ITENS ALTERAR --------->
        <?php include_once 'modalOrcamentoItens_alterar.php' ?>

        <!--------- MODAL ORCAMENTO ITENS INSERIR --------->
        <?php include_once 'modalOrcamentoItens_inserir.php' ?>

        <!--------- MODAL ORCAMENTO ITENS EXCLUIR --------->
        <?php include_once 'modalOrcamentoItens_excluir.php' ?>

        <!--------- MODAL GERAR CONTRATO --------->
        <?php include_once 'modalGerarContrato.php' ?>

        <!--------- MODAL REPROVAR CONTRATO --------->
        <?php include_once 'modalOrcamento_reprovar.php' ?>

        <!--------- MODAL APROVAR CONTRATO --------->
        <?php include_once 'modalOrcamento_aprovar.php' ?>



    </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script src="orcamento.js"></script>
    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

    <script>
        $(document).ready(function() {
            $('#pedirButton').click(function() {
                var idOrcamento = $(this).data('id');
                $.ajax({
                    url: "../database/orcamento.php?operacao=atualizar&acao=pedir",
                    method: "POST",
                    dataType: "json",
                    data: {
                        idOrcamento: idOrcamento
                    },
                    success: function(msg) {
                        window.location.reload();
                    }
                });
            });
        });
    </script>


    <!-- Gabriel 201223 id745 include de modalNotaOrcamento -->


</body>

</html>