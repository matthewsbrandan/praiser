<!-- Modal -->
<div class="modal fade" id="modalEditIntegrant" tabindex="-1" role="dialog" aria-labelledby="titleEditIntegrant" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titleEditIntegrant">Editar Integrante</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('user_ministry.update') }}">
          {{ csrf_field() }}
          <input type="hidden" name="user_id" id="edit-integrant-user-id"/>
          <input type="hidden" name="ministry_id" value="{{$ministry->id}}"/>
          <h6 class="mb-2">Títulos</h6>
          <div id="edit-integrant-captions">
            @foreach(\App\Models\UserMinistry::getAvailableCaptions() as $caption)
              <span
                class="badge badge-sm bg-light text-dark edit-integrant-caption-item"
                id="edit-integrant-caption-{{ $caption->short }}"
                onclick="toggleCaption($(this), '{{ $caption->short }}')"
              >{{ $caption->name }}</span>
            @endforeach
            <input type="hidden" name="caption" id="edit-integrant-caption-input"/>
          </div>
          <h6 class="mb-2 mt-3">Habilidades</h6>
          <div class="p-2 bg-dark text-light" id="edit-integrant-exp-abilities"></div>
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
  function callEditIntegrant(user, abilities, captions){
    console.log(captions);
    $('#edit-integrant-user-id').val(user.id);
    handleRenderAbilities(abilities);

    // BEGIN:: HANDLE CAPTIONS
    $('#edit-integrant-caption-input').val(captions);
    $('.edit-integrant-caption-item').removeClass('bg-dark').addClass('bg-light text-dark');
    let captionArray = captions.split(',').filter(caption => !!caption);
    captionArray.forEach(caption => {
      $(`#edit-integrant-caption-${ caption }`).toggleClass('bg-light text-dark bg-dark');
    });
    // END:: HANDLE CAPTIONS

    $('#modalEditIntegrant').modal('show');
  }
  function handleRenderAbilities(abilities){
    $('#edit-integrant-exp-abilities').html('');
    abilities.forEach(userAbility => {
      $('#edit-integrant-exp-abilities').append(`
        <span class="text-xs text-uppercase">${ userAbility.ability.name }</span>
        <div class="form-group mb-1">
          <input
            type="range"
            max="5" min="0" step="1"
            value="${ userAbility.exp }" style="width: 100%;"
            name="exp[${userAbility.id}]"
          />
        </div>
      `);
    });
  }
  function toggleCaption(elem, short){
    elem.toggleClass('bg-light text-dark bg-dark');

    let captions = $('#edit-integrant-caption-input').val() ? $('#edit-integrant-caption-input').val().split(',') : [];
    if(captions.includes(short)) captions = captions.filter(
      item => item != short
    );
    else captions.push(short);
    $('#edit-integrant-caption-input').val(captions.join(','));
  }
</script>