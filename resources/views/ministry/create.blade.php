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
                <h3 class="text-gradient text-primary">Novo Ministério</h3>
                <p class="mb-0">
                  Cadastre seu ministério e envie o convite para os seus integrantes.
                </p>
              </div>
              <form
                id="contact-form"
                method="post"
                autocomplete="off"
                action="{{ route('ministry.store') }}"
                enctype="multipart/form-data"
              >
                {{ csrf_field() }}
                <div class="card-body pb-2">
                  <div class="row">
                    <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
                      <a
                        href="javascript:;"
                        class="avatar avatar-xl rounded-circle mx-auto d-block"
                        style="width: 9rem !important; margin: 0 0 4.2rem;"
                        onclick="$('#ministry-image').click()"
                      >
                        <img alt="Ministério" src="{{ asset('assets/img/ministry-default.jpg') }}" id="ministry-image-image" style="
                          object-fit: cover;
                          width: 9rem;
                          height: 9rem;
                        "/>
                      </a>
                      <small class="text-xs text-center" style="max-width: 10rem; padding: 1rem;">
                        Clique na imagem para alterar a foto
                      </small>
                      <input
                        type="file"
                        class="d-none"
                        name="image"
                        id="ministry-image"
                        onchange="handleMirrorFileImg(event, $('#ministry-image-image'))"
                      />
                    </div>
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
                            id="ministry-name"
                            required
                          />
                        </div>
                      </div>
                      <div class="form-check form-switch mb-1">
                        <input
                          class="form-check-input" type="checkbox"
                          name="free_entry"
                          id="checkbox-free-entry"
                          checked
                        />
                        <label class="form-check-label" for="checkbox-free-entry">Entrada Livre</label>
                      </div>
                      <div class="bg-light text-xs p-2 rounded mb-2">
                        Quando desmarcada, as pessoas que entrarem no ministério precisarão de autorização manual para ter acesso a todas informações.
                      </div>
                    </div>
                  </div>
                  <div class="form-group mb-0 mt-md-0 mt-4">
                    <label>Descrição</label>
                    <textarea
                      name="description"
                      id="ministry-description"
                      class="form-control"
                      rows="6"
                      maxlength="250"
                      placeholder="Descrição do ministério com no máximo 250 caracteres"
                      required
                    ></textarea>
                  </div>
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