<?php
// helio 21032023 - compatibilidade chamada chamaApi
// helio 01022023 altereado para include_once, usando funcao conectaMysql

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


include_once __DIR__ . "/../conexao.php";


function buscaChecklist($idContrato=null,$idChecklist=null)
{
	
	$contratochecklist = array();
	
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idChecklist' => $idChecklist,
		'idContrato' => $idContrato
	);
	$contratochecklist = chamaAPI(null, '/servicos/contratochecklist', json_encode($apiEntrada), 'GET');

	//echo json_encode ($contratochecklist);
	return $contratochecklist;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	if ($operacao=="inserir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idContrato' => $_POST['idContrato'],
			'descricao' => $_POST['descricao'],
			'dataPrevisto' => $_POST['dataPrevisto']
		);
		
		$contratochecklist = chamaAPI(null, '/servicos/contratochecklist', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idChecklist' => $_POST['idChecklist'],
			'idContrato' => $_POST['idContrato'],
			'descricao' => $_POST['descricao'],
			'dataPrevisto' => $_POST['dataPrevisto'],
			'statusCheck' => $_POST['statusCheck']
		);
		$contratochecklist = chamaAPI(null, '/servicos/contratochecklist', json_encode($apiEntrada), 'POST');

	}

	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idChecklist' => $_POST['idChecklist'],
			'idContrato' => $_POST['idContrato']
		);
		$contratochecklist = chamaAPI(null, '/servicos/contratochecklist', json_encode($apiEntrada), 'DELETE');

	}

	if ($operacao=="tarefa") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idChecklist' => $_POST['idChecklist'],
			'idContrato' => $_POST['idContrato'],
			'idSolicitante' => $_POST['idSolicitante']
		);
		$contratochecklist = chamaAPI(null, '/servicos/contratochecklist/tarefa', json_encode($apiEntrada), 'POST');
		echo json_encode($apiEntrada);
        return $contratochecklist;
	}

	if ($operacao == "buscar") {
        $idContrato = $_POST["idContrato"];
        $idChecklist = $_POST["idChecklist"];
        if ($idContrato == "") {
            $idContrato = null;
        }
        if ($idChecklist == "") {
            $idChecklist = null;
        }
        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idContrato' => $idContrato,
            'idChecklist' => $idChecklist
        );
        $contratochecklist = chamaAPI(null, '/servicos/contratochecklist', json_encode($apiEntrada), 'GET');

        echo json_encode($contratochecklist);
        return $contratochecklist;
    }

}



?>