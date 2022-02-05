<!-- Modal -->
<div class="modal fade" id="modalScaled" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Escala</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="rounded bg-gradient-light p-2 scale-header"></div>
        <div class="row mt-2">
          <div
            class="col-6 text-center text-sm opacity-7 btn btn-link mb-0"
            onclick="tooglePraiseToIntegrants(false)"
            id="btn-to-scale-praises"
          >LOUVORES</div>
          <div
            class="col-6 text-center text-sm opacity-7 btn btn-link text-dark mb-0"
            onclick="tooglePraiseToIntegrants()"
            id="btn-to-integrants"
          >INTEGRANTES</div>
        </div>
        <div id="scale-praises"></div>
        <div class="table-responsive mt-3 scale-table-scaled">
          <table class="table align-items-center mb-3">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Função</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Escalados</th>
              </tr>
            </thead>
            <tbody class="text-sm"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function callModalScaled(scale){
    let [_,month,day] = scale.date.split('-');
    $('#modalScaled .modal-title').html(`Escala ${day}/${month}`);
    
    $('#modalScaled .modal-body .scale-header').html(`
      <h6 class="mb-0">Tema: ${scale.theme}</h6>
      <span class="text-sm text-muted">${scale.weekday_name} - ${scale.hour}</span>
    `);


    $('#scale-praises').html('');
    if(scale.minister_scales && scale.minister_scales.length > 0) scale.minister_scales.forEach(minister => {
      $('#scale-praises').append(handleFillPraiseScale(minister));
    })
    else{
      if(scale.need_make_scale) $('#scale-praises').html(`
        <a
          href="{{ route('scale_praise.create') }}/${scale.id}"
          class="btn bg-gradient-primary d-block mt-3 text-center mx-auto"
          style="width: fit-content; max-width: 100%;"
        >Adicionar Ministração</a>      
      `);
      else $('#scale-praises').html(`<p class="text-muted text-sm my-2 bg-gradient-light w-100 p-3 rounded">A escala de louvores ainda não foi adicionada</p>`);
    }

    $('#modalScaled .modal-body .scale-table-scaled tbody').html(scale.resume.map(item => {
      return `
        <tr>
          <th>${handleFormatteFunction(item.ability)}</th>
          <td>${item.users.join(', ')}</td>
        </tr>
      `;
    }).join(' '));

    tooglePraiseToIntegrants(false);

    $('#modalScaled').modal('show');
  }
  function handleFormatteFunction(index){
    let functions = {
      "ministro": "Ministro",
      "violao": "Violão",
      "back-vocal": "Backvocal",
      "backvocal": "Backvocal",
      "baixo": "Baixo",
      "cajon": "Cajon",
      "bateria": "Bateria",
      "datashow": "Datashow",
      "guitarra": "Guitarra",
      "mesario": "Mesário",
      "teclado": "Teclado",
    }
    return functions[index] ?? index;
  }
  function handleFillPraiseScale(scale){
    let listPraises = scale.scale_praises.map(praise => {
      return `
        <li class="list-group-item py-1 d-flex align-items-center justify-content-between">
          <span>${praise.praise.name + (praise.tone ? ` - ${praise.tone}`:'')}</span>
          <div>
            ${praise.youtube_link ? `
              <a
                href="${praise.youtube_link}" target="_blank"
                class="avatar avatar-xs rounded-circle"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
              >
                <img
                  alt="Youtube"
                  src="{{ asset('assets/img/youtube.png') }}"
                  style="height: 100%; object-fit: cover;"
                />
              </a>
            `:''}
            ${praise.cipher_link ? `
              <a
                href="${praise.cipher_link}" target="_blank"
                class="avatar avatar-xs rounded-circle"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cifra"
              >
                <img
                  alt="Cifras Club"
                  src="{{ asset('assets/img/cifras-club.png') }}"
                  style="height: 100%; object-fit: cover;"
                />
              </a>
            `:''}
          </div>
        </li>
      `;
    }).join('');

    return `
      <div class="d-flex align-items-center mb-1">
        <a
          href="{{ route('user.profile') }}/${scale.user.email}" target="_blank"
          class="avatar avatar-sm rounded-circle"
          data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
        >
          <img
            alt="Ministro"
            src="${ scale.user.profile_formatted }"
            style="height: 100%; object-fit: cover;"
          />
        </a>
        <h6 class="mb-0 ms-2">
          ${ scale.user.name }
        </h6>
      </div>
      <ul class="list-group list-group-flush">
        ${listPraises}
      </ul>

      ${scale.playlist ? `
        <a
          target="_blank"
          href="${scale.playlist}"
          class="btn btn-sm bg-gradient-primary mt-2 mb-4"
        >Ouvir Playlist</a>
      `:''}
      <br/>
      ${scale.verse ? `<strong>${scale.verse}</strong>` : ''}
      ${scale.about ? `<p class="text-sm">${scale.about}</p>` : ''}
    `;
  }
  function tooglePraiseToIntegrants(toIntegrants = true){
    $(toIntegrants ? '#btn-to-scale-praises' : '#btn-to-integrants').addClass('text-dark');
    $(toIntegrants ? '#btn-to-integrants' : '#btn-to-scale-praises').removeClass('text-dark');
    $(toIntegrants ? '#scale-praises':'#modalScaled .modal-body .scale-table-scaled').hide();
    $(toIntegrants ? '#modalScaled .modal-body .scale-table-scaled':'#scale-praises').show('slow');
  }
</script>