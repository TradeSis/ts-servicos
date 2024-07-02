<?php


include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/orcamento.php');
include_once(__DIR__ . '/../database/orcamentoStatus.php');
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/servicos.php');

$usuario = buscaUsuarios(null, $_SESSION['idLogin']);

if ($usuario["idCliente"] == null) {
  $clientes = buscaClientes($usuario["idCliente"]);
} else {
  $clientes = array(buscaClientes($usuario["idCliente"]));
}

if ($_SESSION['administradora'] == 1) {
    $idCliente = null;
} else {
    $idCliente = $usuario["idCliente"];
}

$orcamentosStatus = buscaOrcamentoStatus();
$servicos = buscaServicos();

$statusOrcamento =  null; 
if (isset($_SESSION['filtro_orcamento'])) {
    $filtroEntrada = $_SESSION['filtro_orcamento'];
    $idCliente = $filtroEntrada['idCliente'];
    $statusOrcamento = $filtroEntrada['statusOrcamento'];
}

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>



<body>
    <div class="container-fluid">

        <div class="row ">
            <!-- <BR> MENSAGENS/ALERTAS -->
        </div>

        <div class="row ">
            <!-- <BR> MENSAGENS/ALERTAS -->
        </div>

        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

            <div class="col-2 col-lg-1 order-lg-1">
               <!--  <button class="btn btn-outline-secondary ts-btnFiltros" type="button"><i class="bi bi-funnel"></i></button> -->
            </div>

            <div class="col-4 col-lg-3 order-lg-2">
                <h2 class="ts-tituloPrincipal">Orçamentos</h2>
                <span>Filtro Aplicado</span>
            </div>

            <div class="col-6 col-lg-2 order-lg-3">
                <!-- BOTÂO OPCIONAL -->
            </div>

            <div class="col-12 col-lg-6 order-lg-4">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaOrcamento" placeholder="Buscar por id ou titulo">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
                    <button type="button" class="ms-4 btn btn-success ml-4" data-bs-toggle="modal" data-bs-target="#novoinserirOrcamentoModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                </div>
            </div>

        </div>

        

        <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Titulo</th>
                        <th>Status</th>
                        <th>Abertura</th>
                        <th>Aprovação</th>
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <th>Horas</th>
                        <th>hora</th>
                        <?php } ?>
                        <th>Orçamento</th>
                        <th colspan="2">Ação</th>
                    </tr>

                    <tr class="ts-headerTabelaLinhaBaixo">
                        <th></th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idCliente" id="FiltroClientes">
                                    <?php if ($_SESSION['administradora'] == 1) { ?>
                                        <option value="<?php echo null ?>"><?php echo "Selecione" ?></option>
                                    <?php }
                                    foreach ($clientes as $cliente) {
                                    ?>
                                        <option <?php
                                                if ($cliente['idCliente'] == $idCliente) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $cliente['idCliente'] ?>"><?php echo $cliente['nomeCliente'] ?></option>
                                    <?php } ?>
                                </select>
                            </form>
                        </th>
                        <th></th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="statusOrcamento" id="FiltroStatusOrcamento">
                                <option value="<?php echo null ?>"><?php echo "Selecione" ?></option>
                                <?php
                                foreach ($orcamentosStatus as $orcamentoStatus) {
                                ?>
                                    <option <?php
                                            if ($orcamentoStatus['idOrcamentoStatus'] == $statusOrcamento) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $orcamentoStatus['idOrcamentoStatus'] ?>"><?php echo $orcamentoStatus['nomeOrcamentoStatus']  ?></option>
                                <?php  } ?>
                                </select>

                            </form>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>

        <?php include_once 'modalOrcamento_inserir.php' ?>

    </div>
    
    
    <!-- LOCAL PARA COLOCAR OS JS -->
    
    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <!-- script para menu de filtros -->
    <script src="<?php echo URLROOT ?>/sistema/js/filtroTabela.js"></script>
    
    <script>
        buscar($("#FiltroClientes").val(), $("#buscaOrcamento").val(), $("#FiltroStatusOrcamento").val());

        function limpar() {
            buscar(null, null, null);
            window.location.reload();
        }

        function buscar(idCliente, buscaOrcamento, statusOrcamento) {

            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo URLROOT ?>/services/database/orcamento.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    idCliente: idCliente,
                    buscaOrcamento: buscaOrcamento,
                    statusOrcamento: statusOrcamento
                },


                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);
                    //alert("terceiro alert: " + JSON.stringify(json));
                    /* alert(JSON.stringify(msg)); */
                    /* $("#dados").html(msg); */

                    var linha = "";
                    // Loop over each object
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        //dataAbertura
                        if (object.dataAbertura == "0000-00-00 00:00:00") {
                            var dataAberturaFormatada = "<p>---</p>";
                        } else {
                            var dataAbertura = new Date(object.dataAbertura);
                            dataAberturaFormatada = dataAbertura.toLocaleDateString("pt-BR") + " " + dataAbertura.toLocaleTimeString("pt-BR");
                        }

                        //dataAprovacao
                        if (object.dataAprovacao == null) {
                            var dataAprovacaoFormatada = "<p>---</p>";
                        } else {
                            var dataAprovacao = new Date(object.dataAprovacao);
                            dataAprovacaoFormatada = dataAprovacao.toLocaleDateString("pt-BR");
                        }
                        // alert("quarto alert: " + JSON.stringify(object))
                        /*  alert(object); */
                        linha = linha + "<tr>";
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + object.idOrcamento + "</td>";
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + object.nomeCliente + "</td>";
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + object.tituloOrcamento + "</td>";
                        linha = linha + "<td class='" + object.nomeOrcamentoStatus + "' data-status='Finalizado' >" + object.nomeOrcamentoStatus + " </td>";
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + dataAberturaFormatada + "</td>";
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + dataAprovacaoFormatada + "</td>";
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + object.horas + "</td>";
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + object.valorHora + "</td>";
                        <?php } ?>
                        linha = linha + "<td class='ts-click' data-idOrcamento='" + object.idOrcamento + "'>" + object.valorOrcamento + "</td>";
                        
                        linha += "<td>"; 
                        linha += "<div class='btn-group dropstart'><button type='button' class='btn' data-toggle='tooltip' data-placement='left' title='Opções' data-bs-toggle='dropdown' " +
                        " aria-expanded='false' style='box-shadow:none'><i class='bi bi-three-dots-vertical'></i></button><ul class='dropdown-menu'>"

                        linha += "<li class='ms-1 me-1 mt-1'><a class='btn btn-warning btn-sm w-100 text-start' href='visualizar.php?idOrcamento=" + object.idOrcamento + 
                        "' role='button' id='visualizarOrcamentoButton'><i class='bi bi-pencil-square'></i> Alterar</a></li>";

                        linha += "</tr>";
                        linha +="</ul></div>"
                        linha += "</td>";
                        
                        
                        linha = linha + "</tr>";
                    }

                    //alert(linha);
                    $("#dados").html(linha);


                },
                error: function(e) {
                    alert('Erro: ' + JSON.stringify(e));

                    return null;
                }
            });

        }

        $("#FiltroClientes").change(function() {
            buscar($("#FiltroClientes").val(), $("#buscaOrcamento").val(), $("#FiltroStatusOrcamento").val());
        })

        $("#buscar").click(function() {
            buscar($("#FiltroClientes").val(), $("#buscaOrcamento").val(), $("#FiltroStatusOrcamento").val());
        })

        $("#FiltroStatusOrcamento").change(function() {
            buscar($("#FiltroClientes").val(), $("#buscaOrcamento").val(), $("#FiltroStatusOrcamento").val());
        })

       //Gabriel 22092023 id544 trocado setcookie por httpRequest enviado para gravar origem em session//ajax
        $(document).on('click', '#visualizarOrcamentoButton', function() {
            var currentPath = window.location.pathname;
            $.ajax({
                type: 'POST',
                url: '../database/demanda.php?operacao=origem',
                data: {
                origem: currentPath
                },
                success: function(response) {
                console.log('Session variable set successfully.');
                },
                error: function(xhr, status, error) {
                console.error('An error occurred:', error);
                }
            });
        });

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#FiltroClientes").val(), $("#buscaOrcamento").val(), $("#FiltroStatusOrcamento").val());
            }
        });

        $(document).on('click', '.ts-click', function() {
        	window.location.href='visualizar.php?idOrcamento=' + $(this).attr('data-idOrcamento');
    	});


    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>