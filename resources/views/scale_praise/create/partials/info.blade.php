<div id="content-info"> 
  <div>
    <div class="form-check form-switch mt-3">
      <input
        class="form-check-input"
        type="checkbox"
        name="privacy"
        id="checkbox-privacy"
        @if(!isset($minister) || $minister->privacy == 'public')
          checked
        @endif
      />
      <label class="form-check-label font-weight-bold" for="checkbox-privacy">
        Pública
      </label>
    </div>
    <span class="text-muted text-sm">
      Quando a privacidade publica está desativada, apenas você pode ver sua escala.
    </span>
  </div>
  <button
    type="button"
    class="btn btn btn-link d-block w-100 mb-1"
    onclick="if($(this).html() === 'Mais opções') $(this).html('Menos opções').next().show('slow'); else $(this).html('Mais opções').next().hide('slow');"
  >Mais opções</button>
  <div class="mb-4" style="display: none;">
    <div>
      <label>Versículo (opcional)</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Versículo de abertura"
          aria-label="Versículo de abertura"
          type="text"
          id="minister-verse"
          value="{{ $minister->verse ?? '' }}"
        />
      </div>
    </div>
    <div class="form-group mb-0 mt-md-0 mt-4">
      <label>Sobre (opcional)</label>
      <textarea
        id="minister-about"
        class="form-control"
        rows="6"
        placeholder="Você pode descrever a mensagem da sua ministração nesta area."
      >{{ $minister->about ?? '' }}</textarea>
    </div>
  </div>
</div>