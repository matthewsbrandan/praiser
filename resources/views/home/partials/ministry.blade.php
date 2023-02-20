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
  <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto pt-3 blur shadow-blur">
    <div class="row">
      <div class="col-md-12 position-relative">
        <div class="p-3">
          <h5 class="mt-3 text-gradient text-primary text-center">Próxima Escala</h5>
          @if($next_scale = $next_scales->first())
            <div class="d-flex flex-column align-items-center" id="next-scale">
              <div class="d-flex flex-wrap date-theme" onclick='callModalScaled({!! $next_scale->toJson() !!})'>
                <time style="
                  font-size: 3rem;
                  line-height: 3rem;
                  margin: auto;
                ">{{ $next_scale->date_formatted }}</time>
                <div
                  class="d-flex flex-column px-3"
                  style="margin: auto;"
                >              
                  <strong>{{ $next_scale->weekday_name }}</strong>
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
                  @include('components.card-minister',[
                    'minister' => $minister,
                    'minister_config' => (object)['mode' => 'home']
                  ])
                </div>
              @endforeach              
              @if($next_scale->need_make_scale)
                <a
                  href="{{ route('scale_praise.create',['scale_id' => $next_scale->scale_id]) }}"
                  class="btn bg-gradient-primary text-center mx-auto"
                >Adicionar Ministração</a>
              @elseif($next_scale->minister_scales->count() == 0)
                <p class="text-muted text-sm mt-2 mb-0 bg-light w-100 p-3 rounded">A escala de louvores ainda não foi adicionada</p>
              @endif
            </div>
          @else
            <p class="text-sm text-center">Ainda não há escalas futuras para você.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
  @if($next_scales->count() > 1)
    <div class="col-lg-9 z-index-2 border-radius-xl mt-4 mx-auto py-3 blur shadow-blur">
      <div class="card-header pb-0">
        <h6>Escalas Futuras</h6>
      </div>
      <div class="card-body p-3">
        <div class="timeline timeline-one-side" data-timeline-axis-style="dotted">
          @foreach($next_scales->skip(1) as $scale)
            <div class="timeline-block mb-2">
              <span class="timeline-step">
                <i class="ni ni-check-bold text-info text-gradient"></i>
              </span>
              <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0">
                  {{ $scale->weekday_name }}, {{ $scale->theme }}
                </h6>
                <div class="d-flex align-items-center">
                  <p class="text-secondary font-weight-bold text-xs m-0 me-2">{{ $scale->date_formatted }}</p>
                  <div class="avatar-group d-flex align-items-center">
                    @foreach($scale->abilities as $ability)
                      <a
                        href="javascript:;"
                        class="avatar avatar-xs rounded-circle"
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
                </div>
                <button
                  type="button"
                  class="btn btn-sm text-xxs bg-gradient-info m-0 py-1 px-3"
                  onclick='callModalScaled({!! $scale->toJson() !!})'
                >Ver Escala</button>
                @foreach($scale->minister_scales as $minister)
                  @if($minister->playlist)
                    <a
                      target="_blank"
                      href="{{ $minister->playlist }}"
                      class="btn btn-sm text-xxs bg-gradient-primary m-0 py-1 px-3"
                    >Ouvir Playlist</a>
                  @else
                    <span class="badge text-xxs bg-gradient-secondary"
                    >Louvores postados</span>
                  @endif
                @endforeach
                @if($scale->need_make_scale)
                  <a
                    href="{{ route('scale_praise.create',['scale_id' => $scale->scale_id]) }}"
                    class="btn btn-sm text-xxs bg-gradient-warning m-0 py-1 px-3"
                  >Adicionar Ministração</a>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
  @if($ministry->id)
    <div class="col-lg-9 mx-auto mt-5">
      <div class="row">
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="h-100 d-flex flex-column justify-content-between">
                <div>
                  <h4>Articles</h4>
                  <p>Artigos de suporte, teoria e técnicas musicais</p>
                </div>
                <a class="btn bg-gradient-dark d-block mb-0" href="https://alive-slouch-54f.notion.site/Articles-Praiser-1b41757df37b4cfd985bb41333adf91a" target="_blank">Acessar</a>
              </div>
            </div>
          </div>
        </div>

        @php
          $cashController = new App\Http\Controllers\CashController();
          $resume = $cashController->getResume(auth()->user()->current_ministry);
        @endphp
        @if($resume)
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h4>Caixa</h4>
                <strong class="text-lg">{{ $resume->total_formatted }}</strong>
                  
                @if($resume->value_to_goal !== null)
                  <div class="progress-wrapper mx-auto mt-2">
                    <div class="progress">
                      <div
                        class="progress-bar bg-primary"
                        role="progressbar"
                        aria-valuenow="{{ $resume->percent_to_goal }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        style="width: {{ $resume->percent_to_goal }}%;"
                      ></div>
                    </div>
                  </div>
                  <p class="text-secondary text-xs mt-2 mb-0">
                    @if($resume->value_to_goal > 0)
                      Faltam {{  $resume->value_to_goal_formatted }} para alcançar o objetivo
                    @else
                      Vocês já têm caixa o suficiente para o objetivo atual
                    @endif
                  </p>
                @endif
                <a
                  class="btn bg-gradient-dark d-block mt-4 mb-0"
                  href="{{ route('cash.index') }}"
                >Acessar</a>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  @endif
  @include('scale.modals.scaled')
</div>