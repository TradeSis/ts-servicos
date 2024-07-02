// Abrir demanda/visualizar com o click do mouse na tabela
$(document).on('click', '.ts-click', function () {
    window.location.href = '../demandas/visualizar.php?idDemanda=' + $(this).attr('data-idDemanda');
});

// Tabs de contrato/visualizar
var tab;
var tabContent;

window.onload = function () {
    tabContent = document.getElementsByClassName('tabContent');
    tab = document.getElementsByClassName('tab');
    hideTabsContent(1);

    var urlParams = new URLSearchParams(window.location.search);
    var id = urlParams.get('id');
    if (id === 'demandacontrato') {
        showTabsContent(1);
    }
    if (id === 'contratochecklist') {
        showTabsContent(2);
    }
    if (id === 'notascontrato') {
        showTabsContent(3);
    }
}

document.getElementById('ts-tabs').onclick = function (event) {
    var target = event.target;
    if (target.className == 'tab') {
        for (var i = 0; i < tab.length; i++) {
            if (target == tab[i]) {
                showTabsContent(i);
                break;
            }
        }
    }
}

function hideTabsContent(a) {
    for (var i = a; i < tabContent.length; i++) {
        tabContent[i].classList.remove('show');
        tabContent[i].classList.add("hide");
        tab[i].classList.remove('whiteborder');
    }
}

function showTabsContent(b) {
    if (tabContent[b].classList.contains('hide')) {
        hideTabsContent(0);
        tab[b].classList.add('whiteborder');
        tabContent[b].classList.remove('hide');
        tabContent[b].classList.add('show');
    }
}

// Script de disabilar conteudo da aba quando não estiver ativa
$('.aba1').click(function () {
    $('.aba1_conteudo').show();
    $('.aba1').addClass('whiteborder');
    $('.aba2').removeClass('whiteborder');
    $('.aba3').removeClass('whiteborder');
    $('.aba4').removeClass('whiteborder');
    $('.aba2_conteudo').hide();
    $('.aba3_conteudo').hide();
    $('.aba4_conteudo').hide();
});

$('.aba2').click(function () {
    $('.aba2_conteudo').show();
    $('.aba2').addClass('whiteborder');
    $('.aba1').removeClass('whiteborder');
    $('.aba3').removeClass('whiteborder');
    $('.aba4').removeClass('whiteborder');
    $('.aba1_conteudo').hide();
    $('.aba3_conteudo').hide();
    $('.aba4_conteudo').hide();
});

$('.aba3').click(function () {
    $('.aba3_conteudo').show();
    $('.aba3').addClass('whiteborder');
    $('.aba1').removeClass('whiteborder');
    $('.aba2').removeClass('whiteborder');
    $('.aba4').removeClass('whiteborder');
    $('.aba1_conteudo').hide();
    $('.aba2_conteudo').hide();
    $('.aba4_conteudo').hide();
});
$('.aba4').click(function () {
    $('.aba4_conteudo').show();
    $('.aba4').addClass('whiteborder');
    $('.aba1').removeClass('whiteborder');
    $('.aba2').removeClass('whiteborder');
    $('.aba3').removeClass('whiteborder');
    $('.aba1_conteudo').hide();
    $('.aba2_conteudo').hide();
    $('.aba3_conteudo').hide();
});


$('.ts-btnDescricaoEditar').click(function () {
    $('#ql-editorContratoDescricao').toggleClass('ts-displayDisable');
    $('.ql-toolbar').show();
});

var quillContratoDescricaoAlterar = new Quill('#ql-editorContratoDescricao', {
    modules: {
        toolbar: '#ql-toolbarContratoDescricao'
    },
    placeholder: 'Digite o texto...',
    theme: 'snow'
});

quillContratoDescricaoAlterar.on('text-change', function () {
    $('#quill-contratoDescricao').val(quillContratoDescricaoAlterar.container.firstChild.innerHTML);
});

async function uploadFileContratoDescricao() {

    let endereco = '/tmp/';
    let formData = new FormData();
    var custombutton = document.getElementById("anexarContratoDescricao");
    var arquivo = custombutton.files[0]["name"];

    formData.append("arquivo", custombutton.files[0]);
    formData.append("endereco", endereco);

    destino = endereco + arquivo;

    await fetch('/sistema/quilljs/quill-uploadFile.php', {
        method: "POST",
        body: formData
    });


    const range = this.quillContratoDescricaoAlterar.getSelection(true)

    this.quillContratoDescricaoAlterar.insertText(range.index, arquivo, 'user');
    this.quillContratoDescricaoAlterar.setSelection(range.index, arquivo.length);
    this.quillContratoDescricaoAlterar.theme.tooltip.edit('link', destino);
    this.quillContratoDescricaoAlterar.theme.tooltip.save();

    this.quillContratoDescricaoAlterar.setSelection(range.index + destino.length);

}


$(document).ready(function () {

    $(document).on('click', 'button[data-bs-target="#alterarChecklistModal"]', function () {
        var idChecklist = $(this).attr("data-idChecklist");
        var idContrato = $(this).attr("data-idContrato");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../database/contratochecklist.php?operacao=buscar',
            data: {
                idChecklist: idChecklist,
                idContrato: idContrato
            },
            success: function (data) {
                $('#idChecklist').val(data.idChecklist);
                $('#idContrato').val(data.idContrato);
                $('#descricao').val(data.descricao);
                $('#dataPrevisto').val(data.dataPrevisto);
                $('#alterarChecklistModal').modal('show');
            }
        });
    });

    $(document).on('click', 'button[data-bs-target="#excluirChecklistModal"]', function () {
        var idChecklist = $(this).attr("data-idChecklist");
        var idContrato = $(this).attr("data-idContrato");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../database/contratochecklist.php?operacao=buscar',
            data: {
                idChecklist: idChecklist,
                idContrato: idContrato
            },
            success: function (data) {
                $('#EXCidChecklist').val(data.idChecklist);
                $('#EXCidContrato').val(data.idContrato);
                $('#EXCdescricao').val(data.descricao);
                $('#EXCdataPrevisto').val(data.dataPrevisto);
                $('#excluirChecklistModal').modal('show');
            }
        });
    });

    $(document).on('click', 'button[data-bs-target="#tarefaChecklistModal"]', function () {
        var idChecklist = $(this).attr("data-idChecklist");
        var idContrato = $(this).attr("data-idContrato");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../database/contratochecklist.php?operacao=buscar',
            data: {
                idChecklist: idChecklist,
                idContrato: idContrato
            },
            success: function (data) {
                $('#NEWidChecklist').val(data.idChecklist);
                $('#NEWidContrato').val(data.idContrato);
                $('#NEWdescricao').val(data.descricao);
                $('#NEWdataPrevisto').val(data.dataPrevisto);
                $('#tarefaChecklistModal').modal('show');
            }
        });
    });

    $(document).on('click', '.ts-check', function () {
        var idChecklist = $(this).find("input[type='checkbox']").attr("data-idChecklist");
        var idContrato = $(this).find("input[type='checkbox']").attr("data-idContrato");
        var statusCheck = $(this).find("input[type='checkbox']").is(":checked") ? 1 : 0;
    
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "../database/contratochecklist.php?operacao=alterar",
            data: {
                statusCheck: statusCheck,
                idChecklist: idChecklist,
                idContrato: idContrato
            },
            success: function() { 
                window.location.reload(); 
            }
        });
    });
});
