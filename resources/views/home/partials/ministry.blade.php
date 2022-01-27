<?php
  $ministry = auth()->user()->currentMinistry;
?>
<div class="row">
  <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto py-3 blur shadow-blur">
    <div class="row">
      <div class="col-md-12 position-relative">
        <div class="p-3 text-center">
          <h5 class="mt-3 text-gradient text-primary">Próxima Escala</h5>
          @if($next_scale)
            <div class="d-flex flex-column justify-content-center">
              <div class="text-center">
                <time style="font-size: 3rem; line-height: 4rem;">{{ $next_scale->date_formatted }}</time>
                <h6 class="rounded bg-gradient-light">{{ $next_scale->weekday_formatted }}</h6>
              </div>
              <div class="p-2 d-flex flex-column align-items-center justify-content-center">
                <h5>{{ $next_scale->theme }}</h5>
                <div class="avatar-group">
                  @foreach($next_scale->abilities as $ability)
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
                </div>
              </div>
            </div>
          @else
            <p class="text-sm">Ainda não há escalas futuras para você.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>