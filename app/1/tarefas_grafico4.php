<?php
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$meses = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);
$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$tarefa = array();
$sql = "SELECT * FROM (SELECT YEAR(horaInicioReal) AS Ano, MONTH(horaInicioReal) AS Mes, tipoocorrencia.nomeTipoOcorrencia, SEC_TO_TIME(SUM(TIME_TO_SEC(horasCobrado))) AS total FROM tarefa
        INNER JOIN demanda ON demanda.idDemanda = tarefa.idDemanda
        INNER JOIN tipoocorrencia ON tipoocorrencia.idTipoOcorrencia = demanda.idTipoOcorrencia
        GROUP BY YEAR(horaInicioReal), MONTH(horaInicioReal), tipoocorrencia.nomeTipoOcorrencia) subquery
        WHERE total IS NOT NULL
        ORDER BY Ano, Mes";
//echo "-SQL->" . json_encode($sql) . "\n";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    $ano = $row['Ano'];
    $mes = $row['Mes'];
    $mesNome = $meses[$mes];
    $row['Mes'] = $mesNome;
    unset($row['Ano']);
    $tarefa[$ano][$mesNome][] = $row;
    $rows++;
}

$jsonSaida = $tarefa;
?>