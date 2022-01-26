<div class="page-contents" id="content-availability" style="display: none;">
  <h6 class="mb-4">2. Disponibilidades</h6>
  <div class="form-group mb-0 mt-md-0 mt-4">
    <label class="pb-2">Marque os dias disponíveis:</label>
    <div class="d-flex flex-wrap justify-content-around">
      @foreach(\App\Models\User::getAvailableWeekdays() as $wd => $weekday)
        <button
          type="button"
          class="btn btn-sm bg-gradient-light mx-2"
          onclick="toggleAvailability($(this),'{{ $wd }}')"
          style="width: 10rem"
        >{{ $weekday }}</button>
      @endforeach
      <input type="hidden" name="availability" id="input-availability"/>
    </div>
  </div>
  <div class="form-group mb-0 mt-md-0 mt-4">
    <label>Outras disponibilidades? (opcional)</label>
    <textarea
      name="outhers_availability"
      class="form-control"
      rows="6"
      placeholder="Digite outros horários que você tem disponível além dos dias de culto."
      maxlength="250"
    ></textarea>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <button
        type="button"
        class="btn bg-gradient-primary mt-3 mb-0"
        onclick="handlePagination(3)"
      >Próximo</button>
    </div>
  </div>
</div>