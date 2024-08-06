<?php 

function montaKanban($kanbanDemanda)
{
	$dataAtual = date("d/m/Y"); 
    $hr = '';
    $dataNaTela = '';
    $atendenteNaTela = '';
    $statusDemandaNaTela = '';

    $dataFechamento = null;
    if(isset($kanbanDemanda['dataFechamentoFormatada'])){
        $dataFechamento = $kanbanDemanda['dataFechamentoFormatada'];
    }

    $dataPrevisaoInicio = null;
    if(isset($kanbanDemanda['dataPrevisaoInicio'])){
        $dataPrevisaoInicio = $kanbanDemanda['dataPrevisaoInicioFormatada']; 
    }
    
    if (isset($kanbanDemanda['idAtendente'])) {
        $hr = '<hr class="mt-2 mb-0">';
        $atendenteNaTela = '<span class="ts-cardDataPrevisao">' . ' ' . $kanbanDemanda['nomeAtendente'] . '</span>';
    }

    if ($kanbanDemanda['idTipoStatus'] == TIPOSTATUS_RESPONDIDO || $kanbanDemanda['idTipoStatus'] == TIPOSTATUS_AGENDADO || $kanbanDemanda['idTipoStatus'] == TIPOSTATUS_PAUSADO) {
        $hr = '<hr class="mt-2 mb-0">';
        $statusDemandaNaTela = '<span class="ts-cardStatusDemanda">' . ' ' . $kanbanDemanda['nomeTipoStatus'] . '</span>';
    }

    if ($kanbanDemanda['idTipoStatus'] == TIPOSTATUS_REALIZADO || $kanbanDemanda['idTipoStatus'] == TIPOSTATUS_VALIDADO) {
        if($dataFechamento != null){
            $hr = '<hr class="mt-2 mb-0">';
            $dataNaTela= '<span class="ts-cardDataPrevisao">' . ' Entrega: ' . $dataFechamento . '</span>';
        }
    } else {
        if ($dataPrevisaoInicio != null) {
            $hr = '<hr class="mt-2 mb-0">';
            $dataNaTela= '<span class="ts-cardDataPrevisao">' . ' Previsão: ' . $dataPrevisaoInicio . '</span>';
        }
    
    }
    
	
	$kanban = '<span class="card-body border ts-kanbanQuadro mt-2 ts-click';
	if(($dataPrevisaoInicio != null) && ($dataPrevisaoInicio <= $dataAtual) && $kanbanDemanda['idTipoStatus'] != TIPOSTATUS_REALIZADO){
		$kanban = $kanban . ' ts-cardAtrasado';
	}
    //lucas 28032024 - adicionado na url idContratoTipo
	$kanban = $kanban . '" id="kanbanCard" data-idDemanda="' . $kanbanDemanda["idDemanda"] .'" .  data-idContratoTipo="' . $kanbanDemanda["idContratoTipo"] .'"  >';

		if(isset($kanbanDemanda["idContrato"])){
			$kanban = $kanban .$kanbanDemanda["nomeContrato"] . ' : ' . $kanbanDemanda["idContrato"] . ' ' . $kanbanDemanda["tituloContrato"]. '<br>' ;
		}
		
		$kanban = $kanban .
        $kanbanDemanda["idDemanda"] . ' ' . $kanbanDemanda["tituloDemanda"] . 
        $hr ;

        $prioridade = '';
        if($kanbanDemanda["prioridade"] < 99){
            if($kanbanDemanda["prioridade"] < 10){
                $prioridade = '(0' .$kanbanDemanda["prioridade"] . ')';
            }else{
                $prioridade = '(' .$kanbanDemanda["prioridade"] . ')';
            }
        }
        
        $kanban = $kanban . $prioridade . $statusDemandaNaTela . $atendenteNaTela . '<br>' . $dataNaTela . 
		'</span>';
		
	return $kanban;
}

?>