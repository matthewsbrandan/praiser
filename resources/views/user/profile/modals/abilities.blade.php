<!-- Modal -->
<div class="modal fade" id="modalEditAbilities" tabindex="-1" role="dialog" aria-labelledby="editAbilitiesTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAbilitiesTitle">Editar Habilidades</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <h6 class="mb-3">Habilidades</h6>
        <form method="post" action="{{ route('ability.bind')}}">
          {{ csrf_field() }}
          <div class="row px-2">
            @foreach($abilities as $ability)
              <div class="col-md-4 col-sm-6">
                <div class="form-check form-switch mb-4">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="abilities[]"
                    id="checkbox-{{$ability->id}}"
                    value="{{$ability->id}}"
                    @if($user->abilities->where('id', $ability->id)->first())
                      checked
                    @endif
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
