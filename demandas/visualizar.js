/* QUILL EDITOR - DEMANDA DESCRIÇÃO ALTERAR */
var quillDescricaoAlterar = new Quill('#ql-editorDescricao', {
    modules: {
        toolbar: '#ql-toolbarDescricao'
    },
    placeholder: 'Digite o texto...',
    theme: 'snow'
});

quillDescricaoAlterar.on('text-change', function () {
    $('#quill-demandadescricao').val(quillDescricaoAlterar.container.firstChild.innerHTML);
});

async function uploadFileDescricao() {

    let endereco = '/tmp/';
    let formData = new FormData();
    var custombutton = document.getElementById("anexarDescricao");
    var arquivo = custombutton.files[0]["name"];

    formData.append("arquivo", custombutton.files[0]);
    formData.append("endereco", endereco);

    destino = endereco + arquivo;

    await fetch('/sistema/quilljs/quill-uploadFile.php', {
        method: "POST",
        body: formData
    });


    const range = this.quillDescricaoAlterar.getSelection(true)

    this.quillDescricaoAlterar.insertText(range.index, arquivo, 'user');
    this.quillDescricaoAlterar.setSelection(range.index, arquivo.length);
    this.quillDescricaoAlterar.theme.tooltip.edit('link', destino);
    this.quillDescricaoAlterar.theme.tooltip.save();

    this.quillDescricaoAlterar.setSelection(range.index + destino.length);

}

/*  QUILL EDITOR - DEMANDA COMENTARIOS  */
var quillComentario = new Quill('#ql-editorComentario', {
    modules: {
        toolbar: '#ql-toolbarComentario'
    },
    placeholder: 'Digite o texto...',
    theme: 'snow'
});

quillComentario.on('text-change', function () {
    $('#quill-comentario').val(quillComentario.container.firstChild.innerHTML);
});

async function uploadFileComentario() {

    let endereco = '/tmp/';
    let formData = new FormData();
    var custombutton = document.getElementById("anexarComentario");
    var arquivo = custombutton.files[0]["name"];

    formData.append("arquivo", custombutton.files[0]);
    formData.append("endereco", endereco);

    destino = endereco + arquivo;

    await fetch('/sistema/quilljs/quill-uploadFile.php', {
        method: "POST",
        body: formData
    });


    const range = this.quillComentario.getSelection(true)

    this.quillComentario.insertText(range.index, arquivo, 'user');
    this.quillComentario.setSelection(range.index, arquivo.length);
    this.quillComentario.theme.tooltip.edit('link', destino);
    this.quillComentario.theme.tooltip.save();

    this.quillComentario.setSelection(range.index + destino.length);

}

/* EFEITO MOSTRAR/ESCONDER DOS EDITORES DE DESCRIÇÃO E COMENTARIOS */
$('.ts-btnDescricaoEditar').click(function () {
    $('#ql-editorDescricao').toggleClass('ts-displayDisable');
    $('.btnSalvarComentario').toggleClass('ts-sumir');
    $('.ql-toolbar').show();
});

$('.btnAdicionarComentario').click(function () {
    $('.containerComentario').toggleClass('ts-sumir');
    $('.ts-inputComentario').addClass('ts-sumir');
});

