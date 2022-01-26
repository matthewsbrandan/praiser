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
          <div class="col-lg-7 d-flex justify-content-center flex-column mt-sm-4">
            <div class="card d-flex blur justify-content-center p-4 shadow-lg my-sm-0 my-sm-6 mt-8 mb-5">
              <div class="text-center">
                <h3 class="text-gradient text-primary">Novo Louvor</h3>
                <p class="mb-0">
                  Cadastre seus louvores com links de vídeo, letra e cifra, para facilitar na hora da criação de escalas.
                </p>
              </div>
              <form
                id="contact-form"
                method="post"
                autocomplete="off"
                action="{{ route('praise.store') }}"
                enctype="multipart/form-data"
              >
                {{ csrf_field() }}
                <div class="card-body pb-2">
                  @if($import)
                    <div class="form-group">
                      <label>Clique para importar sua planilha</label>
                      <input type="file" class="form-control" name="import" required/>
                    </div>
                  @else
                    <div class="row">
                      <div class="col-md-6">
                        <div>
                          <label>Nome</label>
                          <div class="input-group mb-3">
                            <input
                              class="form-control"
                              placeholder="Nome"
                              aria-label="Nome"
                              type="text"
                              name="name"
                              id="praise-name"
                              required
                            />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div>
                          <label>Cantor/Banda</label>
                          <div class="input-group mb-3">
                            <input
                              class="form-control"
                              placeholder="Cantor/Banda"
                              aria-label="Cantor/Banda"
                              type="text"
                              name="singer"
                              id="praise-singer"
                              required
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div>
                          <label>Link do Youtube (opcional)</label>
                          <div class="input-group mb-3">
                            <input
                              class="form-control"
                              placeholder="Link do Youtube"
                              aria-label="Link do Youtube"
                              type="text"
                              name="youtube"
                              id="praise-youtube"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div>
                          <label>Link da Cifra (opcional)</label>
                          <div class="input-group mb-3">
                            <input
                              class="form-control"
                              placeholder="Cifra"
                              aria-label="Cifra"
                              type="text"
                              name="cipher"
                              id="praise-cipher"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                    <h6>TAGS</h6>
                    <div class="d-flex flex-wrap justify-content-around">
                      @foreach(\App\Models\Praise::getAvailableTags() as $tag)
                        <button
                          type="button"
                          class="btn btn-sm bg-gradient-light mx-2 text-xs"
                          onclick="toggleTags($(this),'{{ $tag }}')"
                          style="width: 12rem"
                        >#{{ $tag }}</button>
                      @endforeach
                      <input type="hidden" name="tags" id="input-tags"/>
                    </div>
                  @endif
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <button type="submit" class="btn bg-gradient-primary mt-3 mb-0">Criar</button>
                    </div>
                  </div>
                </div>
              </form>
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
    function toggleTags(elem, tag){
      elem.toggleClass('bg-gradient-light bg-gradient-primary');

      let tags = $('#input-tags').val() ? $('#input-tags').val().split(',') : [];
      if(tags.includes(tag)) tags = tags.filter(
        item => item != tag
      );
      else tags.push(tag);
      $('#input-tags').val(tags.join(','));
    }
  </script>
@endsection