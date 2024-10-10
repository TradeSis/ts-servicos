<?php
//lucas 22112023 ID 688 Melhorias em Demandas 
//lucas 22092023 ID 358 Demandas/Comentarios 
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "comentario_inserir";
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

date_default_timezone_set('America/Sao_Paulo');
$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
if (isset($jsonEntrada['idDemanda'])) {
    $idDemanda = $jsonEntrada['idDemanda'];
    $comentario = $jsonEntrada['comentario'];
    $idUsuario = $jsonEntrada['idUsuario'];
    $interno = $jsonEntrada['interno'];
    
    $enviaEmailComentario  = isset($jsonEntrada['enviaEmailComentario'])  && $jsonEntrada['enviaEmailComentario'] !== "" && $jsonEntrada['enviaEmailComentario'] !== "null" ? "'". $jsonEntrada['enviaEmailComentario']."'"  : "''";

	//Gabriel 29052024 select unico para melhora de performance, busca demanda e dados do usuario
    //Busca dados Demanda e Usuario
    $sql_demanda = "SELECT demanda.tituloDemanda, demanda.idContratoTipo, demanda.idSolicitante, demanda.idAtendente, cliente.nomeCliente, tipostatus.nomeTipoStatus, servicos.nomeServico, contrato.tituloContrato,
                           UsuarioC.nomeUsuario AS nomeUsuarioC, UsuarioC.email AS emailUsuarioC,
                           atendente.nomeUsuario AS nomeAtendente, atendente.email AS emailAtendente,
                           solicitante.nomeUsuario AS nomeSolicitante, solicitante.email AS emailSolicitante FROM demanda
                    LEFT JOIN cliente ON demanda.idCliente = cliente.idCliente
                    LEFT JOIN contrato ON demanda.idContrato = contrato.idContrato
                    LEFT JOIN servicos ON demanda.idServico = servicos.idServico
                    LEFT JOIN tipostatus ON demanda.idTipoStatus = tipostatus.idTipoStatus
                    LEFT JOIN usuario AS UsuarioC ON $idUsuario = UsuarioC.idUsuario
                    LEFT JOIN usuario AS atendente ON demanda.idAtendente = atendente.idUsuario
                    LEFT JOIN usuario AS solicitante ON demanda.idSolicitante = solicitante.idUsuario
                    WHERE idDemanda = $idDemanda";
    $buscar_demanda = mysqli_query($conexao, $sql_demanda);
    $row_demanda = mysqli_fetch_array($buscar_demanda, MYSQLI_ASSOC);
    $tituloDemanda = $row_demanda["tituloDemanda"];
    $tituloContrato = $row_demanda["tituloContrato"];
    $idContratoTipo = $row_demanda["idContratoTipo"];
    $idSolicitante = $row_demanda['idSolicitante'];
    $idAtendente = $row_demanda['idAtendente'];
    $nomeCliente = $row_demanda['nomeCliente'];
    $nomeServico = $row_demanda['nomeServico'];
    $nomeTipoStatus = $row_demanda['nomeTipoStatus'];
    $nomeUsuario = $row_demanda["nomeUsuarioC"];
    $emailUsuario = $row_demanda["emailUsuarioC"];
    $nomeAtendente = $row_demanda["nomeAtendente"];
    $emailAtendente = $row_demanda["emailAtendente"];
    $nomeSolicitante = $row_demanda["nomeSolicitante"];
    $emailSolicitante = $row_demanda["emailSolicitante"];
    $dataComentario = date('H:i d/m/Y');

    //Gabriel 28052024 removido $anexos pois nao esta sendo enviado do database
    $sql = "INSERT INTO comentario(idDemanda, comentario, idUsuario, dataComentario, interno) VALUES ($idDemanda,'$comentario',$idUsuario,CURRENT_TIMESTAMP(), $interno)";

    
    //Envio de Email
	//Gabriel 29052024 novo modelo email
    $tituloEmail = '[' . $nomeCliente . '/Comentario ' . $nomeUsuario . '] ' . $tituloContrato . '/ ' . $idDemanda .' ' . $tituloDemanda;
    $corpoEmail = "
    <html>
    <head>
        <title>$tituloEmail</title>
    </head>
    <body>
        <p>Solicitante : $nomeSolicitante<br>
           Servico     : $nomeServico<br>
           Status      : $nomeTipoStatus</p>
    
        <p>$nomeUsuario $dataComentario adicionou comentario:</p>
    
        <p>$comentario</p>
    
        <p><a href='https://meucontrole.pro/servicos/'>https://meucontrole.pro/servicos/</a></p>
    </body>
    </html>";

    $arrayPara = array(
        array(
            'email' => 'tradesis@tradesis.com.br',
            'nome' => 'TradeSis'
        ),
        array(
            'email' => $emailUsuario,
            'nome' => $nomeUsuario
        )
    );
    
    if ($idUsuario !== $idAtendente) {
        $arrayPara[] = array(
            'email' => $emailAtendente,
            'nome' => $nomeAtendente
        );
    }
    
    if ($interno == "0") {
        if ($idUsuario !== $idSolicitante) {
            $arrayPara[] = array(
                'email' => $emailSolicitante,
                'nome' => $nomeSolicitante
            );
        }
    }
    //gabriel 03062024 id 999 adicionado $idEmpresa
    $envio = emailEnviar(null, null,$arrayPara,$tituloEmail,$corpoEmail,$idEmpresa,null);


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

        if (!$atualizar )
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
        "retorno" => "Faltaram parâmetros"
    );
}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG