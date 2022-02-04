<?php
  $disable = [
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  <style>
    .tr-highlight{
      --bs-table-accent-bg: var(--bs-table-hover-bg);
      color: var(--bs-table-hover-color);
    }
  </style>
  <header>
    <div class="page-header min-vh-85">
      <div>
        <img class="position-absolute fixed-top ms-auto w-50 h-100 z-index-0 d-none d-sm-none d-md-block border-radius-section border-top-end-radius-0 border-top-start-radius-0 border-bottom-end-radius-0" src="{{ asset('assets/img/curved-images/curved8.jpg') }}" alt="image">
      </div>
      <div class="container">
        <div class="row">
          <div class="col-12 d-flex justify-content-center flex-column mt-sm-4">
            <div class="card d-flex blur justify-content-center p-4 shadow-lg my-sm-0 my-sm-6 mt-8 mb-5">
              <div class="text-center">
                <h3 class="text-gradient text-primary">Gerenciar Escala</h3>
                <p class="mb-0">
                  Cadastre as escalas aqui e organize sua equipe.
                </p>
              </div>              
              <div class="card-body pb-2">
                @if($import)
                  <form
                    id="contact-form"
                    method="post"
                    autocomplete="off"
                    action="{{ route('scale.store') }}"
                    enctype="multipart/form-data"
                  >
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label>Clique para importar sua planilha</label>
                      <input type="file" class="form-control" name="import" required/>
                    </div>
                  </form>
                @else
                  @include('utils.pagination',['pagination' => [
                    (object)[
                      'id' => 'to-content-days', 'onclick' => 'handlePagination(1)'
                    ],(object)[
                      'id' => 'to-content-scale', 'onclick' => 'handlePagination(2)'
                    ]
                  ],'pagination_config' => (object)[
                    'disabled' => false
                  ]])
                  
                  @include('scale.create.steps.days')
                  @include('scale.create.steps.scales')
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
@endsection
@section('scripts')
  <script>
    var days = [];
    const weekdays = {!! json_encode(array_keys(\App\Models\User::getAvailableWeekdays())) !!};
    const defaultHours = {!! json_encode(\App\Models\Scale::getAvailableHoursByWeekday()) !!}
    const translateWeekdays = {!! json_encode(\App\Models\User::getAvailableWeekdays()) !!}
    const icon = {
      trash:`@include('utils.icons.trash',['icon' => (object)['width' => '18px','height' => '18px']])`,
      word: `@include('utils.icons.word',['icon' => (object)['width' => '18px','height' => '18px']])`,
      lock: `@include('utils.icons.lock',['icon' => (object)['width' => '18px','height' => '18px']])`
    };
    // BEGIN:: DAYS
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
    // END:: DAYS

    const pages = [
      'content-days',
      'content-scale',
    ];
    var pagesValidated = [];

    function handlePagination(to){
      pagesValidated = pagesValidated.filter(pages => pages != $('.to-page-contents.active').attr('id').substr(3));
      switch(to){
        case 2: $('#scale-theme').focus(); break;
      }
      $('.page-contents').hide();
      $(`#${pages[to - 1]}`).show('slow');
      $('.to-page-contents').removeClass('active');
      $(`#to-${pages[to - 1]}`).removeClass('disabled').addClass('active');
      $('html').scrollTop(0);
    }
    // BEGIN:: UTILS
    function focusInError(elem, timeout = 6000){
      elem.addClass('is-invalid').focus();
      setTimeout(() => elem.removeClass('is-invalid'), timeout);
    }
    function handleAddCard(body, active = false, padding = 5){
      return `<div class="info-horizontal ${ active ? 'bg-gradient-primary':'bg-gray-100' } border-radius-xl p-${padding}">${ body }</div>`;
    }
    // END:: UTILS
  </script>
@endsection