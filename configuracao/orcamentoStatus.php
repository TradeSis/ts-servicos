<?php
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/orcamentoStatus.php');

$orcamentosStatus = buscaOrcamentoStatus();
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

        <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Status Orçamento</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <button type="button" class="btn btn-success mr-4" data-bs-toggle="modal"
                    data-bs-target="#inserirModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <?php foreach ($orcamentosStatus as $orcamentoStatus) { ?>
                    <tr>
                        <td><?php echo $orcamentoStatus['nomeOrcamentoStatus'] ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#alterarmodal" 
                            data-idOrcamentoStatus="<?php echo $orcamentoStatus['idOrcamentoStatus'] ?>"><i class="bi bi-pencil-square"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#excluirmodal" 
                            data-idOrcamentoStatus="<?php echo $orcamentoStatus['idOrcamentoStatus'] ?>"><i class="bi bi-trash3"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!--------- INSERIR --------->
    <div class="modal" id="inserirModal" tabindex="-1" aria-labelledby="inserirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Status Orçamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="inserirForm">
                        <div class="row">
                            <div class="col-md">
                                <div class="row mt-3">
                                    <div class="col">
                                        <label class="form-label ts-label">nomeOrcamentoStatus</label>
                                        <input type="text" class="form-control ts-input" name="nomeOrcamentoStatus">
                                    </div>
                                </div><!--fim row 1-->
                            </div>
                        </div>
                </div><!--body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--------- ALTERAR --------->
    <div class="modal" id="alterarmodal" tabindex="-1" aria-labelledby="alterarmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar Parâmetros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="alterarForm">
                        <div class="row">
                            <div class="col-md">
                                <div class="row mt-3">
                                    <div class="col">
                                        <label class="form-label ts-label">nomeOrcamentoStatus</label>
                                        <input type="text" class="form-control ts-input" name="nomeOrcamentoStatus" id="nomeOrcamentoStatus">
                                        <input type="hidden" class="form-control ts-input" name="idOrcamentoStatus" id="idOrcamentoStatus">
                                    </div>
                                </div><!--fim row 1-->
                            </div>
                        </div>
                </div><!--body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--------- EXCLUIR --------->
    <div class="modal" id="excluirmodal" tabindex="-1" aria-labelledby="excluirmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar Parâmetros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="alterarForm">
                        <div class="row">
                            <div class="col-md">
                                <div class="row mt-3">
                                    <div class="col">
                                        <label class="form-label ts-label">nomeOrcamentoStatus</label>
                                        <input type="text" class="form-control ts-input" name="nomeOrcamentoStatus" id="EXCnomeOrcamentoStatus" readonly>
                                        <input type="hidden" class="form-control ts-input" name="idOrcamentoStatus" id="EXCidOrcamentoStatus">
                                    </div>
                                </div><!--fim row 1-->
                            </div>
                        </div>
                </div><!--body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>

        $(document).ready(function () {

            $(document).on('click', 'button[data-bs-target="#alterarmodal"]', function () {
                var idOrcamentoStatus = $(this).attr("data-idOrcamentoStatus");
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/orcamentoStatus.php?operacao=buscar',
                    data: {
                        idOrcamentoStatus: idOrcamentoStatus
                    },
                    success: function (data) {
                        $('#idOrcamentoStatus').val(data.idOrcamentoStatus);
                        $('#nomeOrcamentoStatus').val(data.nomeOrcamentoStatus);
                        $('#alterarmodal').modal('show');
                    }
                });
            });

            $(document).on('click', 'button[data-bs-target="#excluirmodal"]', function () {
                var idOrcamentoStatus = $(this).attr("data-idOrcamentoStatus");
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/orcamentoStatus.php?operacao=buscar',
                    data: {
                        idOrcamentoStatus: idOrcamentoStatus
                    },
                    success: function (data) {
                        $('#EXCidOrcamentoStatus').val(data.idOrcamentoStatus);
                        $('#EXCnomeOrcamentoStatus').val(data.nomeOrcamentoStatus);
                        $('#excluirmodal').modal('show');
                    }
                });
            });

            $("#inserirForm").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/orcamentoStatus.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            $("#alterarForm").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/orcamentoStatus.php?operacao=alterar",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            function refreshPage() {
                window.location.reload();
            }

        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>