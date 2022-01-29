<?php
  $disable = ['header'];
?>
@extends('layout.app')
@section('content')
  @include('layout.header',['header' => (object)[
    'title' => 'Usuários',
    'subtitle' => 'Dev Access'
  ]])
  <section class="mt-4">
    <div class="container">
      <div class="card">
        <div class="table-responsive">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Integrante</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ministérios</th>
                <th class="text-secondary opacity-7"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $user->getProfile() }}" class="avatar avatar-sm me-3">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <a href="{{ route('user.profile', ['email' => $user->email]) }}">
                          <h6 class="mb-0 text-xs">{{ $user->name }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                        </a>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="avatar-group">
                      @foreach($user->ministries as $ministry)
                        <a
                          href="javascript:;"
                          class="avatar avatar-md rounded-circle"
                          data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $ministry->name }}"
                        >
                          <img
                            alt="Image placeholder"
                            src="{{ $ministry->getImage() }}"
                            style="height: 100%; object-fit: cover;"
                          />
                        </a>
                      @endforeach
                    </div>
                  </td>
                  <td class="align-middle">
                    <a
                      href="{{ route('user.login',['user_id' => $user->id]) }}"
                      class="text-secondary font-weight-bold text-xs"
                      data-toggle="tooltip"
                      data-original-title="Acessar {{ $user->name }}"
                    >Acessar</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
@endsection