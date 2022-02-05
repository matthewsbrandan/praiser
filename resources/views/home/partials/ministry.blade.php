<?php
  $ministry = auth()->user()->currentMinistry;
?>
<style>
  @media (max-width: 600px){
    #next-scale .date-theme{
      text-align: center;
    }
    #next-scale .date-theme > div{
      width: 100%;
    }
  }
</style>
<div class="row">
  <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto py-3 blur shadow-blur">
    <div class="row">
      <div class="col-md-12 position-relative">
        <div class="p-3">
          <h5 class="mt-3 text-gradient text-primary text-center">Próxima Escala</h5>
          @if($next_scale)
            <div class="d-flex flex-column align-items-center" id="next-scale">
              <div class="d-flex flex-wrap date-theme">
                <time style="
                  font-size: 3rem;
                  line-height: 3rem;
                  margin: auto;
                ">{{ $next_scale->date_formatted }}</time>
                <div
                  class="d-flex flex-column px-3"
                  style="margin: auto;"
                >              
                  <strong>{{ $next_scale->weekday_formatted }}</strong>
                  <span>Tema: {{ $next_scale->theme }}</span>
                </div>
              </div>
              <div class="avatar-group d-flex align-items-center mt-2 mb-3">
                @foreach($next_scale->abilities as $ability)
                  <a
                    href="javascript:;"
                    class="avatar avatar-sm rounded-circle"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $ability->name }}"
                  >
                    <img
                      alt="Image placeholder"
                      src="{{ $ability->getImage() }}"
                      style="height: 100%; object-fit: cover;"
                    />
                  </a>
                @endforeach
              </div>
              @foreach($next_scale->minister_scales as $minister)
                <div class="w-100">
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
                      <h6 class="mb-0 ms-2">
                        {{$minister->user->name}}
                      </h6>
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
                    >Ouvir Playlist</a>
                  @endif
                  <br/>
                  @if($minister->verse) <strong>{{ $minister->verse }}</strong> @endif
                  @if($minister->about) <p class="text-sm">{{ $minister->about }}</p> @endif
                </div>
              @endforeach              
              @if(
                $next_scale->is_minister &&
                !$next_scale->ministerScales()
                  ->whereUserId(auth()->user()->id)
                  ->wherePrivacy('public')
                  ->first()
              )
                <a
                  href="{{ route('scale_praise.create',['scale_id' => $next_scale->scale_id]) }}"
                  class="btn bg-gradient-primary text-center mx-auto"
                >Adicionar Ministração</a>
              @elseif($next_scale->minister_scales->count() == 0)
                <p class="text-muted text-sm mt-2 mb-0 bg-light w-100 p-3 rounded">A escala de louvores ainda não foi adicionada</p>
              @endif
            </div>
          @else
            <p class="text-sm">Ainda não há escalas futuras para você.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>