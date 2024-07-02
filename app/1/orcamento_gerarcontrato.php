<?php
// Gabriel 26022024 criacao
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "orcamento_gerarContrato";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "orcamento_" . date("dmY") . ".log", "a");
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
if (isset($jsonEntrada['idOrcamento'])) {
    $idOrcamento = $jsonEntrada['idOrcamento'];

    $sql_consulta = "SELECT * FROM orcamento WHERE idOrcamento = $idOrcamento";
    $buscar_consulta = mysqli_query($conexao, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
    $tituloOrcamento = isset($row_consulta['tituloOrcamento']) && $row_consulta['tituloOrcamento'] !== "" ? "'" . $row_consulta['tituloOrcamento'] . "'" : "NULL";
    $descricao = isset($row_consulta['descricao']) && $row_consulta['descricao'] !== "" ? "'" . $row_consulta['descricao'] . "'" : "NULL";
    $dataAbertura = isset($row_consulta['dataAbertura']) && $row_consulta['dataAbertura'] !== "" && $row_consulta['dataAbertura'] !== "NULL" ? "'" . $row_consulta['dataAbertura'] . "'" : "NULL";
    $idCliente = isset($row_consulta['idCliente']) && $row_consulta['idCliente'] !== "" ?  $row_consulta['idCliente']  : "NULL";
    $dataAprovacao = isset($row_consulta['dataAprovacao']) && $row_consulta['dataAprovacao'] !== "" && $row_consulta['dataAprovacao'] !== "NULL" ? "'" . $row_consulta['dataAprovacao'] . "'" : "NULL";
    $horas = isset($row_consulta['horas']) && $row_consulta['horas'] !== "" ? "'" . $row_consulta['horas'] . "'" : "NULL";
    $valorHora = isset($row_consulta['valorHora']) && $row_consulta['valorHora'] !== "" ? "'" . $row_consulta['valorHora'] . "'" : "NULL";
    $valorOrcamento = isset($row_consulta['valorOrcamento']) && $row_consulta['valorOrcamento'] !== "" ? "'" . $row_consulta['valorOrcamento'] . "'" : "NULL";
    $statusOrcamento = isset($row_consulta['statusOrcamento']) && $row_consulta['statusOrcamento'] !== "" ? "'" . $row_consulta['statusOrcamento'] . "'" : "NULL";
    $idSolicitante = isset($row_consulta['idSolicitante']) && $row_consulta['idSolicitante'] !== "" ? $row_consulta['idSolicitante'] : "NULL";

    $idContratoStatus = CONTRATOSTATUS_DESENVOLVIMENTO; 
    $idContratoTipo = "'" . $jsonEntrada['idContratoTipo'] . "'";

    $idContratoTipo   = isset($jsonEntrada['idContratoTipo'])  && $jsonEntrada['idContratoTipo'] !== "" ?  "'" . $jsonEntrada['idContratoTipo'] . "'"  : "null";
        //Verifica o Tipo de Contrato
        $sql_consulta = "SELECT * FROM contratotipos WHERE idContratoTipo = $idContratoTipo";
        $buscar_consulta = mysqli_query($conexao, $sql_consulta);
        $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);

        $idServico = isset($row_consulta['idServicoPadrao'])  && $row_consulta['idServicoPadrao'] !== "" ?  $row_consulta['idServicoPadrao']    : "null";

        //busca dados contratostatus    
        $sql2 = "SELECT * FROM contratostatus WHERE idContratoStatus = $idContratoStatus";
        $buscar2 = mysqli_query($conexao, $sql2);
        $row = mysqli_fetch_array($buscar2, MYSQLI_ASSOC);
        $statusContrato = $row["mudaStatusPara"];


    $sql2 = "INSERT INTO contrato (tituloContrato, descricao, dataAbertura, idContratoStatus, dataPrevisao, dataEntrega, idCliente, statusContrato, horas, valorHora, valorContrato, idContratoTipo, idServico) 
                            values ($tituloOrcamento, $descricao, $dataAbertura, $idContratoStatus, '0000-00-00', '0000-00-00', $idCliente, $statusContrato, $horas, $valorHora, $valorOrcamento, $idContratoTipo, $idServico)";

    $atualizar2 = mysqli_query($conexao, $sql2);

    $idContratoInserido = mysqli_insert_id($conexao);

    $sql_consulta2 = "SELECT * FROM orcamentoitens WHERE idOrcamento = $idOrcamento";
    $buscar_consulta2 = mysqli_query($conexao, $sql_consulta2);
    while ($row_consulta2 = mysqli_fetch_array($buscar_consulta2, MYSQLI_ASSOC)) {
        $idItemOrcamento = isset($row_consulta2['idItemOrcamento']) && $row_consulta2['idItemOrcamento'] !== "" ? $row_consulta2['idItemOrcamento'] : "null";
        $tituloItemOrcamento = isset($row_consulta2['tituloItemOrcamento']) && $row_consulta2['tituloItemOrcamento'] !== "" ? $row_consulta2['tituloItemOrcamento'] : "null";

        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idContrato' => $idContratoInserido,
            'idCliente' => $idCliente,
            'idServico' => $idServico,
            'idSolicitante' => $idSolicitante,
            'idContratoTipo' => $jsonEntrada['idContratoTipo'],
            'tituloDemanda' => $row_consulta2['tituloItemOrcamento']
        );

        $demanda = chamaAPI(null, '/services/demanda', json_encode($apiEntrada), 'PUT');

        
        
    }

    $sql = "UPDATE orcamento SET idContrato=$idContratoInserido, idServico=$idServico WHERE idOrcamento = $idOrcamento";

    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 3) {
            fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
        }
    }
    //LOG

    //TRY-CATCH
    try {

        $atualizar = mysqli_query($conexao, $sql);
        if (!$atualizar)
            throw new Exception(mysqli_error($conexao));

        $jsonSaida = array(
            "status" => 200,
            "retorno" => "ok"
        );
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
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parametros"
    );
}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
