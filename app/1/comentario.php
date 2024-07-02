<?php
//gabriel 08022023 10:48
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}

$conexao = conectaMysql($idEmpresa);
$demanda = array();
$sql = "SELECT comentario.*, usuario.nomeUsuario, demanda.idDemanda FROM comentario  
        INNER JOIN usuario on comentario.idUsuario = usuario.idUsuario 
        INNER JOIN demanda on comentario.idDemanda = demanda.idDemanda ";

//echo "-SQL->".json_encode($sql)."\n";

if (isset($jsonEntrada["idComentario"])) {
  $sql = $sql . " where comentario.idComentario = " . $jsonEntrada["idComentario"]; 
} else {
  if (isset($jsonEntrada["idDemanda"])) {
    $sql = $sql . " where comentario.idDemanda = " . $jsonEntrada["idDemanda"];
  }
}
$sql = $sql ." ORDER BY comentario.idComentario DESC";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($demanda, $row);
  $rows = $rows + 1;
}
if (isset($jsonEntrada["idComentario"]) && $rows==1) {
  $demanda = $demanda[0];
}
$jsonSaida = $demanda;
