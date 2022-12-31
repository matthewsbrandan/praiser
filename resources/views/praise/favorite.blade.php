<?php
  $disable = [
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  <header>
    <div class="page-header min-vh-85">
      <div>
        <img class="position-absolute fixed-top ms-auto w-50 h-100 z-index-0 d-none d-sm-none d-md-block border-radius-section border-top-end-radius-0 border-top-start-radius-0 border-bottom-end-radius-0" src="{{ asset('assets/img/curved-images/curved8.jpg') }}" alt="image">
      </div>
      <div class="container">
        <div class="row">
          <div class="col-12 d-flex justify-content-center flex-column mt-sm-4">
            <div class="card d-flex blur justify-content-center p-4 shadow-lg my-sm-0 my-sm-6 mt-8 mb-5">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-start" style="gap: .4rem">
                  <h3 class="text-gradient text-primary">
                    Favoritos Louvores
                  </h3>
                  <span class="badge bg-dark text-light font-weight-bold">{{ $praises->count() }}</span>
                </div>
                <div>
                  <div class="input-group">
                    <span class="input-group-text" style="background: transparent"><i class="ni ni-zoom-split-in"></i></span>
                    <input
                      class="form-control"
                      placeholder="Pesquisar"
                      type="text"
                      style="background: transparent"
                      id="input-search"
                    />
                  </div>
                </div>
              </div>

              <div class="table-responsive" style="max-height: calc(100vh - 13rem);">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-secondary opacity-7" style="max-width: 2rem !important; padding: 0;"></th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="max-width: 15rem">Louvor</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Referências</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Atualização</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($praises as $praise)
                      <tr class="row-praise" data-name="{{ $praise->praise->name }}" data-singer="{{ $praise->praise->singer }}">
                        <td style="max-width: 2rem !important;" onclick="handleToggleFavorite({{ $praise->praise_id }}, $(this).children())">
                          <i class="fa fa-star"></i>
                        </td>
                        <td style="max-width: 15rem">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-xs" style="white-space: normal;">{{ $praise->praise->name }}</h6>
                            <p class="text-xs text-secondary mb-0">
                              {{ $praise->praise->singer }}
                            </p>
                          </div>
                        </td>
                        <td>
                          @if(!$praise->hasReference())
                            <p class="text-xs text-muted mb-0">Sem Referências</p>
                          @else
                            <div class="avatar-group">
                              @if($cipher = $praise->mainCipher())
                                <a
                                  href="{{ $cipher->link }}" target="_blank"
                                  class="avatar avatar-sm rounded-circle"
                                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cifra"
                                >
                                  <img
                                    alt="Image placeholder"
                                    src="{{ asset('assets/img/cifras-club.png') }}"
                                    style="height: 100%; object-fit: cover;"
                                  />
                                </a>
                              @endif
                              @if($youtube = $praise->mainYoutube())
                                <a
                                  href="{{ $youtube->link }}" target="_blank"
                                  class="avatar avatar-sm rounded-circle"
                                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
                                >
                                  <img
                                    alt="Image placeholder"
                                    src="{{ asset('assets/img/youtube.png') }}"
                                    style="height: 100%; object-fit: cover;"
                                  />
                                </a>
                              @endif
                            </div>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-xs font-weight-bold">
                            {{ $praise->updated_at->format('d/m/Y')}}
                          </span>
                        </td>
                        <td class="align-middle">
                          <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Editar">
                            Editar
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
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
    const debounceEvent = (fn, wait = 1000, time) =>  (...args) =>
      clearTimeout(time, time = setTimeout(() => fn(...args), wait));
    function search(){
      let search = $('#input-search').val();
      $('.row-praise').each(function(){
        let name = $(this).attr('data-name');
        let singer = $(this).attr('data-singer');
        
        if(name.indexOf(search) != -1 || singer.indexOf(search) != -1){
          $(this).show('slow');
        }else $(this).hide('slow');
      })
    }
    $('#input-search').on('keyup',debounceEvent(search, 500));

    var blockFavorite = false;
    function handleToggleFavorite(id, target){
      if(blockFavorite) return;
      blockFavorite = true;
      let data = { id };
      $.post("{{ route('praise.favorite.toggle') }}", data).then(data => {
        if(data.result) target.toggleClass('far fa');
        else callModalMessage(data.response);
      }).always(() => blockFavorite = false);
    }
  </script>
@endsection