<div class="modal fade" id="modal-days" tabindex="-1" role="dialog" aria-labelledby="modal-days-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-days-title">Adicionar Dias</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-2">
        <div class="row">
          <div class="col-md-12">
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
                <button
                  type="button"
                  class="btn-day btn btn-sm bg-gradient-light px-3 {{ $d->is_current_month ? '':'opacity-7' }}"
                  onclick="handleAddOrRemoveDays('{{ $d->date_Ymd }}', $(this))"
                  data-weekday="{{ $d->weekday_index }}"
                >{{ $d->day }}</button>
              @endforeach
            </div>
          </div>
          <div class="col-md-12">
            <p class="text-sm mt-3">
              Selecione aqui os dias que você deseja adicionar escalas.<br/>
              Depois clique em prosseguir para adicionar e editar escalas existentes
            </p>
          </div>
          <div class="col-md-12 text-center">
            <button 
              type="button"
              class="btn bg-gradient-primary mt-3 mb-0"
              onclick="$('#modal-days').modal('hide');"
            >Finalizar Seleção</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function callModalDays(){
    $('#modal-days').modal('show');
  }
  function handleAddOrRemoveDays(date, el){
    let class_name = {
      active: 'bg-gradient-primary',
      normal: 'bg-gradient-light'
    };
    
    handleRenderDays(date, el.hasClass(class_name.active));
    if(el.hasClass(class_name.active)) el.removeClass(class_name.active)
      .addClass(class_name.normal);
    else el.removeClass(class_name.normal)
      .addClass(class_name.active);
  }
  function handleRenderDays(day, remove = false){
    if(remove){
      let index = days.indexOf(day);
      if(index !== -1) days.splice(index, 1);
    }else days.push(day);

    days.sort();

    $('#container-days-selected').html('');
    $('#container-days-to-scale').html('');
    days.forEach((day, i) => {
      let date = new Date(day);
      let index = date.getDay();
      
      let [y, m, d] = day.split('-');
      let weekday = weekdays[index];

      $('#container-days-to-scale').append(`
        <div
          class="info-horizontal ${ i === 0 ? 'bg-gradient-primary text-light':'bg-gray-100' } border-radius-xl p-3 card-to-add-days"
          data-date="${day}"
        >
          <div class="d-flex flex-column text-center">
            <div class="d-flex flex-column text-center" onclick="handleSelectDay('${day}', '${weekday}')">
              <b class="text-uppercase text-xxs">${translateWeekdays[weekday]}</b>
              <span style="font-size: 2rem; line-height: 2.6rem;">${d}/${m}</span>
            </div>
            <button
              class="btn btn-sm btn-link m-0 p-0"
              onclick="handleRenderDays('${day}', true)"
              style="
                color: inherit;
                margin-bottom: -.4rem !important;
              "
            >&times;</button>
          </div>
        </div>
      `);

      if(i === 0) handleSelectDay(day, weekday);
    });
  }
  function handleSelectDay(day, weekday, changeScaleDate = true){
    if(changeScaleDate) $('#scale-date').val(day);
    if(defaultHours[weekday] != '-') $('#scale-hour').val(defaultHours[weekday]);

    let cn = {
      active: 'bg-gradient-primary text-light',
      normal:'bg-gray-100'
    };

    $('.card-to-add-days').removeClass(cn.active).addClass(cn.normal);
    $(`.card-to-add-days[data-date=${day}]`).removeClass(cn.normal).addClass(cn.active);

    handleAvailabilityDay(day);
  }
  function handleAvailabilityDay(day){
    let data = findUserAvailability(day);
    let container = $('#current-availability-container');
    container.html('');
    if(!data || !data.data) return;

    data.data.forEach((user) => container.append(`
      <p class="text-sm mb-1 d-flex user-item text-start">
        <span
          class="badge d-block my-auto  me-2 p-0 ${ user.is_unavailable ? 'bg-gradient-danger' : 'bg-gradient-success' }"
          style="width: .4rem; height: .4rem;"
        ></span>
        ${ user.name }
      </p>
    `))
  }
  // BEGIN:: HANDLE CALENDAR
  let cacheCalendar = {};
  function handleUpdateCalendar(el){
    let url = el.attr('data-target');

    if(cacheCalendar[url]){
      renderCalendar(cacheCalendar[url]);
      return;
    }
    
    submitLoad();
    $.get(url).done((data) => {
      if(!data.result){
        notify('danger', data.response);
        return;
      }
      cacheCalendar[url] = data.response;
      renderCalendar(data.response);
    }).fail((err) => {
      console.error(err);
      notify('danger', 'Houve um erro ao alterar mês do calendário');
    }).always(() => stopLoad());
  }
  function renderCalendar(calendar){
    // BEGIN:: HEADER
    $('#modal-days-month-name').html(calendar.month_name);
    $('#btn-modal-days-prev').attr('data-target', calendar.link.prev);
    $('#btn-modal-days-next').attr('data-target', calendar.link.next);
    // END:: HEADER
    $('#modal-calendar-container .btn-day').remove();
    calendar.calendar.forEach(d => {
      $('#modal-calendar-container').append(`
        <button
          type="button"
          class="btn-day btn btn-sm bg-gradient-light px-3 ${ d.is_current_month ? '':'opacity-7' }"
          onclick="handleAddOrRemoveDays('${ d.date_Ymd }', $(this))"
          data-weekday="${ d.weekday_index }"
        >${ d.day }</button>
      `);
    });
  }
  // END:: HANDLE CALENDAR
</script>