<?php
  $disable = [
    'header',
    'footer'
  ];
?>
@extends('layout.app')
@section('content')
<section>
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
            <div class="card card-plain">
              <div class="card-header pb-0 text-left">
                @isset($email)
                  <h4 class="font-weight-bolder mb-0">Entrar com senha</h4>
                  <span class="text-muted text-sm d-block mb-2">{{ $email }}</span>
                  <p class="mb-0">Digite sua senha para entrar</p>
                @else
                  <h4 class="font-weight-bolder">Entrar com email</h4>
                  <p class="mb-0">Digite seu email para entrar</p>
                @endisset
              </div>
              <div class="card-body pt-2">
                <form
                  role="form"
                  method="POST"
                  action="{{ isset($email) ? route('login') : route('login.email') }}"
                >
                  {{ csrf_field() }}
                  @isset($email)
                    <input type="hidden" name="email" value="{{ $email }}"/>
                    <div class="mb-2">
                      <input
                        type="password"
                        name="password"
                        class="form-control form-control-lg"
                        placeholder="Senha"
                        aria-label="password"
                        aria-describedby="password-addon"
                        required
                      />
                    </div>
                  @else
                    <div class="mb-2">
                      <input
                        type="email"
                        class="form-control form-control-lg"
                        placeholder="Email"
                        aria-label="Email"
                        name="email"
                        aria-describedby="email-addon"
                        required
                      />
                    </div>
                  @endisset
                  <div class="text-center">
                    <button type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Entrar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
              <img src="{{ asset('assets/img/shapes/pattern-lines.svg') }}" alt="pattern-lines" class="position-absolute opacity-4 start-0">
              <div class="position-relative">
                <img class="max-width-500 w-100 position-relative z-index-2" src="{{ asset('assets/img/illustrations/chat.png') }}">
              </div>
              <h4 class="mt-5 text-white font-weight-bolder">Faça, realize!</h4>
              <p class="text-white">Uma ideia simples colocada em prática é melhor do que uma ideia extraordinária que nunca sai do papel.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection