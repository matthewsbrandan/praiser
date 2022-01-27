<?php
  $disable = ['header'];
?>
@extends('layout.app')
@section('content')
  <style>
    .text-ellipsis{
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
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
                <h3 class="text-gradient text-primary">Escala de {{ $month_name }}</h3>
              </div>
              <div class="table-responsive">
                <div style="
                  display: grid;
                  grid-template-columns: repeat(7, 1fr);
                ">
                  <div class="font-weight-bold text-center">Domingo</div>
                  <div class="font-weight-bold text-center">Segunda</div>
                  <div class="font-weight-bold text-center">Terça</div>
                  <div class="font-weight-bold text-center">Quarta</div>
                  <div class="font-weight-bold text-center">Quinta</div>
                  <div class="font-weight-bold text-center">Sexta</div>
                  <div class="font-weight-bold text-center">Sábado</div>
                  @foreach($calendar as $day)
                    <div @class([
                        "p-2 border text-center d-flex flex-column",
                        "text-muted" => !$day->is_current_month,
                        "font-weight-light" => !$day->is_current_month,
                        "text-dark" => $day->is_current_month,
                        "font-weight-bold" => $day->is_current_month
                      ])
                      style="min-height: 5rem;"
                    >
                      <span class="text-center">{{ $day->date->format('d') }}</span>
                      @foreach($day->scales as $scale)
                        <button type="button" @class([
                            "btn btn-sm text-ellipsis mx-auto",
                            "bg-gradient-secondary" => in_array($day->weekday_index,[1,3,5]),
                            "bg-gradient-dark" => in_array($day->weekday_index,[2,4]),
                            "bg-gradient-danger" => $day->weekday_index == '0',
                            "bg-gradient-warning" => $day->weekday_index == '6'
                          ])
                          style="width: 8rem; font-size: .6rem; position: relative;"
                        >
                          {{ $scale->theme }}
                          @if($scaled = $scale->myScale())
                            <span 
                              class="badge bg-gradient-light text-dark"
                              data-bs-toggle="tooltip"
                              data-bs-placement="top"
                              title="{{ $scaled->ability }}"
                              style="position: absolute;"
                            >.</span>
                          @endif
                        </button>
                      @endforeach
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
@endsection
@section('scripts')
  <!-- initialization script -->
  <script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>
@endsection