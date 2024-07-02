<?php
// Lucas 07022023 criacao

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "contrato_totais";
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

$sql = "SELECT
        SUM(CASE WHEN contrato.statusContrato = 2 THEN 1 ELSE 0 END) AS totalOrcamento,
        SUM(CASE WHEN contrato.statusContrato = 2 THEN contrato.valorContrato ELSE 0 END) AS valorOrcamento,
        SUM(CASE WHEN contrato.idContratoStatus = 3 THEN 1 ELSE 0 END) AS totalDesenvolvimento,
        SUM(CASE WHEN contrato.idContratoStatus = 3 THEN contrato.valorContrato ELSE 0 END) AS valorDesenvolvimento,
        SUM(CASE WHEN contrato.idContratoStatus = 4 THEN 1 ELSE 0 END) AS totalFaturamento,
        SUM(CASE WHEN contrato.idContratoStatus = 4 THEN contrato.valorContrato ELSE 0 END) AS valorFaturamento,
        SUM(CASE WHEN contrato.idContratoStatus = 5 THEN 1 ELSE 0 END) AS totalRecebimento,
        SUM(CASE WHEN contrato.idContratoStatus = 5 THEN contrato.valorContrato ELSE 0 END) AS valorRecebimento,
        SUM(CASE WHEN contrato.statusContrato = 1 THEN 1 ELSE 0 END) AS totalAtivo,
        SUM(CASE WHEN contrato.statusContrato = 1 THEN contrato.valorContrato ELSE 0 END) AS valorAtivo,
        SUM(CASE WHEN contrato.statusContrato = 0 THEN 1 ELSE 0 END) AS totalEncerrados,
        SUM(CASE WHEN contrato.statusContrato = 0 THEN contrato.valorContrato ELSE 0 END) AS valorEncerrados FROM contrato 
        INNER JOIN contratotipos  on  contrato.idContratoTipo = contratotipos.idContratoTipo ";
if (isset($jsonEntrada["idContratoTipo"])) {
  $sql = $sql . " where contratotipos.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
} 



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
