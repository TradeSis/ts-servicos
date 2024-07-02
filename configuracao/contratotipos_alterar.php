<?php
// Lucas 17102023 novo padrao
include_once('../header.php');
include_once '../database/contratotipos.php';
include_once '../database/tipoocorrencia.php';
include_once '../database/tipostatus.php';
include_once(ROOT . '/cadastros/database/servicos.php');


$idContratoTipo = $_GET['idContratoTipo'];
$contratotipo = buscaContratoTipos($idContratoTipo);
$tipoOcorrencias = buscaTipoOcorrencia();
$tiposStatus = buscaTipoStatus();
$servicos = buscaServicos();
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <BR> <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Alterar Contrato Tipos</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="../configuracao/?tab=configuracao&stab=contratotipos" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form class="mb-4" action="../database/contratotipos.php?operacao=alterar" method="post">

            <div class="row mt-3">
                <div class="col-md-4">
                    <label class='form-label ts-label'>Nome</label>
                    <input type="text" name="idContratoTipo" class="form-control ts-input" value="<?php echo $contratotipo['idContratoTipo'] ?>">
                </div>
                <div class="col-md-4">
                    <label class='form-label ts-label'>Nome Contrato</label>
                    <input type="text" name="nomeContrato" class="form-control ts-input" value="<?php echo $contratotipo['nomeContrato'] ?>">
                </div>
                <div class="col-md-4">
                    <label class='form-label ts-label'>Nome Demanda</label>
                    <input type="text" name="nomeDemanda" class="form-control ts-input" value="<?php echo $contratotipo['nomeDemanda'] ?>">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <label class="form-label ts-label">Tipo Ocorrencia</label>
                    <select class="form-select ts-input" name="idTipoOcorrenciaPadrao">
                        <option value="<?php echo null ?>">
                            <?php echo "Selecione" ?>
                        </option>
                        <?php
                        foreach ($tipoOcorrencias as $tipoOcorrencia) {
                        ?>
                            <option <?php
                                    if ($tipoOcorrencia['idTipoOcorrencia'] == $contratotipo['idTipoOcorrenciaPadrao']) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $tipoOcorrencia['idTipoOcorrencia'] ?>">
                                <?php echo $tipoOcorrencia['nomeTipoOcorrencia'] ?>
                            </option>
                        <?php  } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">Tipo Staus</label>
                    <select class="form-select ts-input" name="idTipoStatus_fila">
                        <option value="<?php echo null ?>">
                            <?php echo "Selecione" ?>
                        </option>
                        <?php
                        foreach ($tiposStatus as $tipoStatus) {
                        ?>
                            <option <?php
                                    if ($tipoStatus['idTipoStatus'] == $contratotipo['idTipoStatus_fila']) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $tipoStatus['idTipoStatus'] ?>">
                                <?php echo $tipoStatus['nomeTipoStatus'] ?>
                            </option>
                        <?php  } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">Serviços</label>
                    <select class="form-select ts-input" name="idServicoPadrao">
                        <option value="<?php echo null ?>">
                            <?php echo "Selecione" ?>
                        </option>
                        <?php
                        foreach ($servicos as $servico) {
                        ?>
                            <option <?php
                                    if ($servico['idServico'] == $contratotipo['idServicoPadrao']) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $servico['idServico'] ?>">
                                <?php echo $servico['nomeServico'] ?>
                            </option>
                        <?php  } ?>
                    </select>

                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
            </div>
        </form>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>