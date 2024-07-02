<?php
//Lucas 17102023 novo padrao
// helio 01022023 altereado para include_once
// helio 26012023 16:16
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');
$ocorrencias = buscaTipoOcorrencia();

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
        <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Tipo Ocorrência</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->

            </div>

            <div class="col-2 text-end">
                <a href="tipoocorrencia_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
            </div>
        </div>


        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Ocorrência</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <?php
                foreach ($ocorrencias as $ocorrencia) {
                ?>
                    <tr>
                        <td>
                            <?php echo $ocorrencia['nomeTipoOcorrencia'] ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#alterarModal" data-idTipoOcorrencia="<?php echo $ocorrencia['idTipoOcorrencia'] ?>"><i class="bi bi-pencil-square"></i></button>
                            <a class="btn btn-danger btn-sm" href="tipoocorrencia_excluir.php?idTipoOcorrencia=<?php echo $ocorrencia['idTipoOcorrencia'] ?>" role="button"><i class="bi bi-trash3"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <div id="alterarModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Alterar ocorrencia</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="../database/tipoocorrencia.php?operacao=alterar" method="post">
                        <div class="row">
                            <div class="col-md-6 mt-1">
                                <label class="form-label ts-label">Ocorrência</label>
                                <input type="text" name="nomeTipoOcorrencia" id="nomeTipoOcorrencia" class="form-control ts-input" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label ts-label">Inicial</label>
                                <select class="form-control ts-input" id="ocorrenciaInicial" name="ocorrenciaInicial">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="idTipoOcorrencia" id="idTipoOcorrencia" />
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        $(document).ready(function() {
            $('button[data-target="#alterarModal"]').click(function() {
                var idTipoOcorrencia = $(this).attr("data-idTipoOcorrencia");
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo URLROOT ?>/servicos/database/tipoocorrencia.php?operacao=buscar',
                    data: {
                        idTipoOcorrencia: idTipoOcorrencia
                    },
                    success: function(data) {
                        //alert(JSON.stringify(data, null, 2));

                        $('#nomeTipoOcorrencia').val(data.nomeTipoOcorrencia);
                        $('#ocorrenciaInicial').val(data.ocorrenciaInicial);
                        $('#idTipoOcorrencia').val(data.idTipoOcorrencia);
                        $('#alterarModal').modal('show');
                    },
                });
            });
        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>