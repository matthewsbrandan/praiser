<div class="page-contents" id="content-ministry" style="display: none;">
  <h6 class="mb-4">3. Ministério</h6>
  <div class="form-group">
    <label>Selecione o(s) seu(s) ministério(s):</label>
    <div class="row">
      @foreach($ministries as $ministry)
        <div class="col-md-6 card-ministry">
          <div class="card card-plain card-blog">
            <div class="bg-secondary p-1 card-image position-relative" style="
                border-radius: 1rem;
              "
              onclick="toggleMinistry($(this),{{ $ministry->id }})"
            >
              <a href="javascript:;">
                <img class="w-100 border-radius-lg move-on-hover shadow" src="{{ $ministry->image }}">
              </a>
            </div>
            <div class="card-body px-0 pt-2">
              <h5 class="my-0">
                <a href="javascript:;" class="text-dark font-weight-bold">
                  {{ $ministry->name }}
                </a>
              </h5>
            </div>
          </div>
        </div>
      @endforeach
      @if($ministries->count() == 0)
        <p class="d-block mx-auto text-center text-muted">
          Não há ministérios disponíveis
        </p>
      @endif
      <input type="hidden" name="ministries_ids" id="ministries_ids"/>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <button type="submit" class="btn bg-gradient-primary mt-3 mb-0">
        Finalizar
      </button>
    </div>
  </div>
</div>