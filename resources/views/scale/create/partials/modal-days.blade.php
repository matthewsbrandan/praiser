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
                data-target="{{ $calendar->link->prev }}"
              >
                @include('utils.icons.larr',['icon' => (object)['width' => '1rem', 'height' => '1rem']])
              </button>
              <h5 class="text-gradient text-dark mb-0">{{ $calendar->month_name }}</h5>
              <button
                class="btn btn-transparent shadow-none px-2 mb-0"
                id="btn-modal-days-next"
                data-target="{{ $calendar->link->next }}"
              >
                @include('utils.icons.rarr',['icon' => (object)['width' => '1rem', 'height' => '1rem']])
              </button>
            </div>
            <div class="mx-auto" style="
              display: grid;
              grid-template-columns: repeat(7, 1fr);
              gap: .4rem;
            ">
              <div class="font-weight-bold text-dark text-center text-uppercase text-sm">Seg</div>
              <div class="font-weight-bold text-dark text-center text-uppercase text-sm">Ter</div>
              <div class="font-weight-bold text-dark text-center text-uppercase text-sm">Qua</div>
              <div class="font-weight-bold text-dark text-center text-uppercase text-sm">Qui</div>
              <div class="font-weight-bold text-dark text-center text-uppercase text-sm">Sex</div>
              <div class="font-weight-bold text-dark text-center text-uppercase text-sm">Sáb</div>
              <div class="font-weight-bold text-dark text-center text-uppercase text-sm">Dom</div>

              @foreach($calendar->calendar as $d)
                <button
                  type="button"
                  class="btn btn-sm bg-gradient-light px-3 {{ $d->is_current_month ? '':'opacity-7' }}"
                  onclick="handleAddDays('{{ $d->date_formatted }}')"
                >
                  {{ $d->day }}
                </button>
              @endforeach
            </div>
          </div>
          <div class="col-md-12">
            <div class="d-flex flex-wrap" style="gap: .6rem;" id="container-days-selected"></div>
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
    $('#scale-days').focus();
  }
  function handleAddDays(){
    let day = $('#scale-days').val();
    if(!day){
      focusInError($('#scale-days'));
      notify('warning', 'Marque uma data antes de adicionar');
      return;
    }
    handleRenderDays(day);
    $('#scale-days').val('').focus();
  }
  function handleRenderDays(day, remove = false){
    if(remove){
      let index = days.indexOf(day);
      if(index !== -1) days.splice(index, 1);
    }else days.push(day);

    $('#container-days-selected').html('');
    $('#container-days-to-scale').html('');
    days.forEach((day, i) => {
      let date = new Date(day);
      let index = date.getDay();
      // if(index == 7) index = 0;
      let [y, m, d] = day.split('-');
      let weekday = weekdays[index];
      let content = `
        <b class="text-uppercase text-sm">${translateWeekdays[weekday]}</b>
        <span style="font-size: 2rem; line-height: 2.6rem;">${d}/${m}</span>
      `;

      $('#container-days-selected').append(handleAddCard(`
        <div class="d-flex flex-column text-center text-light">
          ${ content }
          <button
            class="btn btn-sm btn-link text-light m-0 p-0"
            onclick="handleRenderDays('${day}', true)"
          >&times;</button>
        </div>
      `, true, 3));

      $('#container-days-to-scale').append(handleAddCard(`
        <div class="d-flex flex-column text-center ${i === 0 ? 'text-light':''}">
          ${ content }
        </div>
      `, i === 0, 3));

      if(i === 0){
        $('#scale-date').val(day);
        if(defaultHours[weekday] != '-') $('#scale-hour').val(defaultHours[weekday]);
      }
    });
  }
</script>