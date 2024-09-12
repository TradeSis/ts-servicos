  <!--------- MODAL INSERIR --------->
  <div class="modal" id="inserirChecklistModal" tabindex="-1" aria-labelledby="inserirChecklistModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Inserir Checklist</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form method="post" id="modalChecklistInserir">
                      <div class="row">
                          <div class="col">
                              <label class='form-label ts-label'>Titulo</label>
                              <input type="text" class="form-control ts-input" name="titulo" autocomplete="off" required>
                              <input type="hidden" class="form-control ts-input" name="idDemanda" value="<?php echo $idDemanda ?>">
                          </div>
                          <div class="col-1">
                              <label class='form-label ts-label'>Ordem</label>
                              <input type="text" class="form-control ts-input" name="ordem">
                          </div>
                      </div>
                      <div class="row mt-2">
                          <label class="form-label ts-label">Descrição</label>
                          <div class="col-md mt-3">
                              <textarea class="ts-textareaResponsivo" name="descricao" rows="5"></textarea>
                          </div>
                      </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
              </div>
              </form>
          </div>
      </div>
  </div>

  <!--------- MODAL ALTERAR --------->
  <div class="modal" id="alterarChecklistModal" tabindex="-1" aria-labelledby="alterarChecklistModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Alterar Checklist</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form method="post" id="modalChecklistAlterar">
                      <div class="row">
                          <div class="col">
                              <label class='form-label ts-label'>Titulo</label>
                              <input type="text" class="form-control ts-input" name="titulo" id="view_titulo" required>
                              <input type="hidden" class="form-control ts-input" name="idDemanda" id="view_idDemanda">
                              <input type="hidden" class="form-control ts-input" name="idChecklist" id="view_idChecklist">
                          </div>
                          <div class="col-1">
                              <label class='form-label ts-label'>Ordem</label>
                              <input type="text" class="form-control ts-input" name="ordem" id="view_ordem">
                          </div>
                      </div>
                      <div class="row mt-2">
                          <label class="form-label ts-label">Descrição</label>
                          <div class="col-md mt-3">
                              <textarea class="ts-textareaResponsivo" name="descricao" id="view_descricao" rows="5"></textarea>
                          </div>
                      </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
              </div>
              </form>
          </div>
      </div>
  </div>

  <!--------- MODAL EXCLUIR --------->
  <div class="modal" id="excluirChecklistModal" tabindex="-1" aria-labelledby="excluirChecklistModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Excluir Checklist</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form method="post" id="modalChecklistExcluir">
                      <div class="row">
                          <div class="col-md">
                              <label class='form-label ts-label'>Titulo</label>
                              <input type="text" class="form-control ts-input" name="titulo" id="exc_titulo" autocomplete="off" readonly>
                              <input type="hidden" class="form-control ts-input" name="idChecklist" id="exc_idChecklist">
                              <input type="hidden" class="form-control ts-input" name="idDemanda" id="exc_idDemanda">
                          </div>
                      </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-danger"><i class="bi bi-sd-card-fill"></i>&#32;Excluir</button>
              </div>
              </form>
          </div>
      </div>
  </div>