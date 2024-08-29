<?php
// helio 21032023 - compatibilidade chamada chamaApi
// helio 01022023 altereado para include_once, usando funcao conectaMysql

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


include_once __DIR__ . "/../conexao.php";


function buscaChecklistDemanda($idDemanda=null,$idChecklist=null)
{
	
	$demandachecklist = array();
	
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idChecklist' => $idChecklist,
		'idDemanda' => $idDemanda
	);
	$demandachecklist = chamaAPI(null, '/servicos/demandachecklist', json_encode($apiEntrada), 'GET');

	//echo json_encode ($demandachecklist);
	return $demandachecklist;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	if ($operacao=="inserir") {

		$ordem = isset($_POST["ordem"]) && $_POST["ordem"] !== "" ? $_POST["ordem"] : 0;

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idDemanda' => $_POST['idDemanda'],
			'titulo' => $_POST['titulo'],
			'descricao' => $_POST["descricao"],
			'ordem' => $ordem
		);
		
		$demandachecklist = chamaAPI(null, '/servicos/demandachecklist', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {

		$ordem = isset($_POST["ordem"]) && $_POST["ordem"] !== "" ? $_POST["ordem"] : 0;

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idChecklist' => $_POST['idChecklist'],
			'idDemanda' => $_POST['idDemanda'],
			'titulo' => $_POST['titulo'],
			'descricao' => $_POST["descricao"],
			'ordem' => $ordem,
			'statusCheck' => $_POST['statusCheck'],
		);
		$demandachecklist = chamaAPI(null, '/servicos/demandachecklist', json_encode($apiEntrada), 'POST');

	}

	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idChecklist' => $_POST['idChecklist'],
			'idDemanda' => $_POST['idDemanda']
		);
		$demandachecklist = chamaAPI(null, '/servicos/demandachecklist', json_encode($apiEntrada), 'DELETE');

	}

	
	if ($operacao == "buscar") {
		$idDemanda = isset($_POST["idDemanda"]) && $_POST["idDemanda"] !== "" ? $_POST["idDemanda"] : null;
		$idChecklist = isset($_POST["idChecklist"]) && $_POST["idChecklist"] !== "" ? $_POST["idChecklist"] : null;

        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idDemanda' => $idDemanda,
            'idChecklist' => $idChecklist
        );
        $demandachecklist = chamaAPI(null, '/servicos/demandachecklist', json_encode($apiEntrada), 'GET');

        echo json_encode($demandachecklist);
        return $demandachecklist;
    }

}



?>