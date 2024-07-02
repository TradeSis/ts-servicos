<?php

//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "contratotipos_alterar";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "servicos_" . date("dmY") . ".log", "a");
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
if (isset($jsonEntrada['idContratoTipo'])) {
    $idContratoTipo = "'" . $jsonEntrada['idContratoTipo']. "'";
    $nomeContrato = isset($jsonEntrada['nomeContrato']) && $jsonEntrada['nomeContrato'] !== "null"    ? "'" . $jsonEntrada['nomeContrato'] . "'" : "null";
    $nomeDemanda = isset($jsonEntrada['nomeDemanda']) && $jsonEntrada['nomeDemanda'] !== "null"    ? "'" . $jsonEntrada['nomeDemanda'] . "'" : "null";
    $idTipoOcorrenciaPadrao = isset($jsonEntrada['idTipoOcorrenciaPadrao']) && $jsonEntrada['idTipoOcorrenciaPadrao'] !== "null"    ? "'" . $jsonEntrada['idTipoOcorrenciaPadrao'] . "'" : "null";
    $idTipoStatus_fila = isset($jsonEntrada['idTipoStatus_fila']) && $jsonEntrada['idTipoStatus_fila'] !== "null"    ? "'" . $jsonEntrada['idTipoStatus_fila'] . "'" : "null";
    $idServicoPadrao = isset($jsonEntrada['idServicoPadrao']) && $jsonEntrada['idServicoPadrao'] !== "null"    ? "'" . $jsonEntrada['idServicoPadrao'] . "'" : "null";

    $sql = "UPDATE contratotipos SET idContratoTipo = $idContratoTipo, nomeContrato = $nomeContrato, nomeDemanda = $nomeDemanda, idTipoOcorrenciaPadrao = $idTipoOcorrenciaPadrao,
    idTipoStatus_fila = $idTipoStatus_fila, idServicoPadrao = $idServicoPadrao WHERE idContratoTipo = $idContratoTipo";

    echo json_encode($sql);

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
