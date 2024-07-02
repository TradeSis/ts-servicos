<?php
// Lucas 25102023 id643 revisao geral
// Lucas 13102023 novo padrao
include_once '../header.php';
include_once '../database/contratos.php';
include '../database/contratotipos.php';
include_once '../database/demanda.php';
include_once '../database/contratochecklist.php';
$idContrato = $_GET['idContrato'];
$contrato = buscaContratos($idContrato, null);
$contratoTipo = buscaContratoTipos($contrato['idContratoTipo']);


include_once(ROOT . '/cadastros/database/usuario.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once '../database/contratos.php';
include_once(ROOT . '/cadastros/database/servicos.php');
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');
include_once '../database/contratoStatus.php';
include_once '../database/tarefas.php';
// Gabriel 201223 id745 dados nota
include_once(ROOT . '/cadastros/database/pessoas.php');

//Lucas 22112023 id 688 - Removido visão do cliente ($ClienteSession)

$usuario = buscaUsuarios(null, $_SESSION['idLogin']);
$clientes = buscaClientes();
$servicos = buscaServicos();
$atendentes = buscaAtendente();
// Gabriel 201223 id745 dados nota
$pessoas = buscarPessoa();
$cidades = buscarCidades();
// Lucas 25102023 id643 ajustado variavel $tipoocorrencias para ficar igual de demanda
$tipoocorrencias = buscaTipoOcorrencia();
$contratoStatusTodos = buscaContratoStatus();

$contratoschecklist = buscaChecklist($idContrato);
$demandas = buscaDemandas(null, null, $idContrato);
$horasCobrado = buscaTotalHorasCobrada($idContrato);
$horasReal = buscaTotalHorasReal($idContrato, null);
//Remover os zeros de segundo de totalHorasCobrado
if ($horasCobrado['totalHorasCobrado'] !== null) {
    $totalHorasCobrado = $horasCobrado['totalHorasCobrado'];
} else {
    $totalHorasCobrado = "00:00";
}
//Remover os zeros de segundo de totalHorasReal
if ($horasReal['totalHorasReal'] !== null) {
    $totalHorasRealizado = $horasReal['totalHorasReal']; 
} else {
    $totalHorasRealizado = "00:00";
}
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
                <form action="../database/contratos.php?operacao=alterar" method="post">
                    <div class="container">
                        <div class="row g-3">
                            <div class="col-md-9 d-flex">
                                <span class="ts-tituloPrincipalModal"><?php echo $contrato['idContrato'] ?></span>
                                <input type="hidden" class="form-control ts-inputSemBorda" name="idContrato" value="<?php echo $contrato['idContrato'] ?>">
                                <input type="text" class="form-control ts-inputSemBorda ts-tituloPrincipalModal" name="tituloContrato" value="<?php echo $contrato['tituloContrato'] ?>" style="z-index: 1;">
                                <input type="hidden" class="form-control ts-input" name="idContratoTipo" value="<?php echo $contrato['idContratoTipo'] ?>">
                            </div>
                            <div class="col-md-3 d-flex">
                                <span class="ts-subTitulo"><strong>Status: </strong></span>
                                <select class="form-select ts-input ts-selectDemandaModalVisualizar" name="idContratoStatus" id="idContratoStatus" autocomplete="off" <?php if ($_SESSION['administradora'] == 0) echo 'disabled'; ?>>
                                    <?php foreach ($contratoStatusTodos as $contratoStatus) { ?>
                                    <option <?php if ($contratoStatus['idContratoStatus'] == $contrato['idContratoStatus']) { echo "selected"; } ?>
                                    value="<?php echo $contratoStatus['idContratoStatus'] ?>"><?php echo $contratoStatus['nomeContratoStatus'] ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="hidden" class="form-control ts-input" name="idCliente" value="<?php echo $contrato['idCliente'] ?>">
                                <span class="ts-subTitulo"><strong>Cliente : </strong><span><?php echo $contrato['nomeCliente'] ?></span>
                            </div>
                            <div class="col-md-4">
                                <!-- <input type="hidden" class="form-control ts-input" name="idSolicitante" id="idSolicitante" value="<?php echo $contrato['idSolicitante'] ?>" readonly>
                                <span class="ts-subTitulo"><strong>Solicitante : </strong> <?php echo $contrato['nomeSolicitante'] ?></span> -->
                            </div>

                            <div class="col-md-5 d-flex">
                                <?php if ($_SESSION['administradora'] == 1) { ?>
                                    <span class="ts-subTitulo"><strong>Serviço: </strong></span>
                                    <select class="form-select ts-input ts-selectDemandaModalVisualizar" name="idServico" id="idServico" autocomplete="off">
                                            <?php foreach ($servicos as $servico) { ?>
                                        <option <?php if ($servico['idServico'] == $contrato['idServico']) echo "selected"; ?> value="<?php echo $servico['idServico'] ?>"><?php echo $servico['nomeServico'] ?>
                                        </option>
                                    <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <span class="ts-subTitulo"><strong>Serviço : </strong> <?php echo $contrato['nomeServico'] ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div id="ts-tabs">
                            <div class="tab aba1 whiteborder" id="tab-contrato"><?php echo $contratoTipo['nomeContrato'] ?></div>
                            <div class="tab aba2" id="tab-demandasontrato"><?php echo $contratoTipo['nomeDemanda'] ?></div>
                            <div class="tab aba3" id="tab-contratochecklist">Checklist</div>
                            <div class="tab aba4" id="tab-notascontrato">Notas</div>
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
                                    <div id="ql-toolbarContratoDescricao">
                                        <?php include ROOT."/sistema/quilljs/ql-toolbar-min.php"  ?>
                                        <input type="file" id="anexarContratoDescricao" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileContratoDescricao()" style=" display:none">
                                        <label for="anexarContratoDescricao">
                                            <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                                        </label>
                                    </div>
                                    <div id="ql-editorContratoDescricao" class="ts-displayDisable" style="height: auto!important;">
                                        <?php echo $contrato['descricao'] ?>
                                    </div>
                                    <textarea style="display: none" id="quill-contratoDescricao" name="descricao"><?php echo $contrato['descricao'] ?></textarea>
                                </div>
                            </div>
                            <div class="tabContent aba2_conteudo" style="display: none;">
                                <?php include_once 'demandascontrato.php'; ?>
                            </div>
                            <div class="tabContent aba3_conteudo" style="display: none;">
                                <?php include_once 'contratochecklist.php'; ?>
                            </div>
                            <div class="tabContent aba4_conteudo" style="display: none;">
                                <?php include_once 'notascontrato.php'; ?>
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
                        <a href="../contratos/?tipo=<?php echo $contrato['idContratoTipo'] ?>" role="button" class="btn-close"></a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Abertura</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control ts-inputSemBorda" name="dataAbertura" value="<?php echo date('d/m/Y H:i', strtotime($contrato['dataAbertura'])) ?>" disabled>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Previsao</label>
                    </div>
                    <div class="col-md-7">
                        <input type="date" class="form-control ts-inputSemBorda" name="dataPrevisao" value="<?php echo $contrato['dataPrevisao'] ?>">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Entrega</label>
                    </div>
                    <div class="col-md-7">
                        <input type="date" class="form-control ts-inputSemBorda" name="dataEntrega" value="<?php echo $contrato['dataEntrega'] ?>">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Fechamento</label>
                    </div>
                    <div class="col-md-7">
                        <?php if ($contrato['dataFechamento'] == null) { ?>
                            <input type="text" class="form-control ts-inputSemBorda" name="dataFechamento" value="<?php echo $contrato['dataFechamento'] = '00/00/0000 00:00' ?>" disabled>
                        <?php } else { ?>
                            <input type="text" class="form-control ts-inputSemBorda" name="dataFechamento" value="<?php echo date('d/m/Y H:i', strtotime($contrato['dataFechamento'])) ?>" disabled>
                        <?php } ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Horas</label>
                    </div>
                    <div class="col-md-7">
                        <input type="number" class="form-control ts-inputSemBorda" name="horas" value="<?php echo $contrato['horas'] ?>">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Valor Hora</label>
                    </div>
                    <div class="col-md-7">
                        <input type="number" class="form-control ts-inputSemBorda" name="valorHora" value="<?php echo $contrato['valorHora'] ?>">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Valor Contrato</label>
                    </div>
                    <div class="col-md-7">
                        <input type="number" class="form-control ts-inputSemBorda" name="valorContrato" value="<?php echo $contrato['valorContrato'] ?>">
                    </div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Total Cobrado:</label>
                    </div>
                    <div class="col-md-7 ps-4">
                        <?php echo $totalHorasCobrado ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label class="form-label ts-label">Total Real:</label>
                    </div>
                    <div class="col-md-7 ps-4">
                        <?php echo $totalHorasRealizado ?>
                    </div>
                </div>

                <hr class="mt-4">

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success">Atualizar</button>
                </div>
            </div>
            </form>
        </div>

        <!-- Lucas 25102023 id643 include de modalDemanda_inserir -->
        <!--------- MODAL DEMANDA INSERIR --------->
        <?php include_once '../demandas/modalDemanda_inserir.php' ?>
        <!--------- MODAL CHECKLIST INSERIR --------->
        <?php include_once '../contratos/modalChecklist_inserir.php' ?>
        <!--------- MODAL CHECKLIST ALTERAR --------->
        <?php include_once '../contratos/modalChecklist_alterar.php' ?>
        <!--------- MODAL CHECKLIST EXCLUIR --------->
        <?php include_once '../contratos/modalChecklist_excluir.php' ?>
        <!--------- MODAL CHECKLIST TAREFA --------->
        <?php include_once '../contratos/modalChecklist_tarefa.php' ?>
        
       

    </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <!-- QUILL editor -->

    <script src="contrato.js"></script>
    <!-- LOCAL PARA COLOCAR OS JS -FIM -->


    <!-- Gabriel 201223 id745 include de modalNotaContrato -->
    <!--------- MODAL NOTA CONTRATO VISUALIZAR --------->
    <?php include_once '../contratos/modalNotaContrato_visualizar.php' ?>
    <!--------- MODAL NOTA CONTRATO INSERIR --------->
    <?php include_once '../contratos/modalNotaContrato_inserir.php' ?>
    <!--------- MODAL NOTA CONTRATO ALTERAR --------->
    <?php include_once '../contratos/modalNotaContrato_alterar.php' ?>
    
</body>

</html>