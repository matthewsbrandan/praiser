<?php
  $disable = ['header'];
?>
<?php $captions = $user->getAllCaptions(true);?>
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
                src="{{ $user->getProfile() }}"
                alt="{{ $user->name }}"
              />
            </div>
            <div class="col-lg-7 col-md-7 z-index-2 position-relative px-md-2 px-sm-5 mt-sm-0 mt-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <h4 class="mb-0">{{ $user->name }}</h4>
                  <span>{{$user->email}}</span> | <a target="_blank" href="https://wa.me/{{$user->whatsapp}}">{{$user->whatsapp}}</a>
                </div>
                @if(auth()->user()->id == $user->id)
                  <div class="d-flex flex-column">
                    <a
                      class="btn btn-sm btn-outline-danger text-nowrap mb-0"
                      href="{{ route('logout') }}"
                    >Sair</a>
                    @if(auth()->user()->devOnly())
                      <a
                        class="btn btn-sm btn-dark text-nowrap mb-0 mt-2"
                        href="{{ route('user.index') }}"
                      >DEV</a>
                    @endif
                  </div>
                @endif
              </div>
              <div class="row mb-4">
                <div class="col-auto">
                  <div class="row text-center py-1">
                    <div class="col-12">
                      <div class="avatar-group">
                        @foreach($user->abilities as $ability)
                          <a
                            href="javascript:;"
                            class="avatar avatar-lg rounded-circle"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $ability->name }}"
                          >
                            <img
                              alt="Image placeholder"
                              src="{{ $ability->getImage() }}"
                              style="height: 100%; object-fit: cover;"
                            />
                          </a>
                        @endforeach
                        @if(auth()->user()->id == $user->id)  
                          <a
                            href="javascript:;"
                            class="avatar avatar-lg rounded-circle bg-light"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Adicionar Abilidade"
                            onclick="$('#modalEditAbilities').modal('show');"
                            style="top: -1.2rem;"
                          >
                            <i class="fas fa-plus text-dark"></i>
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @if(auth()->user()->id == $user->id)
                <h6 onclick="$('#modalEditAvailabilities').modal('show')">DISPONIBILIDADES <i class="fas fa-plus text-dark"></i></h6>
              @else
                <h6>DISPONIBILIDADES</h6>
              @endif
              @foreach($user->getAvailability() as $availability)
                <span class="badge bg-dark">{{ $user->getAvailabilityFormatted($availability) }}</span>
              @endforeach
              @if(count($user->getAvailability()) == 0)
                Você não cadastrou nenhuma disponibilidade
              @endif
              <p class="text-lg mt-1 mb-4">
                {{ $user->outhers_availability ?? '...'}}
              </p>
              <h6>TODOS TÍTULOS</h6>
              <?php $captions = $user->getAllCaptions(true); ?>
              <div class="d-flex flex-wrap" style="gap: .4rem">
                @foreach($captions as $caption)
                  @if($caption)
                    <span class="badge badge-sm {{ $caption->class }}">{{ $caption->short }}</span>
                  @endif
                @endforeach
              </div>
              @if(count($captions) == 0)
                <span class="text-sm text-muted">Não possui títulos</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="row">
        @if($user->userAbilities->count() > 0)
          <div class="col-md-6 card">
            <div class="card-body">
              <div class="row py-3">
                <div class="col-12 mx-auto">
                  <h6 class="text-uppercase">Experiência</h6>
                  <?php
                    $cicleClass = ['bg-primary','bg-secondary','bg-success','bg-info','bg-warning','bg-danger','bg-dark'];
                    $cicleIndex = 0;
                  ?>
                  @foreach($user->userAbilities as $userAbility)
                    <span class="text-xs text-muted text-uppercase">{{ $userAbility->ability->name }}</span>
                    <div class="progress mb-1">
                      <div
                        class="progress-bar {{ $userAbility->exp ? $cicleClass[$cicleIndex]: '' }}"
                        role="progressbar"
                        style="width: {{ $userAbility->exp ? $userAbility->exp * 20 : 0 }}%"
                        aria-valuenow="{{ $userAbility->exp ? $userAbility->exp * 20 : 0 }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      ></div>
                      <?php
                        if($userAbility->exp){
                          $cicleIndex++;
                          if(count($cicleClass) == $cicleIndex){
                            $cicleIndex = 0;
                          }
                        }
                      ?>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- USER MINISTRY  -->
  <section class="py-3">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h3 class="mb-5">Ministérios</h3>
        </div>
      </div>
      <div class="row">
        @foreach ($user->ministries as $ministry)        
          <div class="col-lg-3 col-sm-6">
            <div class="card card-plain card-blog">
              <div class="card-image border-radius-lg position-relative">
                <a href="{{ route('ministry.index', ['slug' => $ministry->slug]) }}">
                  <img
                    class="w-100 border-radius-lg move-on-hover shadow" src="{{ $ministry->getImage() }}"
                    style="object-fit: cover; height: 15rem;"
                  >
                </a>
              </div>
              <div class="card-body px-0">
                <h5>
                  <a href="{{ route('ministry.index', ['slug' => $ministry->slug]) }}" class="text-dark font-weight-bold">
                    {{ $ministry->name }}
                  </a>
                </h5>
                <p>
                  {{ $ministry->description }}
                </p>
                <a href="{{ route('ministry.index', ['slug' => $ministry->slug]) }}" class="text-info icon-move-right">Ver Mais
                  <i class="fas fa-arrow-right text-sm"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
        @if($user->ministries->count() == 0)
          <p class="text-muted">
            @if(auth()->user()->id == $user->id)
              Você ainda não está associado a nenhum ministério
            @else
              {{ $user->name }} ainda não está associado a nenhum ministério
            @endif
          </p>
        @endif
      </div>
    </div>
  </section>
  @if(auth()->user()->id == $user->id)
    @include('user.profile.modals.abilities')
    @include('user.profile.modals.availabilities')
  @endif
@endsection