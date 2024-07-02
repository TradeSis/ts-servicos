

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
				<button type="button" class="btn btn-success mr-4" data-bs-toggle="modal" data-bs-target="#novoinserirDemandaModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
			</div>
		</div>

		

		<div class="table mt-2 ts-divTabela70 ts-tableFiltros">
			<table class="table table-sm table-hover">
				<thead class="ts-headertabelafixo">
					<tr>
						<th></th>
						<th class="col-4">Demanda</th>
						<th>Responsável</th>
						<th>Abertura</th>
						<th>Status</th>
						<th>Serviços</th>
						<th colspan="2" >Tempo</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="fonteCorpo">
					<?php
					foreach ($demandas as $demanda) {
						$horas = buscaHoras($demanda['idDemanda']);

						$prioridade = $demanda['prioridade'];
						if($prioridade == '99'){
							$prioridade = '';
						  }

						if($demanda['tempoCobrado'] !== null){
							$tempoCobrado = date('H:i', strtotime($demanda['tempoCobrado']));
						}else{
							$tempoCobrado = " ";
						}
						
						if($horas['totalHorasReal'] !== null){
							$totalHorasReal = date('H:i', strtotime($horas['totalHorasReal']));
						}else{
							$totalHorasReal = " ";
						}
					?>
						<tr>
							<td class='ts-click' data-idDemanda='<?php echo $demanda['idDemanda'] ?>'><?php echo $prioridade ?></td>
							<td class='ts-click' data-idDemanda='<?php echo $demanda['idDemanda'] ?>'><?php echo $demanda['idDemanda'] ?> <?php echo $demanda['tituloDemanda'] ?></td>
							<td class='ts-click' data-idDemanda='<?php echo $demanda['idDemanda'] ?>'><?php echo $demanda['nomeAtendente'] ?></td>
							<td class='ts-click' data-idDemanda='<?php echo $demanda['idDemanda'] ?>'><?php echo date('d/m/Y', strtotime($demanda['dataAbertura'])) ?></td>
							<td class="ts-click <?php echo $demanda['nomeTipoStatus'] ?>" data-status='Finalizado' data-idDemanda='<?php echo $demanda['idDemanda'] ?>'><?php echo $demanda['nomeTipoStatus'] ?></td>
							<td class='ts-click' data-idDemanda='<?php echo $demanda['idDemanda'] ?>'><?php echo $demanda['nomeServico'] ?></td>
							<td class='ts-click' data-idDemanda='<?php echo $demanda['idDemanda'] ?>'>
								<span style="font-size: 13px;">Cobrado: &nbsp; <?php echo $tempoCobrado ?> <br>
								Realizado: <?php echo $totalHorasReal ?></span>
							</td>
							<td>
							<div class="btn-group dropstart">
                                <button type="button" class="btn" data-toggle="tooltip" data-placement="left" title="Opções" 
                                data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow:none"><i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="ms-1 me-1 mt-1">
										<a class='btn btn-warning btn-sm w-100 text-start' href='../demandas/visualizar.php?idDemanda=<?php echo $demanda['idDemanda'] ?>' role='button'>
										<i class='bi bi-pencil-square'></i>Alterar</a>
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


	

