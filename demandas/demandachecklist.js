
$(document).ready(function () {
    // MODAL EXCLUIR
    $(document).on('click', 'button[data-bs-target="#excluirChecklistModal"]', function () {
        var idChecklist = $(this).attr("data-idChecklist");
        var idDemanda = $(this).attr("data-idDemanda");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../database/demandachecklist.php?operacao=buscar',
            data: {
                idChecklist: idChecklist,
                idDemanda: idDemanda
            },
            success: function (data) {
                $('#exc_idChecklist').val(data.idChecklist);
                $('#exc_idDemanda').val(data.idDemanda);
                $('#exc_titulo').val(data.titulo);
                $('#excluirChecklistModal').modal('show');
            }
        });
    });

    // AÇÂO DE CHECK
    $(document).on('click', '.ts-check', function () {
        var idChecklist = $(this).find("input[type='checkbox']").attr("data-idChecklist");
        var idDemanda = $(this).find("input[type='checkbox']").attr("data-idDemanda");
        var statusCheck = $(this).find("input[type='checkbox']").is(":checked") ? 1 : 0;

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "../database/demandachecklist.php?operacao=alterar",
            data: {
                statusCheck: statusCheck,
                idChecklist: idChecklist,
                idDemanda: idDemanda
            },
            success: function () {
                window.location.reload();
            }
        });
    });
});

//MODAL VISUALIZAR
$(document).on('click', '.ts-click', function () {
    var idChecklist = $(this).attr("data-idChecklist");
    var idDemanda = $(this).attr("data-idDemanda");
   
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../database/demandachecklist.php?operacao=buscar',
        data: {
            idChecklist: idChecklist,
            idDemanda: idDemanda
        },
        success: function (data) {
            //console.log(JSON.stringify(data, null, 2));
            $('#view_idChecklist').val(data.idChecklist);
            $('#view_idDemanda').val(data.idDemanda);
            $('#view_titulo').val(data.titulo);
            $('#view_descricao').val(data.descricao);
            $('#view_ordem').val(data.ordem);
            $('#view_statusCheck').val(data.statusCheck);

            $('#alterarChecklistModal').modal('show');
        }
    });
});

// MODAL INSERIR
$("#modalChecklistInserir").submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "../database/demandachecklist.php?operacao=inserir",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=demandachecklist' + '&&idDemanda=' + idDemanda;
            window.location.href = newUrl;
        }
    });
});

// MODAL ALTERAR
$("#modalChecklistAlterar").submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "../database/demandachecklist.php?operacao=alterar",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=demandachecklist' + '&&idDemanda=' + idDemanda;
            window.location.href = newUrl;
        }
            
    });
});

// MODAL EXCLUIR
$("#modalChecklistExcluir").submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "../database/demandachecklist.php?operacao=excluir",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=demandachecklist' + '&&idDemanda=' + idDemanda;
            window.location.href = newUrl;
        }
    });
});
