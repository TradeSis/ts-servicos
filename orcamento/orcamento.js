// Tabs de orcamento
var tab;
var tabContent;

window.onload = function () {
    tabContent = document.getElementsByClassName('tabContent');
    tab = document.getElementsByClassName('tab');
    hideTabsContent(1);

    var urlParams = new URLSearchParams(window.location.search);
    var id = urlParams.get('id');
    if (id === 'orcamentoitens') {
        showTabsContent(1);
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
    $('.aba2_conteudo').hide();
});

$('.aba2').click(function () {
    $('.aba2_conteudo').show();
    $('.aba2').addClass('whiteborder');
    $('.aba1').removeClass('whiteborder');
    $('.aba1_conteudo').hide();
});


$('.ts-btnDescricaoEditar').click(function () {
    $('#ql-editorOrcamentoDescricao').toggleClass('ts-displayDisable');
    $('.ql-toolbar').show();
});

var quillOrcamentoDescricaoAlterar = new Quill('#ql-editorOrcamentoDescricao', {
    modules: {
        toolbar: '#ql-toolbarOrcamentoDescricao'
    },
    placeholder: 'Digite o texto...',
    theme: 'snow'
});

quillOrcamentoDescricaoAlterar.on('text-change', function () {
    $('#quill-orcamentoDescricao').val(quillOrcamentoDescricaoAlterar.container.firstChild.innerHTML);
});

async function uploadFileOrcamentoDescricao() {

    let endereco = '/tmp/';
    let formData = new FormData();
    var custombutton = document.getElementById("anexarOrcamentoDescricao");
    var arquivo = custombutton.files[0]["name"];

    formData.append("arquivo", custombutton.files[0]);
    formData.append("endereco", endereco);

    destino = endereco + arquivo;

    await fetch('/sistema/quilljs/quill-uploadFile.php', {
        method: "POST",
        body: formData
    });


    const range = this.quillOrcamentoDescricaoAlterar.getSelection(true)

    this.quillOrcamentoDescricaoAlterar.insertText(range.index, arquivo, 'user');
    this.quillOrcamentoDescricaoAlterar.setSelection(range.index, arquivo.length);
    this.quillOrcamentoDescricaoAlterar.theme.tooltip.edit('link', destino);
    this.quillOrcamentoDescricaoAlterar.theme.tooltip.save();

    this.quillOrcamentoDescricaoAlterar.setSelection(range.index + destino.length);

}


$(document).ready(function () {

    $(document).on('click', 'button[data-bs-target="#alterarOrcamentoItensModal"]', function () {
        var idOrcamento = $(this).attr("data-idOrcamento");
        var idItemOrcamento = $(this).attr("data-idItemOrcamento");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../database/orcamento.php?operacao=itensbuscar',
            data: {
                idOrcamento: idOrcamento,
                idItemOrcamento: idItemOrcamento
            },
            success: function (data) {
                $('#idOrcamento').val(data.idOrcamento);
                $('#idItemOrcamento').val(data.idItemOrcamento);
                $('#tituloItemOrcamento').val(data.tituloItemOrcamento);
                $('#horas').val(data.horas);
                $('#alterarOrcamentoItensModal').modal('show');
            }
        });
    });

    $(document).on('click', 'button[data-bs-target="#excluirOrcamentoItensModal"]', function () {
        var idOrcamento = $(this).attr("data-idOrcamento");
        var idItemOrcamento = $(this).attr("data-idItemOrcamento");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../database/orcamento.php?operacao=itensbuscar',
            data: {
                idOrcamento: idOrcamento,
                idItemOrcamento: idItemOrcamento
            },
            success: function (data) {
                $('#EXCidOrcamento').val(data.idOrcamento);
                $('#EXCidItemOrcamento').val(data.idItemOrcamento);
                $('#EXCtituloItemOrcamento').val(data.tituloItemOrcamento);
                $('#EXChoras').val(data.horas);
                $('#excluirOrcamentoItensModal').modal('show');
            }
        });
    });
});