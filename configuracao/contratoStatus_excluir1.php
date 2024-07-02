<?php
// helio 26012023 16:16

include '../head.php';
include '../database/contratoStatus.php';

$idContratoStatus = $_GET['idContratoStatus'];

$contratoStatus = buscaContratoStatus($idContratoStatus);



?>


<body>




<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="card shadow">
            <div class="card-header">
                <div class="row">
                    <h3 class="col">Excluir Status</h3>
                    <div style="text-align:right">
                       
                        <a href="contratoStatus.php" role="button" class="btn btn-primary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>
            <div class="container" style="margin-top: 10px">
                <form action="../database/contratoStatus.php?operacao=excluir" method="post" >                    
                <div class="form-group" style="margin-top:10px">
                    <label>Nome Status</label>
                    <input type="text" class="form-control" name="nomeContratoStatus" value="<?php echo $contratoStatus['nomeContratoStatus'] ?>">
                    <input type="text" class="form-control" name="idContratoStatus" value="<?php echo $contratoStatus['idContratoStatus'] ?>" style="display: none">
                </div>
                    <div class="card-footer py-2">
                        <div style="text-align:right">
                            <button type="submit" class="btn btn-sm btn-success">Excluir</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</body>



<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Excluir Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
            <div class="card-header">

            </div>
            <div class="container" style="margin-top: 10px">
                <form action="../database/contratoStatus.php?operacao=excluir" method="post" >                    
                <div class="form-group" style="margin-top:10px">
                    <label>Nome Status</label>
                    <input type="text" class="form-control" name="nomeContratoStatus" value="<?php echo $contratoStatus['nomeContratoStatus'] ?>">
                    <input type="text" class="form-control" name="idContratoStatus" value="<?php echo $contratoStatus['idContratoStatus'] ?>" style="display: none">
                </div>
                    <div class="card-footer py-2">
                        <div style="text-align:right">
                            <button type="submit" class="btn btn-sm btn-success">Excluir</button>
                        </div>
                    </div>
                </form>
            </div>
        

    </div>
  </div>
</div>