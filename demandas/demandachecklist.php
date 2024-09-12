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
            <button type="button" class="btn btn-success mr-4" data-bs-toggle="modal"
                data-bs-target="#inserirChecklistModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
        </div>
    </div>

    <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
        <table class="table table-sm table-hover">
            <thead class="ts-headertabelafixo">
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>Titulo</th>
                    <th class="text-center" style="width: 30px;"></th>
                    <th style="width: 20px;"></th>
                </tr>
            </thead>
            <tbody class="fonteCorpo">
                <?php
                foreach ($demandaschecklist as $demandachecklist) {
                ?>
                    <tr>
                        <td class="ts-click" data-idChecklist='<?php echo $demandachecklist['idChecklist'] ?>' data-idDemanda='<?php echo $demandachecklist['idDemanda'] ?>'><?php echo $demandachecklist['ordem'] ?></td>
                        
                        <td class="ts-click" data-idChecklist='<?php echo $demandachecklist['idChecklist'] ?>' data-idDemanda='<?php echo $demandachecklist['idDemanda'] ?>'><?php echo $demandachecklist['titulo'] ?></td>
                        
                        <td class='ts-check text-center'>
                            <input class="form-check-input" type="checkbox" 
                                data-idDemanda="<?php echo $idDemanda ?>" 
                                data-idChecklist="<?php echo $demandachecklist['idChecklist'] ?>"
                                <?php if ($demandachecklist['statusCheck'] == 1) echo "checked"; ?> style="font-size: 18px">
                        </td>
                        <td>
                        <div class="btn-group dropstart">
                            <button type="button" class="btn" data-toggle="tooltip" data-placement="left" title="Opções" 
                            data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow:none"><i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li class="ms-1 me-1 mt-1">
                                    <button type="button" class="btn btn-danger btn-sm w-100 text-start" data-bs-toggle="modal" data-bs-target="#excluirChecklistModal" data-idDemanda="<?php echo $idDemanda ?>"
                                    data-idChecklist="<?php echo $demandachecklist['idChecklist'] ?>"><i class='bi bi-trash3'></i><span class="ts-btnAcoes"> Excluir</span></button>
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
