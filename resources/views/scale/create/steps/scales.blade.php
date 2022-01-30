<div class="page-contents" id="content-scale" style="display: none;">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div
        class="table-responsive d-flex"
        style="gap: .6rem;"
        id="container-days-to-scale"
      ></div>
    </div>
    <div class="col-md-4">
      <label>Data</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Data"
          aria-label="Data"
          type="date"
          id="scale-date"
          required
        />
      </div>
    </div>
    <div class="col-md-4">
      <label>Hora</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Hora"
          aria-label="Hora"
          type="time"
          id="scale-hour"
          required
        />
      </div>
    </div>
    <div class="col-md-4">
      <label>Tema</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Tema"
          aria-label="Tema"
          type="text"
          id="scale-theme"
          required
        />
      </div>
    </div>
    <div class="col-12 mb-3">
      <div class="form-group mb-0 mt-md-0 mt-4">
        <label>Observação (opcional)</label>
        <textarea
          id="scale-obs"
          class="form-control"
          rows="3"
          placeholder="Digite aqui informações adicionais sobre a escala."
          maxlength="250"
        ></textarea>
      </div>
    </div>
    <div class="col-12">
      <div class="table-responsive" id="scale-in-creation">
        <table class="table table-hover align-items-center mb-0">
          <thead>
            <tr>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('ministro','Ministro')"
              >Ministro</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('backvocal','Vozes')"
              >Vozes</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('violao','Violão')"
              >Violão</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('baixo','Baixo')"
              >Baixo</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('guitarra','Guitarra')"
              >Guitarra</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('teclado','Teclado')"
              >Teclado</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('bateria','Bateria')"
              >Bateria</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('cajon','Cajon')"
              >Cajon</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('datashow','Datashow')"
              >Datashow</th>
              <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
                onclick="handleAddScaleUser('mesario','Mesário')"
              >Mesário</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-xs">
              <td id="td-react-to-ministro"></td>
              <td id="td-react-to-backvocal"></td>
              <td id="td-react-to-violao"></td>
              <td id="td-react-to-baixo"></td>
              <td id="td-react-to-guitarra"></td>
              <td id="td-react-to-teclado"></td>
              <td id="td-react-to-bateria"></td>
              <td id="td-react-to-cajon"></td>
              <td id="td-react-to-datashow"></td>
              <td id="td-react-to-mesario"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div id="dynamic-input-content" style="display: none;">
        <label></label>
        <div class="input-group mb-3">
          <input
            class="form-control"
            type="text"
            id="dynamic-input"
            onkeyup="reactDynamicInput()"
          />
        </div>
      </div>
      <div id="users-available" style="display: none;">
        <div class="table-responsive">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Integrante</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Disponibilidades</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-12 text-center">
      <button 
        type="button" class="btn bg-gradient-primary mt-3 mb-0"
        onclick="createScale()"
      >Criar Escala</button>
    </div>
    <div class="col-12">
      <hr class="mt-6"/>
      <div class="table-responsive" id="scales-created">
        <table class="table table-hover align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Data/Tema</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ministro</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vozes</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Violão</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Baixo</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guitarra</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Teclado</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bateria/Cajon</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Datashow</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mesário</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr id="tr-empty">
              <td
                colspan="10"
                class="text-sm text-center"
              >Nenhuma escala finalizada</td>
            </tr>
          </tbody>
        </table>
      </div>
      <button
        type="button"
        class="btn btn-sm btn-link text-dark text-center mx-auto d-block"
        onclick="loadLastScales()"
      >Carregar Anteriores</button>
    </div>
  </div>
</div>
<script>
  var currentAbilityInChange = null;
  var scaleInEdition = null;
  var users_scaled = {
    ministro:'',
    backvocal:'',
    violao:'',
    baixo:'',
    guitarra:'',
    teclado:'',
    bateria:'',
    cajon:'',
    datashow:'',
    mesario:'',
  };
  var scale_ids = [];
  async function createScale(){
    let obs = $('#scale-obs').val();
    let data = {
      date: $('#scale-date').val(),
      hour: $('#scale-hour').val(),
      theme: $('#scale-theme').val(),
      obs: obs.length > 0 ? obs : null,
      users_scaled,
      id: scaleInEdition ? scaleInEdition.id : null,
    };
    
    $.post('{{ route('scale.store') }}', data).done(data => {
      if(data.result){
        scale_ids.push(data.response.id);
        if($('#tr-empty')[0])$('#tr-empty').remove();
        console.log('>>>', data);
        if(scaleInEdition) handleScaleEdited(data.response);
        else $('#scales-created tbody').prepend(htmlScaleFinalized(data.response));
        handleNextScale();
      }else callModalMessage(data.response);
    });
  }
  async function handleAddScaleUser(ability, name){
    $('#dynamic-input-content').show('slow');
    $('#dynamic-input-content label').html(name);
    currentAbilityInChange = ability;
    $('#dynamic-input').focus().val(
      users_scaled[currentAbilityInChange]
    );

    $('#users-available').show();
    $('#users-available tbody').html('');

    let url = `{{ substr(route('ability.search', ['ability' => 0]),0,-1) }}${ ability == 'backvocal' ?'back-vocal' : ability }`;
    $.get(url).done(data => {
      if(data.result){
        if(data.response.length > 0){
          data.response.forEach(user => {
            $('#users-available tbody').append(htmlAvailableUsers(user));
          });
        }else{
          $('#users-available tbody').html(`
            <tr>
              <td colspan="2" class="text-sm text-center text-muted">
                Nenhum integrante com essa habilidade
              </td>
            </tr>
          `);
        }
      }
      else callModalMessage(data.response);
    });
  }
  function htmlAvailableUsers(user){
    let availabilities = user.availability_formatted.map(availability => {
      return `
        <span class="badge bg-dark">${ availability }</span>
      `;
    }).join(' ');
    return `
      <tr>
        <td>
          <div class="d-flex px-2 py-1">
            <div>
              <img src="${ user.profile_formatted }" class="avatar avatar-sm me-3">
            </div>
            <div class="d-flex flex-column justify-content-center">
              <a href="{{ route('user.profile') }}/${ user.email }" target="_blank">
                <h6 class="mb-0 text-xs">${ user.nickname ?? '-' }</h6>
                <p class="text-xs text-secondary mb-0">${ user.name}</p>
              </a>
            </div>
          </div>
        </td>
        <td>
          ${ availabilities }
        </td>
      </tr>
    `;
  }
  function reactDynamicInput(){
    if(!currentAbilityInChange) return;
    let dynamic = $('#dynamic-input').val();
    users_scaled[currentAbilityInChange] = dynamic;
    $(`#td-react-to-${currentAbilityInChange}`).html(dynamic);
  }
  function htmlScaleFinalized(scale, withoutWrapper = false){
    let content = `
      <td onclick='handleEditScale(${JSON.stringify(scale)})'> 
        <div class="d-flex align-items-center">
          <span
            class="p-1 pe-2 text-lg font-weight-bold"
            data-bs-toggle="tooltip" data-bs-placement="bottom" title="${ scale.date }"
          >${ scale.day }</span>
          <div class="text-sm">
            <strong class="d-block text-dark text-uppercase">
              ${ scale.weekday_name }
            </strong>
            <span>${ scale.theme }</span>
          </div>
        </div>
        </td>
      <td class="text-sm">${ scale.resume_table.ministro }</td>
      <td class="text-sm">${ scale.resume_table.backvocal }</td>
      <td class="text-sm">${ scale.resume_table.violao }</td>
      <td class="text-sm">${ scale.resume_table.baixo }</td>
      <td class="text-sm">${ scale.resume_table.guitarra }</td>
      <td class="text-sm">${ scale.resume_table.teclado }</td>
      <td class="text-sm">
        ${ scale.resume_table.bateria != '-' ? scale.resume_table.bateria : scale.resume_table.cajon }
      </td>
      <td class="text-sm">${ scale.resume_table.datashow }</td>
      <td class="text-sm">${ scale.resume_table.mesario }</td>
      <td class="text-sm">
        <button
          type="button"
          class="btn btn-sm btn-link text-dark px-1 mb-0"
          onclick="handleDelete(${scale.id})"
        >@include('utils.icons.trash',['icon' => (object)[
          'width' => '18px',
          'height' => '18px'
        ]])</button>
      </td>
    `;
    return withoutWrapper ? content : `
      <tr class="${scale.weekday == 'sunday' ? 'tr-highlight':'' }" id="tr-scale-id-${scale.id}">
        ${ content }
      </tr>
    `;
  }
  function handleScaleEdited(scale){
    console.log('edited',`#tr-scale-id-${scale.id}`, scale);
    $(`#tr-scale-id-${scale.id}`).html(
      htmlScaleFinalized(scale, true)
    );
    if(scale.weekday == 'sunday') $(`#tr-scale-id-${scale.id}`).addClass('tr-highlight');
    else $(`#tr-scale-id-${scale.id}`).removeClass('tr-highlight');
  }
  function handleNextScale(){
    handleRenderDays($('#scale-date').val(), true);
    scaleInEdition = null;
    let abilities = ['ministro','backvocal','violao','baixo','guitarra','teclado','bateria','cajon','datashow','mesario'];
    abilities.forEach(ability => {
      users_scaled[ability] = '';
      $(`#td-react-to-${ability}`).html('');
    });
    $('#scale-obs').val('');
    $('#dynamic-input').val('');
    $('#users-available tbody').html('');
    $('#scale-theme').val('').focus();
  }
  function loadLastScales(){
    let ids = scale_ids.length > 0 ? '/' + scale_ids.join(',') : '';
    
    $.get(`{{ route('scale.last') }}${ids}`).done(data => {
      if(data.result){
        if($('#tr-empty')[0])$('#tr-empty').remove();
        data.response.forEach(scale => {
          scale_ids.push(scale.id);
          $('#scales-created tbody').append(htmlScaleFinalized(scale));
        });
      }
    })
  }
  function handleEditScale(scale){
    $('#container-days-to-scale .bg-gradient-primary').toggleClass('bg-gradient-primary bg-gray-100')
    $('#container-days-to-scale .text-light').removeClass('text-light');
    scaleInEdition = scale;

    let abilities = ['ministro','backvocal','violao','baixo','guitarra','teclado','bateria','cajon','datashow','mesario'];
    users_scaled = scale.resume_table
    abilities.forEach(ability => {
      $(`#td-react-to-${ability}`).html(users_scaled[ability]);
    });
    $('#scale-obs').val(scale.obs);
    $('#dynamic-input').val('');
    $('#users-available tbody').html('');
    $('#scale-theme').val(scale.theme).focus();
    $('#scale-date').val(scale.date);
    $('#scale-hour').val(scale.hour);
  }
  function handleDelete(id){
    callModalMessage(`
      <p>Tem certeza que deseja excluir essa escala?</p>
      <button
        type="button" class="btn bg-gradient-primary mt-3 mb-0"
        onclick="deleteScale(${id})"
      >Sim</button>
      <button
        type="button" class="btn btn-link text-dark mt-3 mb-0"
        data-bs-dismiss="modal"
      >Não</button>
    `);
  }
  function deleteScale(id){
    $.get(`{{ substr(route('scale.delete',['id' => 0]),0,-1) }}${id}`).done(data => {
      if(data.result){
        callModalMessage(data.response);
        $(`#tr-scale-id-${id}`).remove();
      }
      else callModalMessage(data.response);
    })
  }
</script>