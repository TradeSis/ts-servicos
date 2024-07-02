<?php
$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
  $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$contratotipo = array();

$sql = "SELECT * FROM contratotipos ";
if (isset($jsonEntrada["idContratoTipo"])) {
  $sql = $sql . " where contratotipos.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
}

//echo "-SQL->".json_encode($sql)."\n";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($contratotipo, $row);
  $rows = $rows + 1;
}
//echo "-ARRAY->".json_encode($contratotipo)."\n";

if (isset($jsonEntrada["idContratoTipo"]) && $rows == 1) {
  $contratotipo = $contratotipo[0];
}

$jsonSaida = $contratotipo;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";
