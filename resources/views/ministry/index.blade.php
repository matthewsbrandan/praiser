<?php
  $disable = ['header'];
?>
@extends('layout.app')
@section('content')
  <!-- INFO USER  -->
  <section class="py-sm-2 py-5 pb-2 position-relative">
    <div class="container">
      <div class="row">
        <div class="col-12 mx-auto">
          <div class="row py-lg-7 py-5">
            <div class="col-lg-3 col-md-5 position-relative my-auto">
              <img
                class="img border-radius-lg max-width-200 w-100 position-relative z-index-2"
                src="{{ $ministry->getImage() }}"
                alt="{{ $ministry->name }}"
              />
            </div>
            <div class="col-lg-9 col-md-7 z-index-2 position-relative px-md-2 px-sm-5 mt-sm-0 mt-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <h4 class="mb-0">{{ $ministry->name }}</h4>
                  <span>Criador: {{$ministry->user->name}}</span>
                </div>

                @if(auth()->user()->id == $ministry->user_id)
                <!-- <div class="d-block">
                  <button type="button" class="btn btn-sm btn-outline-info text-nowrap mb-0">Editar</button>
                </div> -->
                @endif
                @if($ministry->hasPermissionTo('can_manage_integrant'))
                  <div class="d-block">
                    <button type="button" class="btn btn-sm btn-outline-info text-nowrap mb-0">Postar</button>
                  </div>
                @endif
              </div>
              <div class="row mb-4">
                <div class="col-auto">
                  <div class="row text-center py-1">
                    <div class="col-12">
                      <div class="avatar-group">
                        @foreach($ministry->users->take(5) as $user)
                          <a
                            href="{{ route('user.profile',['email' => $user->email]) }}"
                            class="avatar avatar-lg rounded-circle"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $user->name }}"
                          >
                            <img
                              alt="Image placeholder"
                              src="{{ $user->getProfile() }}"
                              style="height: 100%; object-fit: cover;"
                            />
                          </a>
                        @endforeach
                        @if($ministry->users->count() > 5)                        
                          <a
                            href="javascript:;"
                            class="avatar avatar-lg rounded-circle bg-light text-dark font-weight-bold "
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="+ {{ $ministry->users->count() - 5 }} Integrante(s)"
                            style="top: -1.2rem;"
                          >
                            + {{ $ministry->users->count() - 5 }}
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <h6>DESCRIÇÃO</h6>
              <p class="text-lg mt-1 mb-0">
                {{ $ministry->description ?? '...'}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="card">
        <div class="table-responsive">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Integrante</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Habilidades</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Títulos</th>
                @if($ministry->hasPermissionTo('can_manage_integrant'))
                  <th class="text-secondary opacity-7"></th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($ministry->users as $user)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $user->getProfile() }}" class="avatar avatar-sm me-3">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <a href="{{ route('user.profile', ['email' => $user->email]) }}">
                          <h6 class="mb-0 text-xs">
                            {{ $user->name }}
                            @if($userMinistry = $user->userMinistryId($ministry->id))
                             ({{ $userMinistry->nickname }})
                            @endif
                          </h6>
                          <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                        </a>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="avatar-group">
                      @foreach($user->abilities()->orderBy('exp','desc')->take(4)->get() as $ability)
                        <a
                          href="javascript:;"
                          class="avatar avatar-md rounded-circle"
                          data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $ability->name }}"
                        >
                          <img
                            alt="Image placeholder"
                            src="{{ $ability->getImage() }}"
                            style="height: 100%; object-fit: cover;"
                          />
                        </a>
                      @endforeach
                      @if($user->abilities->count() > 4)
                        <a
                          href="javascript:;"
                          class="avatar avatar-md rounded-circle bg-light text-dark font-weight-bold"
                          data-bs-toggle="tooltip" data-bs-placement="bottom" title="+ {{$user->abilities->count() - 4 }} Habilidades"
                          style="top: -1.0rem;"
                        >
                          + {{$user->abilities->count() - 4 }}
                        </a>
                      @endif
                    </div>
                  </td>
                  <td class="align-middle text-center text-sm">
                    @if($userMinistry)
                      <?php $captions = $userMinistry->getCaptionFormatted(true); ?>
                      <div class="d-flex flex-wrap" style="gap: .4rem">
                        @foreach($captions as $caption)
                          @if($caption)
                            <span class="badge badge-sm {{ $caption->class }}">{{ $caption->short }}</span>
                          @endif
                        @endforeach
                      </div>
                      @if(count($captions) == 0)
                        <span class="text-xs text-muted">não possui títulos</span>
                      @endif
                    @endif
                  </td>
                  @if($ministry->hasPermissionTo('can_manage_integrant'))                  
                    <td class="align-middle">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Editar {{ $user->name }}"
                        onclick="callEditIntegrant({{ $user->toJson() }},{{ $user->userAbilities()->with('ability')->get()->toJson() }},'{{ $userMinistry ? $userMinistry->caption : '' }}','{{ $userMinistry ? $userMinistry->nickname : '' }}')"
                      >Editar</a>
                    </td>
                  @endif
                </tr>
              @endforeach
              @if($ministry->users->count() == 0)
                <tr>
                  <td 
                    colspan="{{ $ministry->hasPermissionTo('can_manage_integrant') ? 4 : 3}}"   class="text-center text-muted"
                  >Este ministério não possui nenhum integrate</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
  @if($ministry->hasPermissionTo('can_manage_integrant'))
    @include('ministry.modals.edit-integrant')
  @endif

@endsection