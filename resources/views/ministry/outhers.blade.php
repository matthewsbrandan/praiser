@extends('layout.app')
@section('content')
  <section class="pt-3 pb-4" id="count-stats">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h3 class="mb-5">Ministérios</h3>
        </div>
      </div>
      <div class="row">
        @foreach ($ministries as $ministry)        
          <div class="col-lg-3 col-sm-6">
            <div class="card card-plain card-blog">
              <div class="card-image border-radius-lg position-relative">
                <a href="{{ route('ministry.bind', ['slug' => $ministry->slug]) }}">
                  <img
                    class="w-100 border-radius-lg move-on-hover shadow" src="{{ $ministry->getImage() }}"
                    style="object-fit: cover; height: 15rem;"
                  >
                </a>
              </div>
              <div class="card-body px-0">
                <h5>
                  <a href="{{ route('ministry.bind', ['slug' => $ministry->slug]) }}" class="text-dark font-weight-bold">
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
        @if($ministries->count() == 0)
          <p class="text-muted">
            Não há outros ministérios disponíveis
          </p>
        @endif
      </div>   
    </div>
  </section>
@endsection