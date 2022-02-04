<?php
  $disable = [
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  <div class="container mt-7">
    <!-- BEGIN:: HEADER -->
    <div class="row py-4">
      <div class="col-lg-6">
        <h3 class="text-gradient text-primary mb-0 mt-2">Iniciar Ministração</h3>
        @if($scale)
          <div class="d-flex flex-wrap mx-auto mt-2 text-dark">
            <time style="font-size: 3rem; line-height: 3rem;">{{ $scale->date_formatted }}</time>
            <div class="d-flex flex-column px-3">
              <strong>{{ $scale->weekday_formatted }}</strong>
              <span>Tema: {{ $scale->theme }}</span>
            </div>
          </div>
        @else
          <h3>Selecione os louvores, e disponibilize os links</h3>
        @endif
      </div>
    </div>
    <!-- END:: HEADER -->
    <div class="row">
      <div class="col-12">
        @include('utils.pagination',['pagination' => [
          (object)[
            'id' => 'to-content-info', 'onclick' => 'handlePagination(1)'
          ],(object)[
            'id' => 'to-content-praises', 'onclick' => 'handlePagination(2)'
          ],(object)[
            'id' => 'to-content-resume', 'onclick' => 'handlePagination(3)'
          ],

        ]])
      </div>
      <div class="col-md-6 mx-auto">
        @include('scale_praise.create.steps.info')
        @include('scale_praise.create.steps.praises')
        @include('scale_praise.create.steps.resume')
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    const icon = {
      trash:`@include('utils.icons.trash',['icon' => (object)['width' => '18px','height' => '18px']])`,
      word: `@include('utils.icons.word',['icon' => (object)['width' => '18px','height' => '18px']])`,
      lock: `@include('utils.icons.lock',['icon' => (object)['width' => '18px','height' => '18px']])`
    };
    const pages = [
      'content-info',
      'content-praises',
      'content-resume',
    ];
    var praises_added = [];

    function handlePagination(to){
      $('.page-contents').hide();
      $(`#${pages[to - 1]}`).show('slow');
      $('.to-page-contents').removeClass('active');
      $(`#to-${pages[to - 1]}`).removeClass('disabled').addClass('active');
    }

    const debounceEvent = (fn, wait = 1000, time) =>  (...args) =>
      clearTimeout(time, time = setTimeout(() => fn(...args), wait));
    $('#minister-praise').on('keyup',debounceEvent(searchPraise, 500));

    async function searchPraise(){
      let search = $('#minister-praise').val();
      if(search.length === 0){
        $('#content-searched-praises tbody').html(
          `<td colspan="2" class="mb-0 text-center text-muted text-xs p-2">...</td>`
        );

        return;
      }
      $('#content-searched-praises tbody').html(
        `<td colspan="2" class="mb-0 text-center text-muted text-xs p-2">Procurando...</td>`
      );

      await $.post('{{ route('praise.more') }}', { search }).done(data => {
        if(data.result){
          $('#content-searched-praises tbody').html('');
          if(data.response.length > 0) data.response.forEach(praise => {
            $('#content-searched-praises tbody').append(handleAddPraise(praise));
          });
          else $('#content-searched-praises tbody').html(
            `<td colspan="2" class="mb-0 text-center text-muted text-xs p-2">Nenhum louvor encontrado...</td>`
          );
        }
      });
    }
    function handleAddPraise(praise){
      return `
        <tr class="row-praise">
          <td style="max-width: 15rem">
            <div class="d-flex align-items-center">
              <a href="javascript:;" class="pe-3" onclick="handleToggleFavorite(${ praise.id }, $(this).children())">
                ${ praise.is_favorite ? `<i class="fa fa-star"></i>`:`<i class="far fa-star"></i>` }
              </a>
              <div
                class="d-flex flex-column justify-content-center" style="flex: 1;"
                onclick='handleSelectPraise(${ JSON.stringify(praise) })'
              >
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
        </tr>
      `;
    }
    function handleSelectPraise(praise){
      $('#minister-praise-id').val(praise.id);
      $('#minister-praise').val(praise.name);
      $('#minister-singer').val(praise.singer).focus();
      $('#minister-youtube').val(praise.main_youtube?.link);
      $('#minister-cipher').val(praise.main_cipher?.link);
      $('#minister-tone').val(praise.main_cipher?.original_tone);
    }
    function handleAddPraiseToScale(){
      let id =  $('#minister-praise-id').val();
      let name =  $('#minister-praise').val();
      let singer =  $('#minister-singer').val();
      let youtube =  $('#minister-youtube').val();
      let cipher =  $('#minister-cipher').val();
      let tone =  $('#minister-tone').val();
      let index =  praises_added.length + 1;

      if(name.length === 0){
        focusInError($('#minister-praise'));
        notify('warning','O nome do louvor é obrigatório');
        return;
      }
      if(singer.length === 0){
        focusInError($('#minister-singer'));
        notify('warning','O nome do cantor/banda é obrigatório');
        return;
      }
      if(youtube.length === 0){
        focusInError($('#minister-youtube'));
        notify('warning','O link é obrigatório');
        return;
      }

      praises_added.push({ id, name, singer, youtube, cipher, tone, index });
      
      $('#minister-praise-id,#minister-praise,#minister-singer,#minister-youtube,#minister-cipher,#minister-tone').val('');
      $('#minister-praise').focus();

      handleRenderPraisesAdded();

      $('#finalize-scale').show('slow');
    }
    function handleRenderPraisesAdded(){
      $('#content-praises-added').html('');
      praises_added.forEach(praise => {
        $('#content-praises-added').append(
          handleHtmlPraiseAdded(praise)
        );
      });
    }
    function handleHtmlPraiseAdded(praise){
      return `
        <div class="d-flex align-items-center my-1">
          <input
            type="number"
            min="1"
            max="${praises_added.length}"
            step="1"
            value="${praise.index}"
            class="form-control me-2 py-1"
            style="max-width: 3rem;"
          />
          <span>
            ${praise.name} - ${praise.tone}
          </span>
        </div>
      `;
    }
    function focusInError(elem, timeout = 6000){
      elem.addClass('is-invalid').focus();
      setTimeout(() => elem.removeClass('is-invalid'), timeout);
    }    
    function saveScale(){
      if(praises_added.length === 0){
        focusInError($("#minister-praise"));
        notify('danger','Adicione pelo menos 1 louvor a escala');
        return;
      }
      let data = {
        scale_id: {{ $scale ? $scale->id : 'null' }},
        verse: $("#minister-verse").val() ?? null,
        about: $("#minister-about").val() ?? null,
        privacy: $("#checkbox-privacy").val() ?? null,
        playlist: $("#minister-playlist").val() ?? null,
        praises: praises_added
      };

      $.post(`{{ route('scale_praise.store') }}`, data).done(data => {
        if(!data.result){
          notify('danger',data.response);
          return;
        }
        fillResume(data.response);
        // MONTAR A ESCALA NO TERCEIRO STEP
        handlePagination(3);
      }).fail(data => {
        notify('danger','Houve um erro ao criar a escala');
      });
    }
    function fillResume(scale){
      let listPraises = scale.scale_praises.map(praise => {
        return `
          <li class="list-group-item py-1 d-flex align-items-center justify-content-between">
            <span>${praise.praise.name + (praise.tone ? ` - ${praise.tone}`:'')}</span>
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
      $('#content-resume').html(`
        <h6>Resumo da Escala ${scale.privacy === 'public' ? icon.word : icon.lock}</h6>
        <ul class="list-group list-group-flush">
          ${listPraises}
        </ul>

        ${scale.playlist ? `
          <a
            target="_blank"
            href="${scale.playlist}"
            class="btn btn-sm bg-gradient-primary mt-2 mb-4"
          >Ouvir Playlist</a>
        `:''}
        <br/>
        ${scale.verse ? `<strong>${scale.verse}</strong>` : ''}
        ${scale.about ? `<p class="text-sm">${scale.about}</p>` : ''}
      `);
    }
  </script>  
@endsection