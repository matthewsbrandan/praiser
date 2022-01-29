<!-- Modal -->
<div class="modal fade" id="modalEditPraise" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body pt-4" style="min-height: 80vh;">
        <div class="d-flex flex-column justify-content-between" style="min-height: 76vh;">
          <div>
            <button
              type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"
              style="position: absolute; top: .4rem; right: .4rem;"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="text-center">
              <h3 class="mb-0 praise-name"></h3>
              <span class="praise-singer"></span>
            </div>
            <div class="d-flex justify-content-center flex-wrap praise-hashtags" style="gap: .4rem"></div>
            <div class="d-flex mt-2">
              <div class="d-flex flex-row praise-youtube"></div>
              <div class="d-flex flex-row praise-cipher"></div>
              <div class="praise-no-refernce text-muted text-sm text-center mx-auto m-2 py-4 border rounded w-100">
                Este louvor não possui referências
              </div>
            </div>
            <form
              id="form-edit-praise"
              action="{{ route('praise.store') }}"
              method="POST"
              style="display: none;"
              class="pt-4"
            >
              {{ csrf_field() }}
              <input type="hidden" name="id" id="praise-id"/>
              @include('praise.partials.fields')
              <button
                type="submit"
                class="btn btn-sm bg-gradient-dark mt-3 mb-0 mx-auto d-block"
              >salvar</button>
            </form>
          </div>

          <button
            type="button"
            class="btn btn-sm bg-gradient-secondary mt-3 mb-0 mx-auto d-block"
            onclick="handleToggleFormEditPraise($(this))"
          >Editar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function callModalEditPraise(praise){
    handleResetEditPraise();
    console.log(praise);
    $('#modalEditPraise .praise-name').html(praise.name);
    $('#modalEditPraise .praise-singer').html(praise.singer);
    handleFillPraiseHashtags(praise);
    handleFillPraiseRefs(praise);
    handleFillPraiseForm(praise);

    $('#modalEditPraise').modal('show');
  }
  function handleResetEditPraise(){
    $('#modalEditPraise .praise-name, #modalEditPraise .praise-singer, #modalEditPraise .praise-hashtags, #modalEditPraise .praise-youtube, #modalEditPraise .praise-cipher').html('');
    $('#modalEditPraise .praise-no-refernce').hide();
    $('#form-edit-praise')[0].reset();
    $('#form-edit-praise').hide();
  }
  function handleFillPraiseHashtags(praise){
    let hashtags = praise.hashtags.map(hashtag => {
        return `<span class="badge text-dark">${ hashtag }</span>`;
      }).join('');
    $('#modalEditPraise .praise-hashtags').html(hashtags);
  }
  function handleFillPraiseRefs(praise){
    if(!praise.has_reference){
      $('#modalEditPraise .praise-no-refernce').show('slow');
      return ;
    }

    if(praise.youtubes) praise.youtubes.forEach(youtube => {
      $('#modalEditPraise .praise-youtube').append(`
        <div class="card mx-1">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <a
                href="${youtube.link}" target="_blank"
                class="avatar avatar-sm rounded-circle"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
              >
                <img
                  alt="Youtube"
                  src="{{ asset('assets/img/youtube.png') }}"
                  style="height: 100%; object-fit: cover;"
                />
              </a>
              <span class="text-sm ps-2">
                ${youtube.link}
              </span>
            </div>
          </div>
        </div>
      `);
    });
    if(praise.ciphers) praise.ciphers.forEach(cipher => {
      $('#modalEditPraise .praise-cipher').append(`
        <div class="card mx-1">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <a
                href="${cipher.link}" target="_blank"
                class="avatar avatar-sm rounded-circle"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
              >
                <img
                  alt="Cifras Club"
                  src="{{ asset('assets/img/cifras-club.png') }}"
                  style="height: 100%; object-fit: cover;"
                />
              </a>
              <span class="text-sm ps-2">
                ${cipher.link}
              </span>
            </div>
          </div>
        </div>
      `);
    });
  }
  function handleFillPraiseForm(praise){
    $('#praise-id').val(praise.id);
    $('#praise-name').val(praise.name);
    $('#praise-singer').val(praise.singer);
    $('#praise-youtube').val(praise.main_youtube ? praise.main_youtube.link : null);
    $('#praise-cipher').val(praise.main_cipher ? praise.main_cipher.link : null);
  }
  function handleToggleFormEditPraise(elem){
    if(elem.hasClass('bg-gradient-secondary')){
      elem.toggleClass('bg-gradient-secondary bg-gradient-danger').html('Cancelar');

      $('#form-edit-praise').show();
    }else{
      elem.toggleClass('bg-gradient-secondary bg-gradient-danger').html('Editar');
      $('#form-edit-praise').hide();
    }
  }
</script>