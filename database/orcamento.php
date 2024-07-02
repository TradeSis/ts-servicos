<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../conexao.php";

function buscaOrcamentos($idOrcamento = null, $statusOrcamento = null, $idCliente = null)
{

	$orcamento = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idOrcamento' => $idOrcamento,
		'statusOrcamento' => $statusOrcamento,
		'idCliente' => $idCliente,
		
	);
	$orcamento = chamaAPI(null, '/services/orcamento', json_encode($apiEntrada), 'GET');

	return $orcamento;
}
function buscaOrcamentoItens($idOrcamento = null, $idItemOrcamento = null)
{
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	$orcamento = array();
	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idOrcamento' => $idOrcamento,
		'idItemOrcamento' => $idItemOrcamento,
	);
	$orcamento = chamaAPI(null, '/services/orcamentoitens', json_encode($apiEntrada), 'GET');

	return $orcamento;
}




if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	if ($operacao == "inserir") {

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'tituloOrcamento' => $_POST['tituloOrcamento'],
			'descricao' => $_POST['descricao'],
			'idCliente' => $_POST['idCliente'],
			'idSolicitante' => $_POST['idSolicitante'],
			'idServico' => $_POST['idServico']
		);
		
		$orcamentos = chamaAPI(null, '/services/orcamento', json_encode($apiEntrada), 'PUT');

	}

	if ($operacao == "atualizar") {

		$acao = "";
        if (isset($_GET['acao'])) {
            $acao = $_GET['acao'];
        }

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idOrcamento' => $_POST['idOrcamento'],
			'acao' => $acao
		);
		
		$orcamentos = chamaAPI(null, '/services/orcamento/atualizar', json_encode($apiEntrada), 'POST');

		if($acao == "pedir") {
			echo json_encode($orcamentos);
			return $orcamentos;
		}

	}

	if ($operacao == "alterar") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idOrcamento' => $_POST['idOrcamento'],
			'tituloOrcamento' => $_POST['tituloOrcamento'],
			'descricao' => $_POST['descricao'],
			'idCliente' => $_POST['idCliente'],
			'dataAprovacao' => $_POST['dataAprovacao'],
			'horas' => $_POST['horas'],
			'valorHora' => $_POST['valorHora'],
			'valorOrcamento' => $_POST['valorOrcamento'],
			'statusOrcamento' => $_POST['idOrcamentoStatus'],
			'idServico' => $_POST['idServico']
		);
		$orcamentos = chamaAPI(null, '/services/orcamento', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "buscar") {
        $idCliente = $_POST["idCliente"];
        if ($idCliente == "") {
            $idCliente = null;
        }
        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idCliente' => $idCliente,
			'statusOrcamento' => '1', //Aberto
        );
        $orcamento = chamaAPI(null, '/services/orcamento', json_encode($apiEntrada), 'GET');

        echo json_encode($orcamento);
        return $orcamento;
    }

	if ($operacao == "filtrar") {

		$idCliente = $_POST["idCliente"];
		$buscaOrcamento = $_POST["buscaOrcamento"];
		$statusOrcamento = $_POST['statusOrcamento'];

		if ($idCliente == ""){
			$idCliente = null;
		}

		if ($buscaOrcamento == ""){
			$buscaOrcamento = null;
		} 

		if ($statusOrcamento == ""){
			$statusOrcamento = null;
		}

	
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idCliente' => $idCliente,
			'buscaOrcamento' => $buscaOrcamento,
			'statusOrcamento' => $statusOrcamento
		);
		
		$_SESSION['filtro_orcamento'] = $apiEntrada;
		
		$orcamento = chamaAPI(null, '/services/orcamento', json_encode($apiEntrada), 'GET');

		echo json_encode($orcamento);
		return $orcamento;

	}

	if ($operacao == "itensinserir") {

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'tituloItemOrcamento' => $_POST['tituloItemOrcamento'],
			'idOrcamento' => $_POST['idOrcamento'],
			'horas' => $_POST['horas']
		);
		
		$orcamentos = chamaAPI(null, '/services/orcamentoitens', json_encode($apiEntrada), 'PUT');
		echo json_encode($orcamentos);
        return $orcamentos;

	}


	if ($operacao == "itensalterar") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idItemOrcamento' => $_POST['idItemOrcamento'],
			'idOrcamento' => $_POST['idOrcamento'],
			'tituloItemOrcamento' => $_POST['tituloItemOrcamento'],
			'horas' => $_POST['horas']
		);
		$orcamentos = chamaAPI(null, '/services/orcamentoitens', json_encode($apiEntrada), 'POST');
		echo json_encode($orcamentos);
        return $orcamentos;
	}
	if ($operacao == "itensexcluir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idItemOrcamento' => $_POST['idItemOrcamento'],
			'idOrcamento' => $_POST['idOrcamento']
		);
		$orcamentos = chamaAPI(null, '/services/orcamentoitens', json_encode($apiEntrada), 'DELETE');
		echo json_encode($orcamentos);
        return $orcamentos;
	}

	if ($operacao == "itensbuscar") {
        $idItemOrcamento = $_POST["idItemOrcamento"];
        $idOrcamento = $_POST["idOrcamento"];
        if ($idOrcamento == "") {
            $idOrcamento = null;
        }
        if ($idItemOrcamento == "") {
            $idItemOrcamento = null;
        }
        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idOrcamento' => $idOrcamento,
            'idItemOrcamento' => $idItemOrcamento
        );
        $orcamento = chamaAPI(null, '/services/orcamentoitens', json_encode($apiEntrada), 'GET');

        echo json_encode($orcamento);
        return $orcamento;
    }

	if ($operacao == "gerarcontrato") {
        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idContratoTipo' => $_POST["idContratoTipo"],
            'idOrcamento' => $_POST["idOrcamento"],
            'idSolicitante' => $_POST["idSolicitante"]
        );
        $orcamento = chamaAPI(null, '/services/orcamento/contrato', json_encode($apiEntrada), 'POST');

    }

	
	header('Location: ../orcamento/visualizar.php?idOrcamento=' . $_POST['idOrcamento']);

}
