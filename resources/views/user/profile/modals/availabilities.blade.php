<!-- Modal -->
<div class="modal fade" id="modalEditAvailabilities" tabindex="-1" role="dialog" aria-labelledby="editAvailabilitiesTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAvailabilitiesTitle">Editar Disponibilidades</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <h6 class="mb-3">Disponibilidades</h6>
        <form method="POST" action="{{ route('user.update.availability') }}">
          {{ csrf_field() }}
          <div class="form-group mb-0 mt-md-0 mt-4">
            <label class="pb-2">Marque os dias disponíveis:</label>
            <div class="d-flex flex-wrap justify-content-around">
              @foreach(\App\Models\User::getAvailableWeekdays() as $wd => $weekday)
                <button
                  type="button"
                  class="btn btn-sm {{ in_array($wd, $user->getAvailability()) ? 'bg-gradient-primary':'bg-gradient-light' }} mx-2"
                  onclick="toggleAvailability($(this),'{{ $wd }}')"
                  style="width: 10rem"
                >{{ $weekday }}</button>
              @endforeach
              <input
                type="hidden"
                name="availability"
                id="input-availability"
                value="{{ $user->availability }}"
              />
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
            >{{ $user->outhers_availability }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <button
                type="submit"
                class="btn bg-gradient-primary mt-3 mb-0"
              >Salvar Edição</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  function toggleAvailability(elem, weekday){
    elem.toggleClass('bg-gradient-light bg-gradient-primary');

    let availabilities = $('#input-availability').val() ? $('#input-availability').val().split(',') : [];
    if(availabilities.includes(weekday)) availabilities = availabilities.filter(
      item => item != weekday
    );
    else availabilities.push(weekday);
    $('#input-availability').val(availabilities.join(','));
  }
</script>