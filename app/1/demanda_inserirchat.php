<?php
//Gabriel 05102023 ID 575 Demandas/Comentarios - Layout de chat
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_chat";
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

if (isset($jsonEntrada['idDemanda'])) {
    $INidUsuario = $jsonEntrada['INidUsuario'];
    $OUTidUsuario = $jsonEntrada['OUTidUsuario'];
    $idDemanda = $jsonEntrada['idDemanda'];

    $sql2 = "SELECT chat.*, CASE WHEN chat.INidUsuario = " . $INidUsuario . " THEN 1 ELSE 0 END AS status FROM chat  
            INNER JOIN usuario AS INidUsuario on chat.INidUsuario = INidUsuario.idUsuario 
            LEFT JOIN usuario AS OUTidUsuario on chat.OUTidUsuario = OUTidUsuario.idUsuario 
            where (chat.INidUsuario = " . $INidUsuario . " AND chat.OUTidUsuario = " . $OUTidUsuario . ")
            OR (chat.INidUsuario = " . $OUTidUsuario . " AND chat.OUTidUsuario = " . $INidUsuario . ")";
    $buscar2 = mysqli_query($conexao, $sql2);

    $sqlLinha = [];

    while ($row = mysqli_fetch_array($buscar2, MYSQLI_ASSOC)) {
        $chatID = $row["chatID"];
        $mensagem = $row["chat"];
        $idUsuario = $row["INidUsuario"];
        $dataMensagem = $row["dataMensagem"];

        $sql = "INSERT INTO mensagem(idDemanda, mensagem, idUsuario, dataMensagem) 
        VALUES ($idDemanda, '$mensagem', $idUsuario, '$dataMensagem')";
        $sql2 = "DELETE FROM chat WHERE chatID = $chatID";
        $sqlLinha[] = $sql;
        $sqlLinha[] = $sql2;

    }
    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 3) {
            $sqlLinhaString = implode("\n", $sqlLinha);
            fwrite($arquivo, $identificacao . "-SQL->" . $sqlLinhaString . "\n");
            fwrite($arquivo, $identificacao . "-SQL->" . $sql2 . "\n");
        }
    }
    //LOG

    //TRY-CATCH
    try {
        foreach ($sqlLinha as $sql) {
        $atualizar = mysqli_query($conexao, $sql);
        }
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
echo "-SAIDA->" . json_encode($jsonSaida) . "\n";
//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
