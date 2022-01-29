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
                    Todos Louvores 
                  </h3>
                  <span class="badge bg-dark text-light font-weight-bold">{{ $total_praises }}</span>
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
                <table class="table align-items-center mb-0" id="table-praises">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="max-width: 15rem">Louvor</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Referências</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tags</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($praises as $praise)
                      <tr class="row-praise" data-name="{{ $praise->name }}" data-singer="{{ $praise->singer}}" data-tags="{{ $praise->tags }}">
                        <td style="max-width: 15rem">
                          <div class="d-flex align-items-center">
                            <a href="javascript:;" class="pe-3" onclick="handleToggleFavorite({{ $praise->id }}, $(this).children())">
                              @if($praise->is_favorite) <i class="fa fa-star"></i>
                              @else <i class="far fa-star"></i> @endif
                            </a>
                          
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-xs" style="white-space: normal;">{{ $praise->name }}</h6>
                              <p class="text-xs text-secondary mb-0">
                                {{ $praise->singer }}
                              </p>
                            </div>
                          </div>
                        </td>
                        <td>
                          @if(!$praise->has_reference)
                            <p class="text-xs text-muted mb-0">Sem Referências</p>
                          @else
                            <div class="avatar-group">
                              @if($praise->main_cipher)
                                <a
                                  href="{{ $praise->main_cipher->link }}" target="_blank"
                                  class="avatar avatar-sm rounded-circle"
                                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cifra"
                                >
                                  <img
                                    alt="Cifras Club"
                                    src="{{ asset('assets/img/cifras-club.png') }}"
                                    style="height: 100%; object-fit: cover;"
                                  />
                                </a>
                              @endif
                              @if($praise->main_youtube)
                                <a
                                  href="{{ $praise->main_youtube->link }}" target="_blank"
                                  class="avatar avatar-sm rounded-circle"
                                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
                                >
                                  <img
                                    alt="Youtube"
                                    src="{{ asset('assets/img/youtube.png') }}"
                                    style="height: 100%; object-fit: cover;"
                                  />
                                </a>
                              @endif
                            </div>
                          @endif
                        </td>
                        <td>
                          <div class="d-flex" style="gap: .4rem">
                            @foreach($praise->hashtags as $hashtag)
                              <span class="badge bg-gradient-secondary">{{ $hashtag }}</span>
                            @endforeach
                          </div>
                        </td>
                      </tr>
                    @endforeach
                    <tr id="tr-more-praises">
                      <td colspan="3">
                        <button
                          type="button" 
                          class="mb-0 btn-sm btn btn-link text-center d-block mx-auto"
                          onclick="handleMorePraises()"
                        >Carregar Mais</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  @include('utils.scripts.mirror-image')
@endsection
@section('scripts')
  <script>
    const btnMorePraises = `
      <tr id="tr-more-praises">
        <td colspan="3">
          <button
            type="button" 
            class="mb-0 btn-sm btn btn-link text-center d-block mx-auto"
            onclick="handleMorePraises()"
          >Carregar Mais</button>
        </td>
      </tr>
    `;
    var praise_ids = {!! $praises->map(function($praise){ return $praise->id; })->toJson() !!};
    var block_btn_more = false;
    const debounceEvent = (fn, wait = 1000, time) =>  (...args) =>
      clearTimeout(time, time = setTimeout(() => fn(...args), wait));

    function search(){
      let search = $('#input-search').val().toLowerCase();
      if(!$('#tr-more-praises')[0]) $('#table-praises tbody').append(btnMorePraises);

      $('.row-praise').each(function(){
        let name = $(this).attr('data-name').toLowerCase();
        let singer = $(this).attr('data-singer').toLowerCase();
        let tags = $(this).attr('data-tags').toLowerCase();
        
        if(name.indexOf(search) != -1 || singer.indexOf(search) != -1){
          $(this).show('slow');
        }else $(this).hide('slow');
      });
      if(search.length > 0) $('#tr-more-praises button').html('Ver mais resultado');
      else $('#tr-more-praises button').html('Carregar mais');
    }
    $('#input-search').on('keyup',debounceEvent(search, 500));

    async function handleMorePraises(){
      if(block_btn_more) return; 

      let search = $('#input-search').val();
      let data = {
        search: search.length > 0 ? search : null,
        ids: praise_ids.join(',')
      };
      let button_title = $('#tr-more-praises button').html();
      $('#tr-more-praises button').html('...');
      block_btn_more = true;

      await $.post('{{ route('praise.more') }}', data).done(data => {
        if(data.result){
          $('#tr-more-praises').remove();
          if(data.response.length > 0){
            data.response.forEach(praise => {
              praise_ids.push(praise.id);
              $('#table-praises tbody').append(handleAddPraise(praise));
            });
            $('#table-praises tbody').append(btnMorePraises);
          }
        }
      }).always(() => {
        if($('#tr-more-praises')[0]) $('#tr-more-praises button').html(button_title);
        block_btn_more = false;
      });
    }
    function handleAddPraise(praise){
      let hashtags = praise.hashtags.map(hashtag => {
        return `<span class="badge bg-gradient-secondary">${ hashtag }</span>`;
      }).join('');
      return `
        <tr class="row-praise" data-name="${ praise.name }" data-singer="${ praise.singer }" data-tags="${ praise.tags }">
          <td style="max-width: 15rem">
            <div class="d-flex align-items-center">
              <a href="javascript:;" class="pe-3" onclick="handleToggleFavorite(${ praise.id }, $(this).children())">
                ${ praise.is_favorite ? `<i class="fa fa-star"></i>`:`<i class="far fa-star"></i>` }
              </a>
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-xs" style="white-space: normal;">${ praise.name }</h6>
                <p class="text-xs text-secondary mb-0">
                  ${ praise.singer }
                </p>
              </div>
            </div>
          </td>
          <td>
            ${ !praise.has_reference ? `
              <p class="text-xs text-muted mb-0">Sem Referências</p>
            `:`
              <div class="avatar-group">
                ${ praise.main_cipher ? `
                  <a
                    href="${ praise.main_cipher.link }" target="_blank"
                    class="avatar avatar-sm rounded-circle"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cifra"
                  >
                    <img
                      alt="Cifras Club"
                      src="{{ asset('assets/img/cifras-club.png') }}"
                      style="height: 100%; object-fit: cover;"
                    />
                  </a>
                `:`` }
                ${ praise.main_youtube ? `
                  <a
                    href="${ praise.main_youtube.link }" target="_blank"
                    class="avatar avatar-sm rounded-circle"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
                  >
                    <img
                      alt="Youtube"
                      src="{{ asset('assets/img/youtube.png') }}"
                      style="height: 100%; object-fit: cover;"
                    />
                  </a>
                `:`` }
              </div>
            ` }
          </td>
          <td>
            <div class="d-flex" style="gap: .4rem">${ hashtags }</div>
          </td>
        </tr>
      `;
    }
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