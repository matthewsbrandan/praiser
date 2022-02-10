<div class="page-contents" id="content-praises" style="display: none;">
  <input type="hidden" id="minister-praise-id"/>
  <div>
    <label>Louvor</label>
    <div class="input-group mb-3">
      <input
        class="form-control"
        placeholder="Nome do Louvor"
        aria-label="Nome do Louvor"
        type="text"
        id="minister-praise"
      />
    </div>
  </div>
  <div class="card mb-4" style="overflow: auto">
    <div class="card-body" id="content-searched-praises">
      <div class="table-responsive" style="max-height: 16rem;">
        <table class="table table-hover align-items-center mb-0" id="table-praises">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="max-width: 15rem">Louvor</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Referências</th>
            </tr>
          </thead>
          <tbody>
            <td colspan="3" class="mb-0 text-center text-muted text-xs">...</td>
          </tbody>
        </table>
      </div>
    </div>
  </div>
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

  <div class="d-flex flex-column" id="content-praises-added">
  </div>

  <div id="finalize-scale" style="display: none;">
    <hr/>
    <div>
      <label>Playlist do Youtube</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Link da Playlist do Youtube"
          aria-label="Link da Playlist do Youtube"
          type="text"
          id="minister-playlist"
          value="{{ $minister->playlist ?? '' }}"
          required
        />
      </div>
    </div>
  
    <button
      type="button" class="btn bg-gradient-light mb-3 w-100"
      onclick="saveScale()"
    >
      @isset($minister) Editar Escala
      @else Criar Escala @endisset
    </button>
  </div>
</div>