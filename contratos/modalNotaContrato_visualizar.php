<!--------- VISUALIZAR --------->
<div class="modal fade bd-example-modal-lg" id="visualizarModal" tabindex="-1" aria-labelledby="visualizarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Visualizar Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pdfViewer"></div>
                <div id="xmlViewer"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '#xml, #pdf', function () {
        var idNotaServico = $(this).attr("data-idNotaServico");
        var visualizarTipo = $(this).attr("id") === "xml" ? "xml" : "pdf";
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo URLROOT ?>/notas/database/notasservico.php?operacao=baixarnota',
            data: {
                idNotaServico: idNotaServico,
                visualizar: visualizarTipo
            },
            success: function (msg) {
                console.log(msg);
                $('#visualizarModal').modal('show');
                $('#pdfViewer iframe').remove();
                $('#xmlViewer pre').remove();
                $('#xmlViewer button').remove();

                if (visualizarTipo === 'pdf') {
                    var pdfDataUri = "data:application/pdf;base64," + msg.pdf_content;

                    var iframe = document.createElement('iframe');
                    iframe.src = pdfDataUri;
                    iframe.width = '100%';
                    iframe.height = '600px';

                    $('#pdfViewer').append(iframe);

                } if (visualizarTipo === 'xml') {
                    var xmlContent = msg.xml_content;

                    var Xml = formatXml(xmlContent);

                    var preElement = $('<pre>').html($('<div>').text(Xml).html());
                    $('#xmlViewer').append(preElement)
                    var downloadButton = $('<button>')
                        .attr('type', 'button')
                        .addClass('btn btn-info btn-sm float-end')
                        .text('Download XML')
                        .click(function () {
                            downloadXml(Xml, idNotaServico + '.xml');
                        });

                    $('#xmlViewer').append(downloadButton);
                }
            }
        });
        function formatXml(xmlString) {
            var string = '';
            var reg = /(>)(<)(\/*)/g;
            xmlString = xmlString.replace(reg, '$1\r\n$2$3');
            var pad = 0;
            jQuery.each(xmlString.split('\r\n'), function (index, node) {
                var indent = 0;
                if (node.match(/.+<\/\w[^>]*>$/)) {
                    indent = 0;
                } else if (node.match(/^<\/\w/)) {
                    if (pad !== 0) {
                        pad -= 1;
                    }
                } else if (node.match(/^<\w[^>]*[^\/]>.*$/)) {
                    indent = 1;
                } else {
                    indent = 0;
                }

                var padding = '';
                for (var i = 0; i < pad; i++) {
                    padding += '  ';
                }

                string += padding + node + '\r\n';
                pad += indent;
            });

            return string;
        }
        function downloadXml(xmlContent, filename) {
            var blob = new Blob([xmlContent], { type: 'application/xml' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            link.click();
        }
    });
</script>