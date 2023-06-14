@php
  $header = (object)['title' => 'Caixa: ' . $cash->name];
@endphp
@extends('layout.app')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/css/glide.core.min.css" integrity="sha512-YQlbvfX5C6Ym6fTUSZ9GZpyB3F92hmQAZTO5YjciedwAaGRI9ccNs4iw2QTCJiSPheUQZomZKHQtuwbHkA9lgw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/css/glide.theme.min.css" integrity="sha512-wCwx+DYp8LDIaTem/rpXubV/C1WiNRsEVqoztV0NZm8tiTvsUeSlA/Uz02VTGSiqfzAHD4RnqVoevMcRZgYEcQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/glide.min.js" integrity="sha512-IkLiryZhI6G4pnA3bBZzYCT9Ewk87U4DGEOz+TnRD3MrKqaUitt+ssHgn2X/sxoM7FxCP/ROUp6wcxjH/GcI5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@section('header')
@endsection
@section('content')
  <section class="pt-3 pb-4">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto pt-3 blur shadow-blur">
          <div class="row">
            <div class="col-md-12 position-relative">
              <div class="p-3">
                <div class="mb-5 text-center">
                  <span class="text-xs text-center w-full d-block">caixa atual</span>
                  <h1 class="mb-3" style="line-height: 1;">{{ $resume->total_formatted }}</h1>
                
                  @if($resume->value_to_goal !== null)
                    <div class="progress-wrapper mx-auto" style="max-width: 20rem;">
                      <div class="progress" style="background-color: #b6b9bc;">
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
                </div>
                <div style="
                  max-height: 20rem;
                  overflow-y: auto;
                ">
                  <div class="timeline timeline-one-side">
                    @foreach($launchs as $launch)
                      <div class="timeline-block mb-3">
                        <span class="timeline-step">
                          @if($launch->type === 'income')
                            <i class="ni ni-bold-up text-success text-gradient"></i>
                          @elseif($launch->type === 'expense')
                            <i class="ni ni-bold-down text-danger text-gradient"></i>
                          @endif
                        </span>
                        <div class="timeline-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="me-md-3 align-self-start">
                              <h6 
                                class="text-sm font-weight-bold mb-0 {{ $launch->type === 'income' ? 'text-dark ': 'text-danger' }}"
                                style="white-space: nowrap;"
                              >{{ $launch->value_formatted }}</h6>
                              <p class="text-secondary font-weight-bold text-xs mb-1">{{ $launch->date_formatted ?? '-' }}</p>
                            </div>
                            <p class="text-secondary text-sm mb-0">
                              <span class="font-weight-bold">{{ $launch->title }}:</span><br/>
                              {{ $launch->description }}
                            </p>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @php
          $unCompletedGoals = $goals->filter(function($goal){ return !$goal->is_completed; });
          // [ ] LISTAR GOALS COMPLETED
        @endphp
        @if(count($unCompletedGoals) > 0)
          <div class="col-12 mt-6">
            <h3 class="text-center mb-4">Objetivos de Compra</h3>
            <div class="glide">
              <div data-glide-el="track" class="glide__track">
                <ul class="glide__slides">
                  @foreach($unCompletedGoals as $goal)
                  <li class="glide__slide">
                      <div class="d-flex flex-column mx-auto" style="max-width: 18rem;">
                        <img
                          src="{{ $goal->image }}"
                          class="mx-auto border shadow"
                          style="width: 15rem; height: 15rem; border-radius: 1rem; object-fit: cover;"
                        />
                        <div class="text-center mt-3">
                          <strong>{{ $goal->title }}</strong><br/>
                          @if(isset($goal->on_budget) && $goal->on_budget)
                            <span class="text-sm">Em Orçamento</span>
                          @else
                            <span>{{ $goal->value_formatted }}</span> - <span>{{ $goal->value_max_formatted }}</span>
                          @endif
                          @isset($goal->links)
                            <div class="d-flex flex-column mt-3">
                              @foreach($goal->links as $link)
                                <a
                                  href="{{ $link->href }}"
                                  class="btn btn-sm bg-gradient-secondary mb-2"
                                  target="_blank"
                                >{{ $link->name }}</a>
                              @endforeach
                            </div>
                          @endisset
                        </div>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
              @if(count($unCompletedGoals) > 1)
                <div class="glide__arrows" data-glide-el="controls">
                  <button class="glide__arrow glide__arrow--left" data-glide-dir="<">
                    <i class="ni ni-bold-left text-dark text-gradient"></i>
                  </button>
                  <button class="glide__arrow glide__arrow--right" data-glide-dir=">">
                    <i class="ni ni-bold-right text-dark text-gradient"></i>
                  </button>
                </div>
              @endif
            </div>
          </div>
        @endif
        <div class="col-md-6 mx-auto mt-6">
          <h3 class="text-center mb-4">Como Contribuir?</h3>

              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column flex-xl-row justify-content-between align-items-center">
                    <strong class="text-dark">PIX - CPF</strong>
                    <div>
                      <span>476.156.288-90</span><br/>
                      <span class="text-sm">Mateus | Santander</span>
                    </div>
                    <button
                      type="button"
                      class="btn btn-sm bg-gradient-secondary mb-0 mt-3 mt-xl-0"
                      onclick="navigator.clipboard.writeText('47615628890'); notify('success', 'Copiado!');"
                    >Copiar</button>
                  </div>
                </div>
              </div>

              <div class="card mt-4">
                <div class="card-body d-flex flex-column">
                  <strong class="text-dark">Ou entre em Contato</strong>
                  <div class="row mt-3">
                    <div class="col-xl-4 col-lg-6">
                      <a
                        target="_blank"
                        href="https://wa.me/551995446606"
                        class="btn btn-sm bg-gradient-light d-flex"
                        style="gap: .5rem;"
                      >
                        <img
                          src="https://imagepng.org/wp-content/uploads/2017/08/whatsapp-icone-1.png"
                          alt="Whatsapp"
                          style="width: 1.5rem;"
                        />
                        <span class="text-sm">Mateus</span>
                      </a>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                      <a
                        target="_blank"
                        href="https://wa.me/5519983382816"
                        class="btn btn-sm bg-gradient-light d-flex"
                        style="gap: .5rem;"
                      >
                        <img
                          src="https://imagepng.org/wp-content/uploads/2017/08/whatsapp-icone-1.png"
                          alt="Whatsapp"
                          style="width: 1.5rem;"
                        />
                        <span class="text-sm">Sérgio</span>
                      </a>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                      <a
                        target="_blank"
                        href="https://wa.me/5519991344087"
                        class="btn btn-sm bg-gradient-light d-flex"
                        style="gap: .5rem;"
                      >
                        <img
                          src="https://imagepng.org/wp-content/uploads/2017/08/whatsapp-icone-1.png"
                          alt="Whatsapp"
                          style="width: 1.5rem;"
                        />
                        <span class="text-sm">Bruna</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
  <script>
    new Glide('.glide',{
      type: 'slider',
      startAt: 0,
      perView: 3,
      breakpoints: {
        900:  { perView: 1 },
        1200: { perView: 2 }
      }
    }).mount();
  </script>
@endsection