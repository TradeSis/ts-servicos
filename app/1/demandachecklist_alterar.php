<?php
// Lucas 07022023 criacao
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demandachecklist_alterar";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "servicos_ALTERAR_" . date("dmY") . ".log", "a");
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
    $idDemanda = $jsonEntrada['idDemanda'];

    //busca dados checklist
    $sql = "SELECT * FROM demandachecklist WHERE idChecklist = $idChecklist AND idDemanda = $idDemanda";
    $buscar = mysqli_query($conexao, $sql);
    $dados = mysqli_fetch_array($buscar, MYSQLI_ASSOC);

    $titulo  = isset($jsonEntrada['titulo']) && $jsonEntrada['titulo'] !== "null" ? "'". $jsonEntrada['titulo']."'"  : "'". $dados['titulo']."'";
    $descricao  = isset($jsonEntrada['descricao'])  && $jsonEntrada['descricao'] !== "" && $jsonEntrada['descricao'] !== "null" ? "'". $jsonEntrada['descricao']."'"  : "'". $dados['descricao']."'";
    $ordem  = isset($jsonEntrada['ordem']) && $jsonEntrada['ordem'] !== "null" ? "'". $jsonEntrada['ordem']."'"  : "'". $dados['ordem']."'";
    $statusCheck = isset($jsonEntrada['statusCheck'])  && $jsonEntrada['statusCheck'] !== "" ?  $jsonEntrada['statusCheck']    : "'". $dados['statusCheck']."'";

    $sql = "UPDATE demandachecklist SET descricao=$descricao, titulo=$titulo, ordem=$ordem, statusCheck=$statusCheck WHERE idChecklist = $idChecklist AND idDemanda = $idDemanda";
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
