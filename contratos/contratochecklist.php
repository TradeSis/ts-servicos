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
            <button type="button" class="btn btn-success mr-4" data-bs-toggle="modal"
                data-bs-target="#inserirChecklistModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
        </div>
    </div>



    <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
        <table class="table table-sm table-hover">
            <thead class="ts-headertabelafixo">
                <tr>
                    <th>ID</th>
                    <th class="col-4">Descrição</th>
                    <th>dataPrevisto</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="fonteCorpo">
                <?php
                foreach ($contratoschecklist as $contratochecklist) {
                ?>
                    <tr>
                        <td data-idChecklist='<?php echo $contratochecklist['idChecklist'] ?>'><?php echo $contratochecklist['idChecklist'] ?></td>
                        <td data-idChecklist='<?php echo $contratochecklist['idChecklist'] ?>'><?php echo $contratochecklist['descricao'] ?></td>
                        <td data-idChecklist='<?php echo $contratochecklist['idChecklist'] ?>'>
                            <?php echo $contratochecklist['dataPrevisto'] ? date('d/m/Y', strtotime($contratochecklist['dataPrevisto'])) : ''; ?>
                        </td>
                        <td class='ts-check'>
                            <input class="form-check-input" type="checkbox" 
                                data-idContrato="<?php echo $idContrato ?>" 
                                data-idChecklist="<?php echo $contratochecklist['idChecklist'] ?>"
                                <?php if ($contratochecklist['statusCheck'] == 1) echo "checked"; ?>>
                        </td>
                        <td>
                        <div class="btn-group dropstart">
                            <button type="button" class="btn" data-toggle="tooltip" data-placement="left" title="Opções" 
                            data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow:none"><i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li class="ms-1 me-1 mt-1">
                                    <button type="button" class="btn btn-warning btn-sm w-100 text-start" data-bs-toggle="modal" data-bs-target="#alterarChecklistModal" data-idContrato="<?php echo $idContrato ?>"
                                    data-idChecklist="<?php echo $contratochecklist['idChecklist'] ?>"><i class='bi bi-pencil-square'></i><span class="ts-btnAcoes"> Alterar</span></button>
                                </li>
                                <li class="ms-1 me-1 mt-1">
                                    <button type="button" class="btn btn-danger btn-sm w-100 text-start" data-bs-toggle="modal" data-bs-target="#excluirChecklistModal" data-idContrato="<?php echo $idContrato ?>"
                                    data-idChecklist="<?php echo $contratochecklist['idChecklist'] ?>"><i class='bi bi-trash3'></i><span class="ts-btnAcoes"> Excluir</span></button>
                                </li>
                                <li class="ms-1 me-1 mt-1">
                                    <button type="button" class="btn btn-success btn-sm w-100 text-start" data-bs-toggle="modal" data-bs-target="#tarefaChecklistModal" data-idContrato="<?php echo $idContrato ?>"
                                    data-idChecklist="<?php echo $contratochecklist['idChecklist'] ?>"><i class='bi bi-plus-square'></i><span class="ts-btnAcoes"> Tarefa</span></button>
                                </li>
                            </ul>
                        </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>