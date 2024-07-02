<?php
// Lucas 22032023 adicionado if de tituloContrato
// Lucas 21032023 ajustado estrutura dentro do else, adicionado $where;
// Lucas 20032023 adicionar if de idCliente
// Lucas 17022023 adicionado condição else para idContratoStatus
// Lucas 07022023 criacao

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "contrato";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "contrato_" . date("dmY") . ".log", "a");
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

$contrato = array();

$arrayStatus = array(
  CONTRATOSTATUS_ORCAMENTO,
  CONTRATOSTATUS_ATIVO,
  CONTRATOSTATUS_ENCERRADOS
);

$sql = "SELECT contrato.*, cliente.*, contratostatus.*, contratotipos.*, servicos.nomeServico FROM contrato				
        LEFT JOIN cliente on cliente.idCliente = contrato.idcliente 
        LEFT JOIN contratostatus  on  contrato.idContratoStatus = contratostatus.idContratoStatus
        LEFT JOIN contratotipos  on  contrato.idContratoTipo = contratotipos.idContratoTipo 
        LEFT JOIN servicos ON contrato.idServico = servicos.idServico ";
if (isset($jsonEntrada["idContrato"])) {
  $sql = $sql . " where contrato.idContrato = " . $jsonEntrada["idContrato"];
} else {
  $where = " where ";

  if (isset($jsonEntrada["idCliente"])) {
    $sql = $sql . $where . " contrato.idCliente = " . $jsonEntrada["idCliente"];
    $where = " and ";
  }

  if (isset($jsonEntrada["idContratoStatus"]) && in_array($jsonEntrada["statusContrato"], $arrayStatus)) {
    $sql = $sql . $where . " contrato.idContratoStatus = " . $jsonEntrada["idContratoStatus"];
    $where = " and ";
  } 

  if (isset($jsonEntrada["statusContrato"])) {
    if($jsonEntrada["statusContrato"] == CONTRATOSTATUS_ORCAMENTO) {
      $sql = $sql . $where . " contrato.statusContrato = 2";
    }
    if($jsonEntrada["statusContrato"] == CONTRATOSTATUS_DESENVOLVIMENTO) {
      $sql = $sql . $where . "  contrato.idContratoStatus = 3";
    }
    if($jsonEntrada["statusContrato"] == CONTRATOSTATUS_FATURAMENTO) {
      $sql = $sql . $where . "  contrato.idContratoStatus = 4";
    }
    if($jsonEntrada["statusContrato"] == CONTRATOSTATUS_RECEBIMENTO) {
      $sql = $sql . $where . "  contrato.idContratoStatus = 5";
    }
    if($jsonEntrada["statusContrato"] == CONTRATOSTATUS_ATIVO) {
      $sql = $sql . $where . " contrato.statusContrato = 1";
    }
    if($jsonEntrada["statusContrato"] == CONTRATOSTATUS_ENCERRADOS) {
      $sql = $sql . $where . " contrato.statusContrato = 0";
    }
    $where = " and ";
  }

  if (isset($jsonEntrada["buscaContrato"])) {
    $sql = $sql . $where . " contrato.idContrato= " . $jsonEntrada["buscaContrato"] . " or . contrato.tituloContrato like " . "'%" . $jsonEntrada["buscaContrato"] . "%'";
    $where = " and ";
  }

  if (isset($jsonEntrada["idContratoTipo"])) {
    $sql = $sql . $where . " contratotipos.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
    $where = " and ";
  }
}


//echo "-SQL->".$sql."\n"; 
//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 3) {
    fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
  }
}
//LOG

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($contrato, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idContrato"]) && $rows == 1) {
  $contrato = $contrato[0];
}
$jsonSaida = $contrato;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG