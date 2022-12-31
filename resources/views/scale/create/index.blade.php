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
                  <div class="d-flex flex-wrap mb-3" style="gap: .4rem;">
                    @if($calendar->result)
                      <button
                        type="button"
                        class="btn bg-gradient-light mb-0"
                        onclick="callModalDays()"
                      >Adicionar Datas</button>
                    @endif 
                    @if($userAvailabilities->result)
                      <button
                        type="button"
                        class="btn bg-gradient-light mb-0"
                        onclick="callModalUserAvailability()"
                      >Disp. / Indisp.</button>
                    @endif                    
                  </div>
                  @include('scale.create.partials.scales')
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
  {{-- BEGIN:: INIT --}}
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

    const pages = [
      'content-days',
      'content-scale',
    ];
    var pagesValidated = [];
  </script>
  {{-- END:: INIT --}}
  @if($calendar->result)
    @include('scale.create.partials.modal-days', [
      'calendar' => (object) $calendar->response
    ])
  @endif
  @if($userAvailabilities->result)
    @include('scale.create.partials.modal-user-availability', [
      'calendar' => (object) $userAvailabilities->response
    ])
  @endif
  <script>
    function focusInError(elem, timeout = 6000){
      elem.addClass('is-invalid').focus();
      setTimeout(() => elem.removeClass('is-invalid'), timeout);
    }
    @isset($scales)
      @foreach($scales as $scale)
        scale_ids.push({{ $scale->id }});
        $('#scales-created tbody').append(htmlScaleFinalized({!! $scale->toJson() !!}));
      @endforeach
      handleEditScale({!! $scales->last()->toJson() !!});
    @endisset
  </script>
@endsection