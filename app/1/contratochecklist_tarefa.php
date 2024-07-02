<?php
// Lucas 07022023 criacao
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "contratochecklist_tarefa";
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
if (isset($jsonEntrada['idChecklist'])) {
    $idChecklist = $jsonEntrada['idChecklist'];
    $idContrato = $jsonEntrada['idContrato'];
    $idSolicitante = $jsonEntrada['idSolicitante'];

    //busca dados checklist
    $sql = "SELECT contratochecklist.*, contrato.idCliente, contrato.idContratoTipo FROM contratochecklist, contrato WHERE contratochecklist.idChecklist = $idChecklist AND contratochecklist.idContrato = $idContrato AND contrato.idContrato = $idContrato";
    $buscar = mysqli_query($conexao, $sql);
    $dados = mysqli_fetch_array($buscar, MYSQLI_ASSOC);


    $apiEntrada = array(
        'idEmpresa' => $idEmpresa,
        'idCliente' => $dados['idCliente'],
        'idSolicitante' => $idSolicitante,
        'tituloDemanda' => $dados['descricao'],
        'idContrato' => $idContrato,
        'idContratoTipo' => $dados['idContratoTipo'],
    );  
    echo json_encode($apiEntrada);
    $demanda = chamaAPI(null, '/services/demanda', json_encode($apiEntrada), 'PUT');
    
    $sql = "DELETE FROM contratochecklist WHERE idChecklist = $idChecklist AND idContrato = $idContrato";


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
