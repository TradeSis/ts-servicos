<?php
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once __DIR__ . "/../conexao.php";

function buscaMensagem($idDemanda, $idUsuario, $idMensagem = null)
{

	$mensagem = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idDemanda' => $idDemanda,
		'idMensagem' => $idMensagem,
		'idUsuario' => $idUsuario,
		'idEmpresa' => $idEmpresa
	);
	$mensagem = chamaAPI(null, '/servicos/mensagem', json_encode($apiEntrada), 'GET');
	return $mensagem;
}
function buscaChat($INidUsuario=null, $OUTidUsuario=null)
{
	$chat = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'INidUsuario' => $INidUsuario,
		'OUTidUsuario' => $OUTidUsuario,
		'idEmpresa' => $idEmpresa
	);
	$chat = chamaAPI(null, '/servicos/chat', json_encode($apiEntrada), 'GET');
	return $chat;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "comentar") {

		$anexo = $_FILES['nomeAnexoMsg'];

		if ($anexo !== null) {
			preg_match("/\.(png|jpg|jpeg|txt|xlsx|pdf|csv|doc|docx|zip){1}$/i", $anexo["name"], $ext);

			if ($ext == true) {
				$pasta = ROOT . "/img/";

				$novoNomeAnexo = $_POST['idDemanda'] . "_" . $anexo["name"];
				$pathAnexo = 'http://' . $_SERVER["HTTP_HOST"] . '/img/' . $novoNomeAnexo;
				move_uploaded_file($anexo['tmp_name'], $pasta . $novoNomeAnexo);


			} else {
				$novoNomeAnexo = " ";
			}

		} 
		$varmsg = strip_tags($_POST['mensagem']);

		$apiEntrada = array(
			'nomeAnexo' => $novoNomeAnexo,
			'pathAnexo' => $pathAnexo,
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idUsuario' => $_POST['idUsuario'],
			'idCliente' => $_POST['idCliente'],
			'idDemanda' => $_POST['idDemanda'],
			'mensagem' => $_POST['mensagem']

		);

		$mensagem = chamaAPI(null, '/servicos/mensagem', json_encode($apiEntrada), 'PUT');
		//header('Location: ../demandas/visualizar.php?id=mensagem&&idDemanda=' . $apiEntrada['idDemanda']);
	}

	if ($operacao == "chat") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'INidUsuario' => $_POST['INidUsuario'],
			'OUTidUsuario' => $_POST['OUTidUsuario'],
			'chat' => $_POST['chat']
		);

		$chat = chamaAPI(null, '/servicos/chat', json_encode($apiEntrada), 'PUT');
		//header('Location: ../demandas/visualizar.php?id=mensagem&&idDemanda=' . $apiEntrada['idDemanda']);
	}

}