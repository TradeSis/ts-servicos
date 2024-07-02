<?php
// lucas 28112023 id706 - Melhorias Demandas 2
//Gabriel 05102023 ID 575 Demandas/Comentarios - Layout de chat
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_inserir";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "servicos_inserir" . date("dmY") . ".log", "a");
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


date_default_timezone_set('America/Sao_Paulo');
$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
$posicao = null;
$statusDemanda = null;

if (isset($jsonEntrada['tituloDemanda'])) {
    $tituloDemanda = "'" . $jsonEntrada['tituloDemanda'] . "'";
    $descricao  = isset($jsonEntrada['descricao'])  && $jsonEntrada['descricao'] !== "" && $jsonEntrada['descricao'] !== "null" ? "'". $jsonEntrada['descricao']."'"  : "''";
    $horasPrevisao  = isset($jsonEntrada['horasPrevisao'])  && $jsonEntrada['horasPrevisao'] !== "" && $jsonEntrada['horasPrevisao'] !== "null" ? "'". $jsonEntrada['horasPrevisao']."'"  : "0";
    $idSolicitante = isset($jsonEntrada['idSolicitante'])  && $jsonEntrada['idSolicitante'] !== "" ?  $jsonEntrada['idSolicitante']    : "null";
    $idAtendente = isset($jsonEntrada['idAtendente'])  && $jsonEntrada['idAtendente'] !== "" ?  $jsonEntrada['idAtendente']    : "null";
    $idCliente = isset($jsonEntrada['idCliente'])  && $jsonEntrada['idCliente'] !== "" ?  $jsonEntrada['idCliente']    : "null";
    $dataPrevisaoEntrega  = isset($jsonEntrada['dataPrevisaoEntrega'])  && $jsonEntrada['dataPrevisaoEntrega'] !== "" && $jsonEntrada['dataPrevisaoEntrega'] !== "null" ? "'". $jsonEntrada['dataPrevisaoEntrega']."'"  : "null";
    $dataPrevisaoInicio  = isset($jsonEntrada['dataPrevisaoInicio'])  && $jsonEntrada['dataPrevisaoInicio'] !== "" && $jsonEntrada['dataPrevisaoInicio'] !== "null" ? "'". $jsonEntrada['dataPrevisaoInicio']."'"  : "null";
    $tempoCobrado = isset($jsonEntrada["tempoCobrado"])  && $jsonEntrada["tempoCobrado"] !== "" && $jsonEntrada["tempoCobrado"] !== "null" ? "'". $jsonEntrada["tempoCobrado"]."'"  : "null";
    $tempoCobradoDigitado = '0';
    if($tempoCobrado !== "null"){
        $tempoCobradoDigitado = '1';
    }

    // lucas 28112023 id706 - removido idTipoOcorrencia
    $idContratoTipo   = isset($jsonEntrada['idContratoTipo'])  && $jsonEntrada['idContratoTipo'] !== "" ?  "'" . $jsonEntrada['idContratoTipo'] . "'"  : "null";
        //Verifica o Tipo de Contrato
        $sql_consulta = "SELECT * FROM contratotipos WHERE idContratoTipo = $idContratoTipo";
        $buscar_consulta = mysqli_query($conexao, $sql_consulta);
        $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);

        $idServicoPadrao = isset($row_consulta['idServicoPadrao'])  && $row_consulta['idServicoPadrao'] !== "" ?  $row_consulta['idServicoPadrao']    : "null";
        $idTipoStatus_fila = isset($row_consulta['idTipoStatus_fila'])  && $row_consulta['idTipoStatus_fila'] !== "" ?  $row_consulta['idTipoStatus_fila']    : "null";
        $idUsuarioPadrao = isset($row_consulta['idUsuarioPadrao'])  && $row_consulta['idUsuarioPadrao'] !== "" ?  $row_consulta['idUsuarioPadrao']    : "null";

        $idServico  = isset($jsonEntrada['idServico'])  && $jsonEntrada['idServico'] !== "" ?  $jsonEntrada['idServico']    : "null";
        if($idServico === "null"){
            $idServico = $idServicoPadrao;
        }
 
        $idTipoStatus = $idTipoStatus_fila;
        
        if ( $idAtendente === "null") {
            $idAtendente = $idUsuarioPadrao;
        }
        


    $idContrato = isset($jsonEntrada['idContrato'])  && $jsonEntrada['idContrato'] !== "" ?  $jsonEntrada['idContrato']    : "null";

    if($idContrato !== "null"){
        //Pega o campo idCliente de contrato
        $sql_consulta = "SELECT * FROM contrato WHERE idContrato = $idContrato";
        $buscar_consulta = mysqli_query($conexao, $sql_consulta);
        $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
        $idCliente = isset($row_consulta['idCliente'])  && $row_consulta['idCliente'] !== "" ?  $row_consulta['idCliente']    : "null";
    }


    //busca dados tipostatus    
    $sql2 = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
    $buscar2 = mysqli_query($conexao, $sql2);
    $row = mysqli_fetch_array($buscar2, MYSQLI_ASSOC);
    $posicao = $row["mudaPosicaoPara"];
    $statusDemanda = $row["mudaStatusPara"];

    $sql = "INSERT INTO demanda(prioridade, tituloDemanda, descricao, dataAbertura, idTipoStatus, posicao, statusDemanda, idCliente, idSolicitante, idServico, idContrato, 
    idContratoTipo, horasPrevisao, idAtendente, dataPrevisaoEntrega, dataPrevisaoInicio, tempoCobrado, tempoCobradoDigitado)
     VALUES (99, $tituloDemanda, $descricao, CURRENT_TIMESTAMP(), $idTipoStatus, $posicao, $statusDemanda, $idCliente, $idSolicitante, $idServico, $idContrato, 
     $idContratoTipo, $horasPrevisao, $idAtendente, $dataPrevisaoEntrega, $dataPrevisaoInicio, $tempoCobrado, $tempoCobradoDigitado)";
    

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
        //Gabriel 05102023 ID 575 adicionado idDemanda adicionada
        $idInserido = mysqli_insert_id($conexao);

		//Gabriel 29052024 busca dados da demanda para envio do email novo modelo
        $sql_demanda = "SELECT demanda.tituloDemanda, demanda.descricao, demanda.idSolicitante, demanda.idAtendente, cliente.nomeCliente, 
                               tipostatus.nomeTipoStatus, servicos.nomeServico, contrato.tituloContrato, 
                               solicitante.nomeUsuario AS nomeSolicitante, solicitante.email AS emailSolicitante                              FROM demanda
                    LEFT JOIN cliente ON demanda.idCliente = cliente.idCliente
                    LEFT JOIN contrato ON demanda.idContrato = contrato.idContrato
                    LEFT JOIN servicos ON demanda.idServico = servicos.idServico
                    LEFT JOIN tipostatus ON demanda.idTipoStatus = tipostatus.idTipoStatus
                    LEFT JOIN usuario AS solicitante ON demanda.idSolicitante = solicitante.idUsuario
                    WHERE idDemanda = $idInserido";
        $buscar_demanda = mysqli_query($conexao, $sql_demanda);
        $row_demanda = mysqli_fetch_array($buscar_demanda, MYSQLI_ASSOC);
        $tituloDemanda = $row_demanda["tituloDemanda"];
        $tituloContrato = $row_demanda["tituloContrato"];
        $descricao = $row_demanda["descricao"];
        $nomeCliente = $row_demanda['nomeCliente'];
        $nomeServico = $row_demanda['nomeServico'];
        $nomeTipoStatus = $row_demanda['nomeTipoStatus'];
        $nomeSolicitante = $row_demanda["nomeSolicitante"];
        $emailSolicitante = $row_demanda["emailSolicitante"];

            //Envio de Email
            $tituloEmail = '[' . $nomeCliente . '/Abertura] ' . $tituloContrato . '/ ' . $idInserido .' ' . $tituloDemanda;
            $corpoEmail = "
            <html>
            <head>
                <title>$tituloEmail</title>
            </head>
            <body>
                <p>Solicitante : $nomeSolicitante<br>
                Servico     : $nomeServico<br>
                Status      : $nomeTipoStatus</p>
            
                <p>$descricao</p>
            
                <p><a href='https://meucontrole.pro/servicos/'>https://meucontrole.pro/servicos/</a></p>
            </body>
            </html>";

            $arrayPara = array(

                array(
                    'email' => 'tradesis@tradesis.com.br',
                    'nome' => 'TradeSis'
                ),
                array(
                    'email' => $emailSolicitante,
                    'nome' => $nomeSolicitante
                ),
            );

            //gabriel 03062024 id 999 adicionado $idEmpresa
            $envio = emailEnviar(null,null,$arrayPara,$tituloEmail,$corpoEmail,$idEmpresa,1);
            
    
        $jsonSaida = array(
            "status" => 200,
            "retorno" => "ok",
            "idInserido" => $idInserido  
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
//echo "-SAIDA->".json_encode($jsonSaida)."\n";
//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
