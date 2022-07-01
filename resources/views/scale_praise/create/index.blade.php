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
        <h3 class="text-gradient text-primary mb-0 mt-2">
          @isset($minister) Editar Ministração
          @else Iniciar Ministração @endisset
        </h3>
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
            'id' => 'to-content-praises', 'onclick' => 'handlePagination(1)'
          ],(object)[
            'id' => 'to-content-resume', 'onclick' => 'handlePagination(2)'
          ],

        ]])
      </div>
      <div class="col-md-6 mx-auto">
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
      'content-praises',
      'content-resume',
    ];
    var praises_added = {!!
      isset($minister) ? $minister->praises_added->toJson() : '[]'
    !!};
    var minister_scale_id = {{ $minister->id ?? 'null' }};
    var praiseInEdition = null;

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
    function handleReindexPraise(elem){
      let index = findPraiseAdded({
        name: elem.attr('data-name'),
        singer: elem.attr('data-singer')
      });
      if(index === null) return;

      let next_index = elem.val();
      if(next_index > praises_added[index].index){
        if(index + 1 < praises_added.length) praises_added[index + 1].index = next_index - 1;
        praises_added[index].index = next_index;
      }else
      if(next_index < praises_added[index].index){
        if(index - 1 >= 0) praises_added[index - 1].index = next_index + 1;
        praises_added[index].index = next_index;
      }else return;
      
      praises_added = [...praises_added.sort(function(a, b) {
        return a.index - b.index;
      })];

      praises_added = praises_added.map((praise, i) => {
        praise.index = i+1;
        return praise;
      })

      handleRenderPraisesAdded();
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

      handleShowPraise();
    }
    function handleAddPraiseToScale(){
      let id =  $('#minister-praise-id').val();
      let name =  $('#minister-praise').val();
      let singer =  $('#minister-singer').val();
      let youtube =  $('#minister-youtube').val();
      let cipher =  $('#minister-cipher').val();
      let tone =  $('#minister-tone').val();
      let legend =  $('#minister-legend').val();
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

      if(praiseInEdition){
        let index = findPraiseAdded(praiseInEdition);
        if(index === null) return;
        praises_added[index] = {
          id,
          name,
          singer,
          youtube,
          cipher,
          tone,
          legend,
          index: praiseInEdition.index
        };
        praiseInEdition = null;
      }
      else praises_added.push({ id, name, singer, youtube, cipher, tone, legend, index });
      
      $('#minister-praise-id,#minister-praise,#minister-singer,#minister-youtube,#minister-cipher,#minister-tone,#minister-legend').val('');

      $('#modalPraise').modal('hide');
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
            data-name="${praise.name}"
            data-singer="${praise.singer}"
            class="form-control me-2 py-1 praise-index"
            style="max-width: 3rem;"
            onchange="handleReindexPraise($(this))"
          />
          <span 
            style="flex: 1"
            onclick='handleEditPraiseAdded(${JSON.stringify(praise)})'
          >
            ${
              (praise.legend ? praise.legend + ': ' : '') + 
              (praise.name) + 
              (praise.tone ? ` - ${praise.tone}`:'')
            }
          </span>
          <button
            type="button"
            class="btn-close text-dark"
            onclick='handleRemovePraiseAdded(${JSON.stringify(praise)})'
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      `;
    }
    function handleEditPraiseAdded(praise){
      let index = findPraiseAdded(praise);
      if(index === null) return;

      $('#minister-praise-id').val(praises_added[index].id ?? '');
      $('#minister-praise').val(praises_added[index].name ?? '').focus();
      $('#minister-singer').val(praises_added[index].singer ?? '');
      $('#minister-youtube').val(praises_added[index].youtube ?? '');
      $('#minister-cipher').val(praises_added[index].cipher ?? '');
      $('#minister-tone').val(praises_added[index].tone ?? '');
      $('#minister-legend').val(praises_added[index].legend ?? '');

      praiseInEdition = praises_added[index];

      handleShowPraise();
    }
    function handleRemovePraiseAdded(praise){
      let index = findPraiseAdded(praise);
      if(index === null) return;
      praises_added.splice(index, 1);
      handleRenderPraisesAdded();
    }
    
    function findPraiseAdded(praise){
      let index = praises_added.findIndex(
        added => added.name === praise.name && added.singer === praise.singer
      );
      if(index === -1){
        notify('danger','Louvor não encontrados');
        return null;
      }
      return index;
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
        id: minister_scale_id,
        scale_id: {{ $scale ? $scale->id : 'null' }},
        verse: $("#minister-verse").val() ?? null,
        about: $("#minister-about").val() ?? null,
        privacy: $("#checkbox-privacy").prop('checked') ? 'on' : null,
        playlist: $("#minister-playlist").val() ?? null,
        praises: praises_added
      };
      
      $.post(`{{ route('scale_praise.store') }}`, data).done(data => {
        if(!data.result){
          notify('danger',data.response);
          return;
        }
        minister_scale_id = data.response.id;
        fillResume(data.response);
        // MONTAR A ESCALA NO TERCEIRO STEP
        handlePagination(2);
      }).fail(data => {
        notify('danger','Houve um erro ao criar a escala');
      });
    }
    function fillResume(scale){
      let sharePraises = [];
      let listPraises = scale.scale_praises.map(praise => {
        let description =
          (praise.legend ? `${praise.legend}: ` : '') + 
          praise.praise.name + 
          (praise.tone ? ` - ${praise.tone}` : '')
        ;
        sharePraises.push(`- ${description.trim()}`);

        return `
          <li class="list-group-item py-1 d-flex align-items-center justify-content-between">
            <span>
              ${description}
              <em class="d-block text-xs">${ praise.praise.singer }</em>
            </span>
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

      if(scale.playlist) sharePraises.push(`\n${scale.playlist}`);
      if(scale.verse) sharePraises.push(`\n*${scale.verse}*`);
      if(scale.about) sharePraises.push(`${!scale.verse?'\n':''}${scale.about}`);

      let shareMinistering = encodeURIComponent(scale.header + sharePraises.join('\n'));

      $('#content-resume .content').html(`
        <h6>Resumo da Escala ${scale.privacy === 'public' ? icon.word : icon.lock}</h6>
        <ul class="list-group list-group-flush">
          ${listPraises}
        </ul>

        <a
          href="{{ substr(route('scale_praise.show', ['id' => 0]),0,-1) }}${scale.id}"
          class="btn btn-sm bg-gradient-light mt-2 mb-4"
        >Ver</a>
        ${scale.playlist ? `
          <a
            target="_blank"
            href="${scale.playlist}"
            class="btn btn-sm bg-gradient-primary mt-2 mb-4"
          >Ouvir Playlist</a>
        `:''}
        ${ handleHtmlShare(shareMinistering) }
        <br/>
        ${scale.verse ? `<strong>${scale.verse}</strong>` : ''}
        ${scale.about ? `<p class="text-sm">${scale.about}</p>` : ''}
      `);
    }
    function handleHtmlShare(share){
      return `
        <a
          target="_blank"
          href="https://api.whatsapp.com/send?text=${share}"
          class="btn btn-sm bg-gradient-dark mt-2 mb-4"
        >@include('utils.icons.share', ['icon' => (object)[
          'width' => '18px',
          'height' => '18px',
        ]])</a>
      `;
    }

    @isset($minister)
      praises_added = praises_added.map((praise, i) => {
        praise.index = i+1;
        return praise;
      })
      handleRenderPraisesAdded();
      $('#finalize-scale').show('slow');
    @endisset
  </script>
@endsection