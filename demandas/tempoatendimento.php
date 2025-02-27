<?php
// lucas 07062024 id 742 visao do mes atual
// helio 01022023 alterado para include_once
// gabriel 03022023 alterado visualizar

include_once '../head.php';
include_once '../database/tarefas.php';
include_once '../database/demanda.php';

include_once '../database/contratotipos.php';
include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');


$contratotipos = buscaContratoTipos();
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);
// Helio 29/07/2024 - quando está gravanbco novo login, não está gravando o usuario no mysql
// paliativo
if (isset($usuario)) {
    if ($usuario["idUsuario"] == null) {
        echo "Usuario não encontrado!" . "<HR>";
        return;
    }
} else {
    return;
}

if ($usuario["idCliente"] == null) {
    $clientes = buscaClientes($usuario["idCliente"]);
} else {
    $clientes = array(buscaClientes($usuario["idCliente"]));
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="ts-noScroll">

    <div class="container-fluid">

        <div class="row d-flex pt-1 pb-2 mb-1 border-bottom">


            <div class="col-1 d-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#periodoModal"><i
                        class="bi bi-calendar3"></i></button>
            </div>

            <div class="col-2">
                <select class="form-select ts-input mt-1 pt-1 <?php if ($usuario["idCliente"] != null) {
                    echo "ts-displayDisable";
                } ?>" name="idCliente" id="FiltroCliente">
                    <option value="<?php echo null ?>">
                        <?php echo "Todos Clientes" ?>
                    </option>
                    <?php
                    foreach ($clientes as $cliente) {
                        ?>
                        <option <?php
                        if ($cliente['idCliente'] == $usuario["idCliente"]) {
                            echo "selected";
                        }
                        ?> value="<?php echo $cliente['idCliente'] ?>">
                            <?php echo $cliente['nomeCliente'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-2">
                <select class="form-select ts-input mt-1 pt-1" name="idContratoTipo" id="FiltroContratoTipo">
                    <option value="<?php echo null ?>">
                        <?php echo "Todos Tipos" ?>
                    </option>
                    <?php
                    foreach ($contratotipos as $contratotipo) {
                        ?>
                        <option <?php
                        ?> value="<?php echo $contratotipo['idContratoTipo'] ?>">
                            <?php echo $contratotipo['nomeContrato'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            
            

            <div class="col-8 d-flex gap-2 align-items-end justify-content-end">
                <div class="col-2 p-0">
                    <button type="submit" class="btn btn-success btn-sm" id="exportCSV">Gerar CSV</button>
                </div>
                <div class="col-1 p-0">
                    <input type="text" class="form-control ts-input" name="anoImposto" id="FiltroDataAno"
                        placeholder="Ano" autocomplete="off" required>
                </div>

                <div class="col-2">
                    <select class="form-select ts-input" name="mesImposto" id="FiltroDataMes">
                        <option value="01">Janeiro</option>
                        <option value="02">Fevereiro</option>
                        <option value="03">Março</option>
                        <option value="04">Abril</option>
                        <option value="05">Maio</option>
                        <option value="06">Junho</option>
                        <option value="07">Julho</option>
                        <option value="08">Agosto</option>
                        <option value="09">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary btn-sm" id="filtrardata">Filtrar </button>
                </div>
            </div>

        </div>

        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-8">
                    <div class="table mt-2 ts-tableFiltros text-center">
                        <table class="table table-hover table-sm align-middle">
                            <thead class="ts-headertabelafixo">
                                <tr>
                                    <th>Titulo</th>
                                    <th>Atendente</th>
                                    <th>Ocorrência</th>
                                    <th>Fechamento</th>
                                    <th>Data</th>
                                    <?php if ($_SESSION['administradora'] == 1) { ?>
                                        <th>Hora</th>
                                        <th>Tempo</th>
                                    <?php } ?>
                                    <th>Cobrado</th>
                                </tr>
                            </thead>

                            <tbody id='dados' class="fonteCorpo">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-4 mt-0 pt-0">
                    <div id="piechart"></div>
                    <h6 class="ml-2" id="textocontador" style="color: #13216A;"></h6>
                </div>
            </div>
        </div>




<!-- LOCAL PARA COLOCAR OS JS -->
<?php include_once ROOT . "/vendor/footer_js.php"; ?>
<!-- Google charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- script para menu de filtros -->
<script src="<?php echo URLROOT ?>/sistema/js/filtroTabela.js"></script>
<script>
    const date = new Date();
    const year = date.getFullYear();
    const currentMonth = date.getMonth() + 1;
    const FiltroDataAno = document.getElementById("FiltroDataAno");
    FiltroDataAno.value = year;
    const FiltroDataMes = document.getElementById("FiltroDataMes");
    FiltroDataMes.value = (currentMonth <= 9 ? "0" + currentMonth : currentMonth);

    $(document).ready(function() {
        var texto = $("#textocontador");
        if (<?php echo $_SESSION['administradora'] == 1 ? 'true' : 'false'; ?>) {
            texto.html('Total Cobrado: ' + "00:00" + " | Total Realizado: " + "00:00");
        } else {
            texto.html('Total Cobrado: ' + "00:00");
        }
    });

    buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());

    function buscar(FiltroContratoTipo, FiltroCliente, FiltroDataAno, FiltroDataMes) {
        $('#tabelaAno').val($("#FiltroDataAno").val());
        $('#tabelaMes').val($("#FiltroDataMes").val());
        $('#tabelaTipoContrato').val($("#FiltroContratoTipo").val());
        $('#tabelaCliente').val($("#FiltroCliente").val());
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo URLROOT ?>/servicos/database/demanda.php?operacao=tempoatendimento",
            beforeSend: function () {
                $("#dados").html("Carregando...");
            },
            data: {
                idContratoTipo: FiltroContratoTipo,
                idCliente: FiltroCliente,
                ano: FiltroDataAno,
                mes: FiltroDataMes
            },
            success: function (msg) {
                var json = JSON.parse(msg);
                var linha = "";
                for (var $i = 0; $i < json.demandas.length; $i++) {
                    var object = json.demandas[$i];
                    
                    linha = linha + "<tr>";
                    linha = linha + "<td>" + object.nomeContrato + ": " + object.tituloContrato + "<br>";
                    linha = linha + object.idDemanda + " " + object.tituloDemanda + "</td>";
                    linha = linha + "<td>" + object.nomeAtendente + "</td>";
                    linha = linha + "<td>" + object.nomeTipoOcorrencia + "</td>";
                    
                    if (object.dataFechamento == null) {
                        linha = linha + "<td> </td>";
                    } else {
                        let fechamentoDate = new Date(object.dataFechamento);
                        let formattedFechamento = fechamentoDate.toLocaleString('pt-BR', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }).replace(',', '');
                        linha = linha + "<td>" + formattedFechamento + "</td>";
                    }
                    linha = linha + "<td>" + formatDate(object.dataReal) + "</td>";
                    <?php if ($_SESSION['administradora'] == 1) { ?>
                        linha = linha + "<td>" + formatTime(object.horaInicioReal) + "<br>";
                        linha = linha + " " + formatTime(object.horaFinalReal) + "</td>";
                        linha = linha + "<td>" + formatTime(object.tempo) + "</td>";
                    <?php } ?>
                    linha = linha + "<td>" + formatTime(object.tempoCobrado) + "</td>";
                    linha = linha + "</tr>";
                }
                $("#dados").html(linha);

                var total = json.total[0];
                var texto = $("#textocontador");
                if (<?php echo $_SESSION['administradora'] == 1 ? 'true' : 'false'; ?>) {
                    texto.html('Total Cobrado: ' + total.totalCobrado + " | Total Realizado: " + total.totalTempo);
                } else {
                    texto.html('Total Cobrado: ' + total.totalCobrado);
                }
            }
        });

        //AJAX GRAFICO
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '<?php echo URLROOT ?>/servicos/database/demanda.php?operacao=ocorrencia_dashboard',
            beforeSend: function () {
                $("#dados").html("Carregando...");
            },
            data: {
                idContratoTipo: FiltroContratoTipo,
                idCliente: FiltroCliente,
                ano: FiltroDataAno,
                mes: FiltroDataMes
            },
            success: function (msg) {
                $('#piechart').hide();
                var json = JSON.parse(msg);

                if (json.length > 0) {
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawPieChart);

                    function drawPieChart() {
                        var jsonDataObj = json;
                        var totalSeconds = 0;

                        jsonDataObj.forEach(function(row) {
                            var timeParts = row.Total.split(':');
                            var seconds = (+timeParts[0]) * 60 * 60 + (+timeParts[1]) * 60 + (+timeParts[2]);
                            totalSeconds += seconds;
                            row.seconds = seconds; 
                        });

                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Nome');
                        data.addColumn('number', 'Porcentagem');
                        data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}}); 

                        for (var i = 0; i < jsonDataObj.length; i++) {
                            var row = jsonDataObj[i];
                            var percentage = (row.seconds / totalSeconds) * 100;
                            var formattedTime = formatTime(row.Total);
                            data.addRow([row.nomeTipoOcorrencia, percentage, '<div><b>' + row.nomeTipoOcorrencia + '</b><br>' + formattedTime + '</div>']);
                        }

                        var options = {
                            title: 'Distribuição por Tipo de Ocorrência',
                            width: 500,
                            height: 400,
                            pieSliceText: 'percentage',
                            sliceVisibilityThreshold: 0,
                            tooltip: {isHtml: true} 
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                        chart.draw(data, options);
                    }
                    $('#piechart').show();
                }
            }
        });
    }

    let formatTime = (time) => time.split(':').slice(0, 2).join(':');

    $("#FiltroContratoTipo").change(function () {
        buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
    });
    $("#FiltroCliente").change(function () {
        buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
    });
    $("#filtrardata").click(function () {
        buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
    });
    document.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            buscar($("#FiltroContratoTipo").val(), $("#FiltroCliente").val(), $("#FiltroDataAno").val(), $("#FiltroDataMes").val());
        }
    });

    function formatDate(dateString) {
        if (dateString !== null && !isNaN(new Date(dateString))) {
            var date = new Date(dateString);
            var day = date.getUTCDate().toString().padStart(2, '0');
            var month = (date.getUTCMonth() + 1).toString().padStart(2, '0');
            var year = date.getUTCFullYear().toString().padStart(4, '0');
            return day + "/" + month + "/" + year;
        }
        return "";
    }

    $("#exportCSV").click(function () {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo URLROOT ?>/servicos/database/demanda.php?operacao=tempoatendimento",
            data: {
                idContratoTipo: $("#FiltroContratoTipo").val(),
                idCliente: $("#FiltroCliente").val(),
                ano: $("#FiltroDataAno").val(),
                mes: $("#FiltroDataMes").val()
            },
            success: function (msg) {
                var json = JSON.parse(msg);
                if (json.demandas && json.demandas.length > 0) {
                    var csvContent = "data:text/csv;charset=utf-8,\uFEFF";
                    var mes = $("#FiltroDataMes").val();
                    var ano = $("#FiltroDataAno").val();
                    if (<?php echo $_SESSION['administradora'] == 1 ? 'true' : 'false'; ?>) {
                        csvContent += "Titulo,Atendente,Ocorrencia,Fechamento,Data,HoraInicio,HoraFinal,Tempo,Cobrado;\n";
                    } else {
                        csvContent += "Titulo,Atendente,Ocorrencia,Fechamento,Data,Cobrado;\n";
                    }
                    for (var i = 0; i < json.demandas.length; i++) {
                        var object = json.demandas[i];
                        var fechamento = object.dataFechamento ? new Date(object.dataFechamento).toLocaleString('pt-BR', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }).replace(',', '') : '';
                        var linha = [
                            object.nomeContrato + ": " + object.tituloContrato + " " + object.idDemanda + " " + object.tituloDemanda,
                            object.nomeAtendente,
                            object.nomeTipoOcorrencia,
                            fechamento,
                            formatDate(object.dataReal)
                        ];
                        if (<?php echo $_SESSION['administradora'] == 1 ? 'true' : 'false'; ?>) {
                            linha = linha.concat([
                                formatTime(object.horaInicioReal),
                                formatTime(object.horaFinalReal),
                                formatTime(object.tempo),
                                formatTime(object.tempoCobrado)
                            ]);
                        } else {
                            linha = linha.concat([
                                formatTime(object.tempoCobrado)
                            ]);
                        }
                        csvContent += linha.join(',') + ";\n";
                    }
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "atendimento_" + mes + "_" + ano + ".csv");
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    console.log("Erro ao Buscar Dados");
                }
            },
            error: function (e) {
                alert('Erro: ' + JSON.stringify(e));
            }
        });
    });

    
</script>
<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>