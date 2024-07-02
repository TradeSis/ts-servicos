<?php
// Lucas 06122023 id715 - layout demanda
// Lucas 17102023 novo padrao
// helio 01022023 altereado para include_once
// helio 26012023 16:16
include_once('../header.php');
include_once '../database/tipostatus.php';

$idTipoStatus = $_GET['idTipoStatus'];

$status = buscaTipoStatus(null, $idTipoStatus);

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">

    <!-- Lucas 06122023 id715 - Modelo de exibir modal em programas -->
    <div class="modal fade" id="meuModal" tabindex="-1" aria-labelledby="meuModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="ts-tituloPrincipal">Alterar Status</h2>
                    <a href="../configuracao/?tab=configuracao&stab=tipostatus" role="button" class="btn-close"></a>
                </div>
                <div class="modal-body">


                    <div class="container-fluid">
                        <div class="row">
                             <!-- MENSAGENS/ALERTAS -->
                        </div>
                        <div class="row">
                             <!-- BOTOES AUXILIARES -->
                        </div>
                        <div class="row"> <!-- LINHA SUPERIOR A TABLE -->
                            <div class="col-3">
                                <!-- TITULO -->
                            </div>
                            <div class="col-7">
                                <!-- FILTROS -->
                            </div>

                            <div class="col-2 text-end">
                                <!-- BOTÂO VOLTAR-->
                            </div>
                        </div>

                        <form class="mb-4" action="../database/tipostatus.php?operacao=alterar" method="post">

                            <div class="col-md-12 mt-3">
                                <label class='form-label ts-label'></label>
                                <input type="text" class="form-control ts-input" name="nomeTipoStatus" value="<?php echo $status['nomeTipoStatus'] ?>">
                                <input type="hidden" class="form-control ts-input" name="idTipoStatus" value="<?php echo $status['idTipoStatus'] ?>">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label ts-label">Atendimento(0=Atendente 1=Cliente)</label>
                                        <select class="form-select ts-input" name="mudaPosicaoPara">
                                            <option value="<?php echo $status['mudaPosicaoPara'] ?>"><?php echo $status['mudaPosicaoPara'] ?></option>
                                            <option>0</option>
                                            <option>1</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label ts-label">Situação (0=Fechado 1=Aberto)</label>
                                        <select class="form-select ts-input" name="mudaStatusPara">
                                            <option value="<?php echo $status['mudaStatusPara'] ?>"><?php echo $status['mudaStatusPara'] ?></option>
                                            <option>0</option>
                                            <option>1</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

    <script>
        var myModal = new bootstrap.Modal(document.getElementById("meuModal"), {});
        document.onreadystatechange = function() {
            myModal.show();
        };
    </script>

</body>

</html>