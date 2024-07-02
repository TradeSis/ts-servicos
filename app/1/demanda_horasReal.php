<?php
//lucas 29112023 id706 - Melhorias Demandas 2
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG 
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_horasReal";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "services_" . date("dmY") . ".log", "a");
    }
  }
}
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 4) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
  $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);

$tarefa = array();

$sql = "SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(subquery.horasReal))) AS totalHorasReal
FROM (SELECT tarefa.*, demanda.idContrato, TIMEDIFF(tarefa.horaFinalReal, tarefa.horaInicioReal) AS horasReal
FROM tarefa 
LEFT JOIN demanda ON tarefa.idDemanda = demanda.idDemanda ";

if (isset($jsonEntrada["idDemanda"])) {
  $sql = $sql . " where demanda.idDemanda = " . $jsonEntrada["idDemanda"] . ") AS subquery";
}
if (isset($jsonEntrada["idContrato"])) {
  $sql = $sql . " where demanda.idContrato = " . $jsonEntrada["idContrato"] . ") AS subquery";
}

//echo "-SQL->".json_encode($sql)."\n";
$rows = 0;

//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 5) {
    fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
  }
}
//LOG

//TRY-CATCH
try {

  $buscar = mysqli_query($conexao, $sql);
  if (!$buscar)
    throw new Exception(mysqli_error($conexao));

  while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($tarefa, $row);
    $rows = $rows + 1;
  }
  if (isset($jsonEntrada["idDemanda"]) && $rows == 1) {
    $tarefa = $tarefa[0];
  }
  if (isset($jsonEntrada["idContrato"]) && $rows == 1) {
    $tarefa = $tarefa[0];
  }
  $jsonSaida = $tarefa;
} catch (Exception $e) {
  $jsonSaida = array(
    "status" => 500,
    "retorno" => $e->getMessage()
  );
  if ($LOG_NIVEL >= 1) {
    fwrite($arquivo, $identificacao . "-ERRO->" . $e->getMessage() . "\n");
  }
} finally {
  // ACAO EM CASO DE ERRO (CATCH), que mesmo assim precise
}
//TRY-CATCH




//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 4) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG
