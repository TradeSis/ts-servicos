 <!-- Lucas 10112023 id965 Melhorias em Tarefas -->


 <!--------- MODAL STOP Tab EXECUCAO --------->
 <div class="modal" id="stopmodal" tabindex="-1" aria-labelledby="stopmodalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLabel">Stop Tarefa</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>

       <div class="modal-body">
         <form method="post" id="stopForm">
           <!-- lucas 27022024 - id853 nova chamada editor quill -->
           <div class="container-fluid p-0">
             <div id="ql-toolbarTarefaStop">
               <?php include ROOT."/sistema/quilljs/ql-toolbar-min.php"  ?>
               <input type="file" id="anexarTarefaStop" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileTarefaStop()" style=" display:none">
               <label for="anexarTarefaStop">
                 <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
               </label>
             </div>
             <div id="ql-editorTarefaStop" style="height:30vh !important">
             </div>
             <textarea style="display: none" id="quill-stop" name="comentario"></textarea>
           </div>

           <div class="col-md">
             <input type="hidden" class="form-control" name="idUsuario" value="<?php echo $usuario['idUsuario'] ?>" readonly>
             <input type="hidden" class="form-control" name="idTarefa" id="stopmodal_idTarefa" />
             <input type="hidden" class="form-control" name="idDemanda" id="stopmodal_idDemanda" />
             <input type="hidden" class="form-control" name="idCliente" id="stopmodal_idCliente" />
           </div>
       </div>
       <div class="modal-footer">

         <?php if (isset($demanda)) { ?>
           <div class="col align-self-start pl-0">
             <button type="submit" formaction="../database/tarefas.php?operacao=realizado&acao=entregue&redirecionarDemanda" class="btn btn-warning float-left">Entregar</button>
           </div>
           <button type="submit" formaction="../database/tarefas.php?operacao=realizado&acao=stop&redirecionarDemanda" class="btn btn-danger">Stop</button>
         <?php } else { ?>
           <div class="col align-self-start pl-0">
             <!-- gabriel 13102023 id 596 fix ao dar stop vai para demanda -->
             <button type="submit" id="realizadoFormbutton" class="btn btn-warning float-left">Entregar</button>
           </div>
           <!-- gabriel 13102023 id 596 fix ao dar stop vai para demanda -->
           <button type="submit" id="stopFormbutton" class="btn btn-danger">Stop</button>
         <?php } ?>


         </form>
       </div>

     </div>
   </div>
 </div>

 <?php include_once ROOT . "/vendor/footer_js.php"; ?>
 <!-- LOCAL PARA COLOCAR OS JS -->
 <!-- lucas 27022024 - id853 nova chamada editor quill -->
 <script>
   var quillTarefaStop = new Quill('#ql-editorTarefaStop', {
     modules: {
       toolbar: '#ql-toolbarTarefaStop'
     },
     placeholder: 'Digite o texto...',
     theme: 'snow'
   });

   quillTarefaStop.on('text-change', function() {
     $('#quill-stop').val(quillTarefaStop.container.firstChild.innerHTML);
   });

   async function uploadFileTarefaStop() {

     let endereco = '/tmp/';
     let formData = new FormData();
     var custombutton = document.getElementById("anexarTarefaStop");
     var arquivo = custombutton.files[0]["name"];

     formData.append("arquivo", custombutton.files[0]);
     formData.append("endereco", endereco);

     destino = endereco + arquivo;

     await fetch('/sistema/quilljs/quill-uploadFile.php', {
       method: "POST",
       body: formData
     });


     const range = this.quillTarefaStop.getSelection(true)

     this.quillTarefaStop.insertText(range.index, arquivo, 'user');
     this.quillTarefaStop.setSelection(range.index, arquivo.length);
     this.quillTarefaStop.theme.tooltip.edit('link', destino);
     this.quillTarefaStop.theme.tooltip.save();

     this.quillTarefaStop.setSelection(range.index + destino.length);

   }
 </script>

 <!-- LOCAL PARA COLOCAR OS JS -FIM -->