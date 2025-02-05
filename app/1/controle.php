<?php
// lucas 22112023 id 688 - Melhoria em Demandas
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat
// Lucas 05042023 - adicionado aplicativo, menu, menuPrograma e montaMenu
// gabriel 200323 11:04 - demanda/retornar
// Lucas 03032023 - usuario alterar
// Helio 16022023 - contrato/totais
// Lucas 06022023 - adicionado contratoStatus (GET)
// helio 31012023 - melhorar testes de metodos, incluido clientes_inserir (POST)
// helio 26012023 18:10

//echo "metodo=".$metodo."\n";
//echo "funcao=".$funcao."\n";
//echo "parametro=".$parametro."\n";

if ($metodo == "GET") {

  if ($funcao == "contrato" && $parametro == "totais") {
    $funcao = "contrato/totais";
    $parametro = null;
  }

  if ($funcao == "demandas" && $parametro == "totais") {
    $funcao = "demandas/totais";
    $parametro = null;
  }

  if ($funcao == "tarefas" && $parametro == "grafico1") {
    $funcao = "tarefas/grafico1";
    $parametro = null;
  }

  if ($funcao == "tarefas" && $parametro == "grafico2") {
    $funcao = "tarefas/grafico2";
    $parametro = null;
  }

  if ($funcao == "tarefas" && $parametro == "grafico3") {
    $funcao = "tarefas/grafico3";
    $parametro = null;
  }

  if ($funcao == "tarefas" && $parametro == "grafico4") {
    $funcao = "tarefas/grafico4";
    $parametro = null;
  }

  switch ($funcao) {
    case "contratostatus":
      include 'contratostatus.php';
      break;

    case "tipostatus":
      include 'tipostatus.php';
      break;

    case "tipoocorrencia":
      include 'tipoocorrencia.php';
      break;

    case "contrato":

      include 'contrato.php';
      break;

    case "demanda":
      include 'demanda.php';
      break;

    case "usuario":
      include 'usuario.php';
      break;

    case "tarefas":
      include 'tarefas.php';
      break;

    case "comentario":
      include 'comentario.php';
      break;

    case "demanda_dashboard":
      include 'demanda_dashboard.php';
      break;

    //Gabriel 26092023 ID 575 adicionado get mensagens e chat
    case "mensagem":
      include 'mensagem.php';
      break;
      
    case "chat":
      include 'chat.php';
      break;

    case "horas":
      include 'tarefas_horas.php';
      break;

    case "contrato/totais":
      include 'contrato_totais.php';
      break;

    case "demandas/totais":
      include 'demandas_totais.php';
      break;

    case "usuario/verifica":
      include 'usuario_verifica.php';
      break;

    case "tarefas/grafico1":
      include 'tarefas_grafico1.php';
      break;

    case "tarefas/grafico2":
      include 'tarefas_grafico2.php';
      break;

    case "tarefas/grafico3":
      include 'tarefas_grafico3.php';
      break;

    case "tarefas/grafico4":
      include 'tarefas_grafico4.php';
      break;

    case "contratotipos":
      include 'contratotipos.php';
      break;

    case "demanda_horasCobrado":
      include 'demanda_horasCobrado.php';
      break;

    case "demanda_horasReal":
      include 'demanda_horasReal.php';
      break;

      case "visaocli":
        include 'visaocli.php';
        break;

    case "orcamento":
      include 'orcamento.php';
      break;
    
    case "orcamentostatus":
      include 'orcamentostatus.php';
      break;

    case "orcamentoitens":
      include 'orcamentoitens.php';
      break;
      
    case "contratochecklist":
      include 'contratochecklist.php';
      break;

    /* gabriel 20240131 - id1600 troquei para tempoatendimento pois demanda_dashboard estava duplicado */ 
    case "tempoatendimento":
      include 'tempoatendimento.php';
      break;

    case "tempoocorrencia":
      include 'tempoatendimento_totais.php';
      break;
    
    case"demandatabela_dashboard":
      include 'demandatabela_dashboard.php';
      break;

    case "demandachecklist":
      include 'demandachecklist.php';
      break;

    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}

if ($metodo == "PUT") {
  // lucas 22112023 id 688 - removido $função de comentario atendente e comentario cliente
  if ($funcao == "demanda" && $parametro == "validar") {
    $funcao = "demanda/validar";
    $parametro = null;
  }
  if ($funcao == "demanda" && $parametro == "retornar") {
    $funcao = "demanda/retornar";
    $parametro = null;
  }
  if ($funcao == "tarefas" && $parametro == "novostart") {
    $funcao = "tarefas/novostart";
    $parametro = null;
  }
  //Gabriel 26092023 ID 575 inserir demanda via chat
  if ($funcao == "demanda" && $parametro == "chat") {
    $funcao = "demanda/chat";
    $parametro = null;
  }


  switch ($funcao) {
    case "contratostatus":
      include 'contratostatus_inserir.php';
      break;

    case "tipostatus":
      include 'tipostatus_inserir.php';
      break;

    case "tipoocorrencia":
      include 'tipoocorrencia_inserir.php';
      break;

    case "demanda":
      include 'demanda_inserir.php';
      break;

    case "tarefas":
      include 'tarefas_inserir.php';
      break;

    case "contrato":
      include 'contrato_inserir.php';
      break;

    case "previsao":
      include 'previsao_inserir.php';
      break;
// lucas 22112023 id 688 - removido comentarios atendente/cliente ficando apenas comentario_inserir
    case "comentario":
      include 'comentario_inserir.php';
      break;

    case "demanda/validar":
      include 'demanda_validar.php';
      break;

    case "demanda/retornar":
      include 'demanda_retornar.php';
      break;


    case "contratotipos":
      include 'contratotipos_inserir.php';
      break;

        case "tarefas/novostart":
          include 'tarefas_novostart.php';
          break;
    //Gabriel 26092023 ID 575 inserir demanda via chat
    case "demanda/chat":
      include 'demanda_inserirchat.php';
      break;      
          
    //Gabriel 26092023 ID 575 inserir mensagens e chat
    case "mensagem":
      include 'mensagem_inserir.php';
      break;

    case "chat":
      include 'chat_inserir.php';
      break;

    case "orcamento":
      include 'orcamento_inserir.php';
      break;
      
    case "orcamentostatus":
      include 'orcamentostatus_inserir.php';
      break;

    case "orcamentoitens":
      include 'orcamentoitens_inserir.php';

    case "contratochecklist":
      include 'contratochecklist_inserir.php';
      break;

    case "demandachecklist":
      include 'demandachecklist_inserir.php';
      break;
     
    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}

if ($metodo == "POST") {

  if ($funcao == "contrato" && $parametro == "finalizar") {
    $funcao = "contrato/finalizar";
    $parametro = null;
  }

  if ($funcao == "demanda" && $parametro == "atualizar") {
    $funcao = "demanda/atualizar";
    $parametro = null;
  }

  if ($funcao == "orcamento" && $parametro == "atualizar") {
    $funcao = "orcamento/atualizar";
    $parametro = null;
  }

  if ($funcao == "tarefas" && $parametro == "stop") {
    $funcao = "tarefas/stop";
    $parametro = null;
  }

  if ($funcao == "tarefas" && $parametro == "realizado") {
    $funcao = "tarefas/realizado";
    $parametro = null;
  }

  if ($funcao == "tarefas" && $parametro == "start") {
    $funcao = "tarefas/start";
    $parametro =
     null;
  }
  if ($funcao == "contratochecklist" && $parametro == "tarefa") {
    $funcao = "contratochecklist/tarefa";
    $parametro = null;
  }
  
  if ($funcao == "orcamento" && $parametro == "contrato") {
    $funcao = "orcamento/contrato";
    $parametro = null;
  }




  switch ($funcao) {
    case "contratostatus":
      include 'contratostatus_alterar.php';
      break;

    case "tipostatus":
      include 'tipostatus_alterar.php';
      break;

    case "tipoocorrencia":
      include 'tipoocorrencia_alterar.php';
      break;

    case "demanda":
      include 'demanda_alterar.php';
      break;

    case "demanda/atualizar":
      include 'demanda_atualizar.php';
      break;

    case "orcamento/atualizar":
      include 'orcamento_atualizar.php';
      break;

    case "contrato":
      include 'contrato_alterar.php';
      break;

    case "tarefas":
      include 'tarefas_alterar.php';
      break;

    case "usuario/ativar":
      include 'usuario_ativar.php';
      break;

    case "tarefas/stop":
      include 'tarefas_stop.php';
      break;

    case "tarefas/realizado":
      include 'tarefas_realizado.php';
      break;

    case "tarefas/start":
      include 'tarefas_start.php';
      break;

    case "previsao":
      include 'previsao_alterar.php';
      break;

    case "contratotipos":
      include 'contratotipos_alterar.php';
      break;

    case "demanda_descricao":
      include 'demanda_descricao.php';
      break;

    case "visaocli":
      include 'visaocli_atualizar.php';
      break;

    case "orcamento":
      include 'orcamento_alterar.php';
      break;
      
    case "orcamentostatus":
      include 'orcamentostatus_alterar.php';
      break;

    case "orcamentoitens":
      include 'orcamentoitens_alterar.php';
      break;

    case "orcamento/contrato":
      include 'orcamento_gerarcontrato.php';
      
    case "contratochecklist":
      include 'contratochecklist_alterar.php';
      break;

    case "contratochecklist/tarefa":
      include 'contratochecklist_tarefa.php';
      break;

    case "demandachecklist":
        include 'demandachecklist_alterar.php';
        break;

    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}

if ($metodo == "DELETE") {
  switch ($funcao) {
    case "contratostatus":
      include 'contratostatus_excluir.php';
      break;

    case "tipostatus":
      include 'tipostatus_excluir.php';
      break;
    case "tipoocorrencia":
      include 'tipoocorrencia_excluir.php';
      break;

    case "contrato":
      include 'contrato_excluir.php';
      break;

    case "contratotipos":
      include 'contratotipos_excluir.php';
      break;

    case "orcamentostatus":
      include 'orcamentostatus_excluir.php';
      break;
    
    case "orcamentoitens":
      include 'orcamentoitens_excluir.php';
      
    case "contratochecklist":
      include 'contratochecklist_excluir.php';
      break;

    case "demandachecklist":
      include 'demandachecklist_excluir.php';
      break;

    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}