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
        <div class="pb-2 scale-header"></div>
        <div
          class="row mt-2"
          style="background: #34476708;"
        >
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
          <table class="table table-hover align-items-center mb-3">
            <tbody class="text-sm"></tbody>
          </table>
          <div class="text-center content-btn-share"></div>
        </div>
        @if(auth()->user()->currentMinistry->hasPermissionTo('can_manage_scale'))
          <a
            href="#"
            target="_blank"
            class="btn btn-sm bg-gradient-info d-block mt-3 text-center mx-auto"
            style="width: fit-content; max-width: 100%;"
            id="to-edit-scale"
          >Editar</a>
        @endif
      </div>
    </div>
  </div>
</div>
<script>
  const auth_id = {{ auth()->user()->id }};
  function callModalScaled(scale){
    let [_,month,day] = scale.date.split('-');
    $('#modalScaled .modal-title').html(`Escala ${day}/${month}`);
    
    $('#modalScaled .modal-body .scale-header').html(`
      <h6 class="mb-0">Tema: ${scale.theme}</h6>
      <span class="text-sm text-muted">${scale.weekday_name} - ${scale.hour}</span>
    `);


    let header = `*Escala ${day}/${month} | ${scale.weekday_name} - ${scale.hour}*\n_${scale.theme}_\n\n`;
    
    $('#scale-praises').html('');
    if(scale.minister_scales && scale.minister_scales.length > 0) scale.minister_scales.forEach(minister => {
      $('#scale-praises').append(handleFillPraiseScale(minister, header));
    })
    else{
      if(scale.need_make_scale) $('#scale-praises').html(`
        <a
          href="{{ route('scale_praise.create') }}/${scale.id}"
          class="btn bg-gradient-primary d-block mt-3 text-center mx-auto"
          style="width: fit-content; max-width: 100%;"
        >Adicionar Ministração</a>      
      `);
      else $('#scale-praises').html(`
        <p class="text-muted text-sm my-2 w-100 p-3 text-center">
          A escala de louvores ainda não foi adicionada
        </p>
      `);
    }

    let shareUsers = [];
    $('#modalScaled .modal-body .scale-table-scaled tbody').html(scale.resume.map(item => {
      let ability = handleFormatteFunction(item.ability);
      let users = item.users.join(', ');
      if(users !== '-') shareUsers.push(`*${ability}:* ${users}`);

      return `
        <tr>
          <td
            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
            style="width: 6rem"
          >${ability}</td>
          <td>${users}</td>
        </tr>
      `;
    }).join(' '));

    let shareScaled = encodeURIComponent(header + shareUsers.join('\n'));
    $('#modalScaled .modal-body .scale-table-scaled .content-btn-share').html(
      handleHtmlShare(shareScaled)
    );

    tooglePraiseToIntegrants(false);

    @if(auth()->user()->currentMinistry->hasPermissionTo('can_manage_scale'))
      $("#to-edit-scale").attr('href',`{{ substr(route('scale.edit', ['id' => 0]),0,-1) }}${scale.id}`);
    @endif
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
  function handleFillPraiseScale(scale, header){
    let sharePraises = [];
    let listPraises = scale.scale_praises.map(praise => {
      let description =
        (praise.legend ? `${praise.legend}: ` : '') + 
         praise.praise.name + 
        (praise.tone ? ` - ${praise.tone}` : '')
      ;
      sharePraises.push(`- ${description.trim()}`);
      return `
        <li class="list-group-item py-1 d-flex align-items-center justify-content-between">
          <span>
            ${description}
            <em class="d-block text-xs">${ praise.praise.singer }</em>
          </span>
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

    if(scale.playlist) sharePraises.push(`\n${scale.playlist}`);
    if(scale.verse) sharePraises.push(`\n*${scale.verse}*`);
    if(scale.about) sharePraises.push(`${!scale.verse?'\n':''}${scale.about}`);

    let shareMinistering = encodeURIComponent(header + sharePraises.join('\n'));

    return `
      <div class="d-flex align-items-center mb-1 mt-3">
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
        <a
          href="${scale.user_id == auth_id ? `{{ substr(route('scale_praise.edit', ['id' => 0]),0,-1) }}${scale.id}`:'javascript:;' }"
          class="mx-2" style="letter-spacing: inherit; flex: 1;"
        >
          <h6 class="mb-0 ms-2">
            ${ scale.user.name }
          </h6>
        </a>
      </div>
      <ul class="list-group list-group-flush">
        ${listPraises}
      </ul>

      ${scale.playlist ? `
        <a
          target="_blank"
          href="${scale.playlist}"
          class="btn btn-sm bg-gradient-primary mt-2 mb-2"
        >Ouvir Playlist</a>
      `:''}
      ${ handleHtmlShare(shareMinistering) }
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
  function handleHtmlShare(share){
    return `
      <a
        target="_blank"
        href="https://api.whatsapp.com/send?text=${share}"
        class="btn btn-sm bg-gradient-dark mt-2 mb-2"
      >@include('utils.icons.share', ['icon' => (object)[
        'width' => '18px',
        'height' => '18px',
      ]])</a>
    `;
  }
</script>