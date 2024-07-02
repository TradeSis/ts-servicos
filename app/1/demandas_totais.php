<?php
//lucas 26092023 ID 576 Demanda/BOTÕES de SITUACOES 
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG 
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_totais";
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
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
  $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$card = array();

//lucas 26092023 ID 576 Modificado query para os novos status
$sql = "SELECT
        SUM(CASE WHEN demanda.statusDemanda = 1 THEN 1 ELSE 0 END) AS totalAbertas,
        SUM(CASE WHEN demanda.statusDemanda = 2 THEN 1 ELSE 0 END) AS totalExecucao,
        SUM(CASE WHEN demanda.statusDemanda = 3 THEN 1 ELSE 0 END) AS totalEntregue,
        SUM(CASE WHEN demanda.statusDemanda = 0 THEN 1 ELSE 0 END) AS totalFechado,
        COUNT(demanda.idDemanda) AS totalDemandas
        FROM demanda";

//echo "-SQL->".json_encode($sql)."\n";
$rows = 0;

//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 3) {
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
    array_push($card, $row);
    $rows = $rows + 1;
  }

  $jsonSaida = $card[0];
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
  if ($LOG_NIVEL >= 3) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG