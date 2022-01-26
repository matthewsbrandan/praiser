<div class="page-contents" id="content-ability" style="display: none;">
  <h6 class="mb-4">3. Habilidades</h6>
  <div class="row">
    <div class="col-12">
      <div class="form-group">
        <label>Selecione suas habilidades:</label>
      </div>
    </div>
    @foreach($abilities as $ability)
      <div class="col-md-4 col-sm-6">
        <div class="form-check form-switch mb-4">
          <input
            class="form-check-input"
            type="checkbox"
            name="abilities[]"
            id="checkbox-{{$ability->id}}"
            value="{{$ability->id}}"
          />
          <label class="form-check-label" for="checkbox-{{$ability->id}}">
            {{ $ability->name }}
          </label>
        </div>
      </div>
    @endforeach
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <button
        type="button"
        class="btn bg-gradient-primary mt-3 mb-0"
        onclick="handlePagination(4)"
      >Próximo</button>
    </div>
  </div>
</div>