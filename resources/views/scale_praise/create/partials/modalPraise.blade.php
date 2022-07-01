<!-- Modal -->
<div class="modal fade" id="modalPraise" tabindex="-1" role="dialog" aria-labelledby="modalPraiseName" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5
          class="modal-title"
          id="modalPraiseName"
          onclick="$('#modalPraise').modal('hide'); $('#minister-praise').focus();"
        ></h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <label>Cantor/Banda</label>
          <div class="input-group mb-3">
            <input
              class="form-control"
              placeholder="Nome do Cantor/Banda"
              aria-label="Nome do Cantor/Banda"
              type="text"
              id="minister-singer"
            />
          </div>
        </div>
        <div>
          <label>Link do Youtube</label>
          <div class="input-group mb-3">
            <input
              class="form-control"
              placeholder="Link do youtube"
              aria-label="Link do youtube"
              type="text"
              id="minister-youtube"
            />
          </div>
        </div>
        <div>
          <label>Cifra/Tom (opcional)</label>
          <div class="input-group mb-3">
            <input
              class="form-control"
              placeholder="Link da cifra"
              aria-label="Link da cifra"
              type="text"
              id="minister-cipher"
            />
            <input
              class="form-control border ps-2"
              placeholder="Tom"
              aria-label="Tom"
              type="text"
              id="minister-tone"
              style="max-width: 5rem;"
            />
          </div>
        </div>
        <div>
          <label>Legenda (opcional)</label>
          <div class="input-group mb-3">
            <input
              class="form-control"
              placeholder="Caso seja oferta, pós-palavra, ceia, etc..."
              aria-label="Legenda"
              type="text"
              id="minister-legend"
              list="complete-legend"
            />
          </div>
          <datalist id="complete-legend">
            <option value="Oferta">
            <option value="Pós-palavra">
            <option value="Ceia">
            <option value="Abertura">
            <option value="Encerramento">
          </datalist>
        </div>
        <div class="text-center">
          <button
            type="button"
            class="btn bg-gradient-primary mb-3 w-100"
            onclick="handleAddPraiseToScale()"
          >Adicionar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function handleShowPraise(){
    let name = $('#minister-praise').val();

    if(name.length == 0){
      notify('warning','Preencha primeiro o nome do louvor');
      return null;
    }

    $('#modalPraiseName').html(name);
    $('#modalPraise').modal('show');
    $('#minister-singer').focus();
  }
</script>