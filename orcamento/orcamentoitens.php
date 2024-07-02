

<div class="container-fluid m-0 p-0">

<div class="row">
    <!-- MENSAGENS/ALERTAS -->
</div>
<div class="row">
     <!-- BOTOES AUXILIARES -->
</div>
<div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
    <div class="col-3 text-start">
        <!-- TITULO -->
    </div>
    <div class="col-7">
        <!-- FILTROS -->
    </div>

    <div class="col-2 text-end">
     <!-- Lucas 25102023 id643 alterado nome do target do botão para chamada do modal -->
        <?php if ($_SESSION['administradora'] == 1) { ?>
        <button type="button" class="btn btn-success mr-4" data-bs-toggle="modal" data-bs-target="#inserirOrcamentoItensModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
        <?php } ?>
    </div>
</div>



<div class="table mt-2 ts-divTabela70 ts-tableFiltros">
    <table class="table table-sm table-hover">
        <thead class="ts-headertabelafixo">
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Horas</th>
                <?php if ($_SESSION['administradora'] == 1) { ?>
                <th colspan="2">Ação</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody class="fonteCorpo">
            <?php
            foreach ($orcamentoitens as $orcamentoitem) {
            ?>
                <tr>
                    <td class='ts-click' data-idItemOrcamento='<?php echo $orcamentoitem['idItemOrcamento'] ?>'><?php echo $orcamentoitem['idItemOrcamento'] ?></td>
                    <td class='ts-click' data-idItemOrcamento='<?php echo $orcamentoitem['idItemOrcamento'] ?>'><?php echo $orcamentoitem['tituloItemOrcamento'] ?></td>
                    <td class='ts-click' data-idItemOrcamento='<?php echo $orcamentoitem['idItemOrcamento'] ?>'><?php echo $orcamentoitem['horas'] ?></td>
                    <?php if ($_SESSION['administradora'] == 1) { ?>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#alterarOrcamentoItensModal" data-idOrcamento="<?php echo $idOrcamento ?>"
                        data-idItemOrcamento="<?php echo $orcamentoitem['idItemOrcamento'] ?>"><i class="bi bi-pencil-square"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#excluirOrcamentoItensModal"  data-idOrcamento="<?php echo $idOrcamento ?>"
                        data-idItemOrcamento="<?php echo $orcamentoitem['idItemOrcamento'] ?>"><i class="bi bi-trash3"></i></button>
                    </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div>




