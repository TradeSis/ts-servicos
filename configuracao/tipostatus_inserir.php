<?php
// Lucas 17102023 novo padrao
// helio 01022023 altereado para include_once
// helio 26012023 16:16
include_once('../header.php');
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
                <h2 class="ts-tituloPrincipal">Inserir Status</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="../configuracao/?tab=configuracao&stab=tipostatus" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form class="mb-4" action="../database/tipostatus.php?operacao=inserir" method="post">

            <div class="col-md-12">
                <label class='form-label ts-label'>nome Status</label>
                <input type="text" name="nomeTipoStatus" class="form-control ts-input" autocomplete="off">

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label ts-label">Atendimento(0=Atendente 1=Cliente)</label>
                        <select class="form-select ts-input" name="mudaPosicaoPara">
                            <option>0</option>
                            <option>1</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label ts-label">Situação (0=Fechado 1=Aberto)</label>
                        <select class="form-select ts-input" name="mudaStatusPara">
                            <option>0</option>
                            <option>1</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-end mt-2">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
            </div>
        </form>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>