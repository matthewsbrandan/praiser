  <style>
    .modal-backdrop + .modal-backdrop{ z-index: 1051; }
    .btn-hover, .user-of-ministry-item{ transition: .6s; }
    .btn-hover:hover, .user-of-ministry-item:hover{
      background: #dddde3dd;
    } 
  </style>
<div class="modal fade" id="modal-user-availability" tabindex="-1" role="dialog" aria-labelledby="modal-user-availability-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-user-availability-title">Quadro de Disponibilidades</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <button
            class="btn btn-transparent shadow-none px-2 mb-0"
            id="btn-modal-days-prev"
            onclick="handleUpdateCalendar($(this))"
            data-target="{{ $calendar->link->prev }}"
          >
            @include('utils.icons.larr',['icon' => (object)['width' => '1rem', 'height' => '1rem']])
          </button>
          <h5 class="text-gradient text-dark mb-0" id="modal-days-month-name">{{ $calendar->month_name }}</h5>
          <button
            class="btn btn-transparent shadow-none px-2 mb-0"
            id="btn-modal-days-next"
            onclick="handleUpdateCalendar($(this))"
            data-target="{{ $calendar->link->next }}"
          >
            @include('utils.icons.rarr',['icon' => (object)['width' => '1rem', 'height' => '1rem']])
          </button>
        </div>
        <div class="table-responsive mx-auto" id="modal-calendar-container" style="
          display: grid;
          grid-template-columns: repeat(7, 1fr);
          gap: .4rem;
        ">
          <div
            class="font-weight-bold text-dark text-center text-uppercase text-sm"
            onclick="$('.btn-day[data-weekday=1]').click()"
          >Seg</div>
          <div
            class="font-weight-bold text-dark text-center text-uppercase text-sm"
            onclick="$('.btn-day[data-weekday=2]').click()"
          >Ter</div>
          <div
            class="font-weight-bold text-dark text-center text-uppercase text-sm"
            onclick="$('.btn-day[data-weekday=3]').click()"
          >Qua</div>
          <div
            class="font-weight-bold text-dark text-center text-uppercase text-sm"
            onclick="$('.btn-day[data-weekday=4]').click()"
          >Qui</div>
          <div
            class="font-weight-bold text-dark text-center text-uppercase text-sm"
            onclick="$('.btn-day[data-weekday=5]').click()"
          >Sex</div>
          <div
            class="font-weight-bold text-dark text-center text-uppercase text-sm"
            onclick="$('.btn-day[data-weekday=6]').click()"
          >Sáb</div>
          <div
            class="font-weight-bold text-dark text-center text-uppercase text-sm"
            onclick="$('.btn-day[data-weekday=0]').click()"
          >Dom</div>

          @foreach($calendar->calendar as $d)
            <div
              class="border border-radius-md px-2 text-center {{ $d->is_current_month ? '':'opacity-7' }} modal-user-availability-item"
              onclick="handleExpandDate('{{ $d->date_Ymd }}')"
              data-weekday="{{ $d->weekday_index }}"
              data-date="{{ $d->date_Ymd }}"
              style="min-height: 4rem;"
            >
              <span class="day">{{ $d->day }}</span>
              <div class="content-users" style="max-height: 2.8rem; overflow: hidden;">
                @foreach($d->data as $user)
                  <p class="text-xs mb-1 d-flex user-item text-start">
                    <span
                      class="badge d-block my-auto  me-2 p-0 {{ $user->is_unavailable ? 'bg-gradient-danger' : 'bg-gradient-success' }}"
                      style="width: .4rem; height: .4rem;"
                    ></span>
                    {{ $user->name }}
                  </p>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-expand-user-availability" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1052;">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body py-2">
        <button
          type="button"
          class="btn-close text-dark"
          data-bs-dismiss="modal" aria-label="Close"
          style="
            position: absolute;
            right: .5rem;
          "
        ><span aria-hidden="true">&times;</span></button>

        <p
          id="modal-expand-user-availability-weekday"
          class="text-center text-xxs text-uppercase mt-2" 
          style="margin-bottom: -.3rem;"
        ></p>
        <h5 class="text-center" id="modal-expand-user-availability-date"></h5>
        <div id="modal-expand-user-availability-users" style="min-height: 6rem;"></div>
        
        {{-- BEGIN:: ADD DISP/INDISP --}}
        <p
          class="text-center text-xs text-uppercase text-dark font-weight-bold mb-2 mt-3"
          onclick="$(this).next().toggle('slow'); $('#filter-users-of-ministry-to-available').focus();"
        >Adicionar Disp/Indisp</p>
        <div style="display: none;">
          <div class="input-group mb-1">
            <input
              class="form-control form-control-sm"
              id="filter-users-of-ministry-to-available"
              onkeyup="handleFilterUserOfMinistry($(this))"
              placeholder="Filtrar usuários"
              type="text"
            />
          </div>
          <div class="form-check form-switch mb-2">
            <input
              class="form-check-input"
              type="checkbox"
              role="switch"
              id="check-user-unavailable"
              style="transform: scale(.8);"
              checked
            >
            <label
              class="form-check-label text-sm"
              for="check-user-unavailable"
              style="margin-left: -.2rem;"
            >Indisponibilidade</label>
          </div>
          <div class="container-users" style="
            max-height: 10rem;
            overflow: auto;
          ">
            @foreach($usersOfMinistry as $userM)
              <div class="user-of-ministry-item border-radius-lg" data-name="{{ $userM->nickname }}">
                <div class="d-flex px-2 py-1">
                  <div>
                    <img src="{{ $userM->profile_formatted }}" class="avatar avatar-sm me-3">
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <a href="javascript:;" onclick="handleAddDispOrIndisp('{{ $userM->user_id }}')">
                      <h6 class="mb-0 text-xs">{{ $userM->nickname ?? '-' }}</h6>
                      <p class="text-xs text-secondary mb-0">{{ $userM->name }}</p>
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <p class="text-xs text-center mt-3 mb-1">Clique para adicionar</p>
        </div>
        {{-- END:: ADD DISP/INDISP --}}
      </div>
    </div>
  </div>
</div>
<script>
  let userAvailabilities = {!!
    json_encode(
      array_map(function($d){
        return (object)[
          'date' => $d->date_Ymd,
          'day' => $d->day,
          'weekday_index' => $d->weekday_index,
          'data' => $d->data
        ];
      }, $calendar->calendar)
    )
  !!};
  let currentUserAvailability = null;
  function findUserAvailability(date){
    return userAvailabilities.find(user => user.date === date);
  }
  function handleUpdateUserAvailability(date, data){
    let user = findUserAvailability(date);
    if(!user){
      notify('danger', 'Houve um erro ao atualizar data');
      return;
    }

    user.data = data;
    $(`.modal-user-availability-item[data-date=${date}] .content-users`).html(
      data.map(u => {
        return renderUserAvailability(u)
      }).join(' ')
    );
    if(currentUserAvailability && currentUserAvailability.date == date){
      $('#modal-expand-user-availability-users').html(data.map(u => {
        return renderUserAvailability(u, 'md', true)
      }).join(' ')); 
    }
  }
  function callModalUserAvailability(){
    $('#modal-user-availability').modal('show');
  }
  function handleExpandDate(date){
    currentUserAvailability = null;
    let user = findUserAvailability(date);
    if(!user){
      notify('danger', 'Houve um erro ao carregar data');
      return;
    }
    currentUserAvailability = user;

    $('#modal-expand-user-availability-weekday').html(
      translateWeekdays[weekdays[
        user.weekday_index == 0 ? 6 : user.weekday_index - 1
      ]].substr(0,3)
    );
    $('#modal-expand-user-availability-date').html(user.day);
    $('#modal-expand-user-availability-users').html(user.data.map(user => {
      return renderUserAvailability(user, 'md', true)
    }).join(' '));
    $('#modal-expand-user-availability').modal('show');
  }
  function renderUserAvailability(user, text_len = 'xs', actionRemove = false){
    return `
      ${ actionRemove ? `
      <div
        class="d-flex justify-content-between align-items-center border-radius-lg px-1 btn-hover"
        onclick="handleRemoveDispOrIndisp(${user.id},${user.user_id})"
      >` : '' }
        <p class="text-${text_len} mb-1 d-flex user-item text-start">
          <span
            class="badge d-block my-auto  me-2 p-0 ${ user.is_unavailable ? 'bg-gradient-danger' : 'bg-gradient-success' }"
            style="width: .4rem; height: .4rem;"
          ></span>
          ${ user.name }
        </p>
      ${ actionRemove ? `
        @include('utils.icons.trash',['icon' => (object)[
          'width' => '16px',
          'height' => '16px'
        ]])
      </div>
      `:''}
    `;
  }
  function handleFilterUserOfMinistry(el){
    let search = el.val();
    $('.user-of-ministry-item').each(function(){
      if($(this).attr('data-name').includes(search)) $(this).show();
      else $(this).hide();
    });
  }
  function handleAddDispOrIndisp(user_id){
    if(!currentUserAvailability){
      notify('danger','Erro ao lidar com essa data');
      return;
    }

    let data = {
      user_id,
      date: currentUserAvailability.date
    };
    if(!$('#check-user-unavailable').prop('checked')) data = {
      ...data,
      is_available: true
    };

    submitLoad();
    $.post('{{ route('user_availability.store') }}', data).done((data) => {
      if(!data.result){
        notify('danger',data.response);
        return;
      }

      handleUpdateUserAvailability(currentUserAvailability.date, data.response);
      notify('success', 'Adicionado com sucesso');
    }).fail((err) => {
      console.error(err);
      notify('danger','Houve um erro ao executar está ação');
    }).always(() => stopLoad());
  }
  function handleRemoveDispOrIndisp(id, user_id){
    if(!currentUserAvailability){
      notify('danger', 'Não foi possível identificar a data');
      return;
    }
    
    let user = currentUserAvailability.data.find(u => u.id === id);
    if(!user){
      notify('danger', 'Não foi possível localizar usuário nessa data');
      return
    }

    let is_interval = false;
    if(user.date_final){
      if(!window.confirm(
        'Essa disponibilidade/indis. é um intervalo de tempo, se excluí-la irá remover todo o intervalo de tempo. Deseja continuar?'
      )) return;

      is_interval = true;
    }

    submitLoad();
    $.post('{{ route('user_availability.delete') }}', {
      id,
      user_id
    }).done((data) => {
      if(!data.result){
        notify('danger', data.response)
        return;
      }

      notify('success', 'Excluído com sucesso. Essa página atualizará em alguns segundos');
      setTimeout(() => window.location.reload(), 3000);
    }).fail((err) => {
      console.error(err);
      notify('danger', 'Houve um erro ao realizar essa ação');
    }).always(() => stopLoad());
  }
</script>