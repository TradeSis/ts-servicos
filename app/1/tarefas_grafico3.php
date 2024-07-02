<?php
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";
$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$tarefa = array();

$mes = date('m');
$ano = date('Y');

$sql = "SELECT tipoocorrencia.nomeTipoOcorrencia, COUNT(*) AS total FROM demanda
        INNER JOIN tipoocorrencia on demanda.idTipoOcorrencia = tipoocorrencia.idTipoOcorrencia
        WHERE demanda.idTipoOcorrencia != '' AND MONTH(demanda.dataAbertura) = $mes AND YEAR(demanda.dataAbertura) = $ano
        GROUP BY demanda.idTipoOcorrencia";
    //echo "-SQL->" . json_encode($sql) . "\n";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($tarefa, $row);
  $rows = $rows + 1;
}

$jsonSaida = $tarefa;
?>