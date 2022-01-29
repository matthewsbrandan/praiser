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
                    @include('praise.partials.fields')
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