@php
  $header = (object)['title' => 'Votação: Louvores p/ Setembro'];
@endphp
@extends('layout.app')
@section('content')
  <section class="pt-3 pb-4">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto pt-3 blur shadow-blur">
          <div class="row">
            @foreach($praises as $praise)              
              <div class="col-md-12 position-relative">
                <div class="p-3 d-flex" style="gap: 1rem;">
                  <div
                    class="video-wrapper"
                    style="position: relative; width: 18rem; height: 12rem; cursor: pointer;"
                    onClick="playVideo(this, '{{ $praise->youtube_id }}')"
                  >
                    <img
                      src="https://img.youtube.com/vi/{{ $praise->youtube_id }}/hqdefault.jpg"
                      alt="{{ $praise->title }}"
                      style="width: 100%; height: 100%; object-fit: cover; border: 1px solid #eef;"
                    >
                    <div style="
                      position: absolute; top: 50%; left: 50%;
                      transform: translate(-50%, -50%);
                      font-size: 3rem; color: white;
                      background: rgba(0,0,0,0.5); border-radius: 50%; padding: 0rem 1rem;"
                    >▶</div>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <strong>{{ $praise->title }}</strong>
                    <div class="mt-4 d-flex gap-2">
                      <button class="btn btn-light btn-like" data-id="{{ $loop->index }}">
                        👍 Like
                      </button>
                      <button class="btn btn-light btn-dislike" data-id="{{ $loop->index }}">
                        👎 Dislike
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
  <script>
    function playVideo(el, videoId){
      console.log('[clicked]');

      let iframe = document.createElement("iframe");
      iframe.width = "100%";
      iframe.height = "100%";
      iframe.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
      iframe.frameBorder = "0";
      iframe.allow =
        "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
      iframe.allowFullscreen = true;

      el.replaceChildren(iframe);
    }
  </script>
@endsection