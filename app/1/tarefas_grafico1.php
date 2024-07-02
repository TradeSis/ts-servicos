<?php
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$mes = date('m');
$ano = date('Y');

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$tarefa = array();
$sql = "SELECT
        SUM(CASE WHEN demanda.statusDemanda = 1 THEN 1 ELSE 0 END) AS totalAbertos,
        SUM(CASE WHEN demanda.statusDemanda = 0 THEN 1 ELSE 0 END) AS totalSolucionados,
        COUNT(demanda.idDemanda) AS totalDemandas FROM demanda
        WHERE MONTH(demanda.dataAbertura) = $mes AND YEAR(demanda.dataAbertura) = $ano";
//echo "-SQL->" . json_encode($sql) . "\n";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($tarefa, $row);
  $rows = $rows + 1;
}

$jsonSaida = $tarefa[0];
?>