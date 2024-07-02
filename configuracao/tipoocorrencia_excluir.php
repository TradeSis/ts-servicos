<?php
// Lucas 17102023 novo padrao
// helio 01022023 altereado para include_once
// helio 26012023 16:16
include_once('../header.php');
include_once '../database/tipoocorrencia.php';

$idTipoOcorrencia = $_GET['idTipoOcorrencia'];

$ocorrencias = buscaTipoOcorrencia(null, $idTipoOcorrencia);

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
                <h2 class="ts-tituloPrincipal">Excluir Ocorrência</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="../configuracao/?tab=configuracao&stab=tipoocorrencia" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form class="mb-4" action="../database/tipoocorrencia.php?operacao=excluir" method="post">
            <div class="col-md-12">
                <label class='form-label ts-label'></label>
                <input type="text" class="form-control ts-input" name="nomeTipoOcorrencia" value="<?php echo $ocorrencias['nomeTipoOcorrencia'] ?>" disabled>
                <input type="hidden" class="form-control ts-input" name="idTipoOcorrencia" value="<?php echo $ocorrencias['idTipoOcorrencia'] ?>">
            </div>

            <div class="text-end mt-4">
                <button type="submit" id="botao" class="btn btn-sm btn-danger"><i class="bi bi-x-octagon"></i>&#32;Excluir</button>
            </div>
        </form>


    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>