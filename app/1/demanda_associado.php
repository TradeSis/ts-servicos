<?php
//gabriel 20250210
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_associado";
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
if (isset($jsonEntrada['idDemanda'])) {

    $idDemanda = $jsonEntrada['idDemanda'];
    $idAssociado = $jsonEntrada['idAssociado'];
    $acao = $jsonEntrada['acao'];

    $sql2 = "SELECT demanda.associados FROM demanda WHERE idDemanda = $idDemanda";
    $buscar2 = mysqli_query($conexao, $sql2);
    $row = mysqli_fetch_array($buscar2, MYSQLI_ASSOC);

    if (isset($row['associados']) && $row['associados'] !== "" && $row['associados'] !== "null") {
        $associadosArray = explode(',', trim($row['associados'], "'"));
        if ($acao == "associar" && !in_array($idAssociado, $associadosArray)) {
            $associadosArray[] = $idAssociado;
        } elseif ($acao == "desassociar") {
            $key = array_search($idAssociado, $associadosArray);
            if ($key !== false) {
                unset($associadosArray[$key]);
            }
        }
        $associados = "'" . implode(',', $associadosArray) . "'";
    } else {
        if ($acao == "associar") {
            $associados = "'" . $idAssociado . "'";
        } else { 
            $associados = "''";
        }
    }
    
    $sql = "UPDATE demanda SET associados = $associados WHERE idDemanda = $idDemanda";


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