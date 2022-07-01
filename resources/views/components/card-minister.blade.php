<div class="d-flex align-items-center justify-content-between mb-3">
  <div class="d-flex align-items-center" style="flex: 1;">
    <a
      href="javascript:;"
      class="avatar avatar-sm rounded-circle"
      data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $minister->user->name }}"
    >
      <img
        alt="Ministro"
        src="{{ $minister->user->getProfile() }}"
        style="height: 100%; object-fit: cover;"
      />
    </a>
    <a
      href="{{ $minister->user_id == auth()->user()->id ? route('scale_praise.edit', ['id' => $minister->id]) :'javascript:;' }}"
      class="mx-2" style="letter-spacing: inherit; flex: 1;"
    >
      @if(isset($minister_config) && $minister_config->mode == 'home')
        <h6 class="mb-0"> {{$minister->user->name}} </h6>
      @elseif($minister->scale)
        <h6 class="mb-0">
          Escala {{ $minister->scale->weekday_formatted }} @include('utils.icons.'.(
            $minister->privacy == 'public' ? 'word' : 'lock'
          ),['icon' => (object)[
            'width' => '18px',
            'height' => '18px'
          ]])
        </h6>
        <span class="text-muted text-sm d-block" style="margin-top: -0.2rem;">
          {{ $minister->scale->date_formatted }}
        </span>
      @else
        <h6 class="mb-0">
          Escala Particular @include('utils.icons.'.($minister->privacy == 'public' ? 'word' : 'lock'),['icon' => (object)[
            'width' => '18px',
            'height' => '18px'
          ]])
        </h6>
      @endif
    </a>
  </div>
  @if($minister->user_id == auth()->user()->id && 
    (!isset($minister_config) || $minister_config->mode != 'home')
  )
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
      <span class="praise-description">
        {{
          ($praise->legend ? $praise->legend . ': ' : '') .
          ($praise->praise->name) . 
          ($praise->tone ? ' - ' . $praise->tone:'')
        }}
        <em class="d-block text-xs">{{ $praise->praise->singer }}</em>
      </span>
      <div>
        @if($praise->youtube_link)
          <a
            href="{{ $praise->youtube_link }}" target="_blank"
            class="avatar avatar-xs rounded-circle youtube_link"
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
            class="avatar avatar-xs rounded-circle cipher_link"
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
<a
  href="{{ route('scale_praise.show', ['id' => $minister->id]) }}"
  class="btn btn-sm bg-gradient-light mt-2 mb-4 see-scale"
>Ver</a>
@if($minister->playlist)
  <a
    target="_blank"
    href="{{ $minister->playlist }}"
    class="btn btn-sm bg-gradient-primary mt-2 mb-4"
  >Ouvir Playlist</a>
@endif
<button
  type="button"
  class="btn btn-sm bg-gradient-dark mt-2 mb-4"
  onclick='handleShareMinistring($(this).next(), {!! $minister->toJson() !!});'
>@include('utils.icons.share', ['icon' => (object)[
  'width' => '18px',
  'height' => '18px',
]])</button>
<a target="_blank" href="javascript:;" class="d-none"></a>
<br/>
@if($minister->verse) <strong>{{ $minister->verse }}</strong> @endif
@if($minister->about) <p class="text-sm">{{ $minister->about }}</p> @endif

@once
  <script>
    function handleShareMinistring(target, scale){
      let sharePraises = [];
      scale.scale_praises.forEach(praise => {
        let description =
          (praise.legend ? `${praise.legend}: ` : '') + 
          praise.praise.name + 
          (praise.tone ? ` - ${praise.tone}` : '')
        ;
        sharePraises.push(`- ${description.trim()}`);
      });

      if(scale.playlist) sharePraises.push(`\n${scale.playlist}`);
      if(scale.verse) sharePraises.push(`\n*${scale.verse}*`);
      if(scale.about) sharePraises.push(`${!scale.verse?'\n':''}${scale.about}`);

      let shareMinistering = encodeURIComponent(scale.header + sharePraises.join('\n'));

      target.attr('href',"https://api.whatsapp.com/send?text="+shareMinistering);
      target[0].click();
    }
  </script>
@endonce