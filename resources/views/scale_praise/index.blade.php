<?php
  $disable = [
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  <div class="container mt-7" style="min-height: calc(100vh - 4rem)">
    <div style="margin: auto; max-width: 900px">
      @foreach($ministers as $minister)
        <div class="card my-3">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center">
                <a
                  href="{{ route('user.profile', ['email' => $minister->user->email]) }}" target="_blank"
                  class="avatar avatar-sm rounded-circle"
                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
                >
                  <img
                    alt="Ministro"
                    src="{{ $minister->user->getProfile() }}"
                    style="height: 100%; object-fit: cover;"
                  />
                </a>
                @if($minister->scale)
                  <div class="mx-2">
                    <h6 class="mb-0">
                      Escala {{ $minister->scale->weekday_formatted }} @include('utils.icons.'.(
                        $minister->privacy == 'public' ? 'word' : 'lock'
                      ),['icon' => (object)[
                        'width' => '18px',
                        'height' => '18px'
                      ]])
                    </h6>
                    <span class="text-muted text-sm d-block" style="margin-top: -0.2rem;">{{ $minister->scale->date_formatted }}</span>
                  </div>
                @else
                  <h6 class="mb-0 ms-2">
                    Escala Particular @include('utils.icons.'.($minister->privacy == 'public' ? 'word' : 'lock'),['icon' => (object)[
                      'width' => '18px',
                      'height' => '18px'
                    ]])
                  </h6>
                @endif
              </div>
              @if($minister->user_id == auth()->user()->id)
                <button
                  type="button" class="btn btn-link text-dark px-1"
                  onclick="handleDeleteScale({{ $minister->id }})"
                >
                  @include('utils.icons.trash',['icon' => (object)[
                    'width' => '18px',
                    'height' => '18px'
                  ]])
                </button>
              @endif
            </div>
            <ul class="list-group list-group-flush">
              @foreach($minister->scale_praises as $praise)
                <li class="list-group-item py-1 d-flex align-items-center justify-content-between">
                  <span>
                    {{ $praise->praise->name . ($praise->tone ? ' - ' . $praise->tone:'') }}
                  </span>
                  <div>
                    @if($praise->youtube_link)
                      <a
                        href="{{ $praise->youtube_link }}" target="_blank"
                        class="avatar avatar-xs rounded-circle"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Youtube"
                      >
                        <img
                          alt="Youtube"
                          src="{{ asset('assets/img/youtube.png') }}"
                          style="height: 100%; object-fit: cover;"
                        />
                      </a>
                    @endif
                    @if($praise->cipher_link)
                      <a
                        href="{{ $praise->cipher_link }}" target="_blank"
                        class="avatar avatar-xs rounded-circle"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cifra"
                      >
                        <img
                          alt="Cifras Club"
                          src="{{ asset('assets/img/cifras-club.png') }}"
                          style="height: 100%; object-fit: cover;"
                        />
                      </a>
                    @endif
                  </div>
                </li>
              @endforeach
            </ul>
            @if($minister->playlist)
              <a
                target="_blank"
                href="{{ $minister->playlist }}"
                class="btn btn-sm bg-gradient-primary mt-2 mb-4"
              >Ver Playlist</a>
            @endif
            <br/>
            @if($minister->verse) <strong>{{ $minister->verse }}</strong> @endif
            @if($minister->about) <p class="text-sm">{{ $minister->about }}</p> @endif
          </div>
        </div>
      @endforeach
      @if($ministers->count() === 0)
        <p class="text-center text-muted text-sm">Nenhuma escala cadastrada</p>
      @endif
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    function handleDeleteScale(scale_id){
      callModalMessageConfirm(
        `{{ substr(route('scale_praise.delete', ['id' => 0]),0,-1) }}${scale_id}`,
        'Tem certeza que deseja excluir essa escala?'
      );
    }
  </script>
@endsection