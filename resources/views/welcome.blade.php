<?php
  $disable = [
    'navbar',
    'header',
    'footer'
  ];
?>
@extends('layout.app')
@section('header')
  @if(!auth()->user())
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://unpkg.com/jwt-decode@3.0.0/build/jwt-decode.js"></script>
    
    <script>
      function handleCredentialResponse(response) {
        const data = jwt_decode(response.credential);
        if(data.email && data.name && data.sub){
          $('#flwg-email').val(data.email);
          $('#flwg-name').val(data.name);
          $('#flwg-picture').val(data.picture ?? null);
          $('#flwg-sub').val(data.sub);
          $('#form-login-with-google').submit();
        }else{ alert('Sem informações suficientes para fazer a autenticação com o google.'); }
      }
      window.onload = function () {
        google.accounts.id.initialize({
          client_id: "{{ config('services.google.client_id') }}",
          callback: handleCredentialResponse
        });
        google.accounts.id.renderButton(
          document.getElementById("btnLoginWithGoogle"),
          {
            type: "standard",
            shape: "pill",
            theme: "outline",
            size: "large",
            text: "$ {button.text}"
          }  // customization attributes
        );
        google.accounts.id.prompt(); // also display the One Tap dialog
      }
    </script>
  @endif
  <style>
    #content-verse-text{
      max-height: 6rem;
      overflow: auto;
    }
    #content-verse-text::-webkit-scrollbar{
      height: 6px;
      width: 6px;
    }
    #content-verse-text::-webkit-scrollbar-track{
      background: #eee;
      border-radius: .4rem;
    }
    #content-verse-text::-webkit-scrollbar-thumb{
      background: #666;
      border-radius: .4rem;
    }
  </style>
@endsection
@section('content')
  <header style="height: 100vh;">
    <div class="page-header section-height-100" style="height: calc(100% - 1rem);">
      <div class="oblique position-absolute top-0 h-100 d-md-block d-none">
        <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url(./assets/img/curved-images/curved11.jpg)"></div>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-md-7 d-flex justify-content-center flex-column">
            <h1 class="text-gradient text-primary">Praiser</h1>
            <h1 class="mb-4">Ministério de Louvor</h1>
            <p class="lead pe-5 me-5" id="content-verse-text">{{ $verse->text }}</p>
            <div class="stats" style="padding-left: .8rem;">{{ $verse->ref }}</div>
            <div class="buttons">
              @auth
                <a
                  href="{{ route('home') }}"
                  class="btn bg-gradient-primary mt-4">Acessar</a>
              @else
                <a  
                  href="javascript:;"
                  class="btn p-0 mt-4"
                  style="background: transparent; border-radius: 99rem;"
                  id="btnLoginWithGoogle"
                >Acessar com Google</a>
                <a href="{{ route('login') }}" class="btn text-primary shadow-none mt-4">Acessar com Email</a>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <form
    id="form-login-with-google"
    class="d-none"
    action="{{ route('login') }}"
    method="post"
  >
    {{ csrf_field() }}
    <input type="hidden" name="email" id="flwg-email" required>
    <input type="hidden" name="name" id="flwg-name" required>
    <input type="hidden" name="profile" id="flwg-picture">
    <input type="hidden" name="google_id" id="flwg-sub" required>
  </form>
@endsection