@php
  $header = (object)['title' => 'Votação: Louvores p/ Setembro'];
@endphp
@extends('layout.app')
@section('content')
  <section class="pt-3 pb-4">
    <style>
      .spinner {
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        width: 14px;
        height: 14px;
        animation: spin 1s linear infinite;
        display: inline-block;
        vertical-align: middle;
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    </style>
    <div class="container">
      <div class="row">
        @if($status === 'Em Apuração')
          <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto py-3 blur shadow-blur d-flex">
            <div class="m-auto d-flex flex-column">
              <strong class="text-center text-lg mb-3">Votação encerrada, em apuração de dados.</strong>
              @if(auth()->user()->type === 'dev')
                <a class="btn btn-primary mx-auto" href="{{ route('vote.praises.result') }}">
                  Acessar Resultados
                </a>
              @endif
            </div>
          </div>
        @else
          <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto pt-3 blur shadow-blur">
            <div class="row">
              @foreach($praises as $praise)              
                <div class="col-md-12 position-relative">
                  <div class="p-3 d-flex" style="gap: 1rem;">
                    <div
                      class="video-wrapper"
                      style="position: relative; width: 18rem; height: 12rem; cursor: pointer;"
                      tabindex="{{ $loop->index }}" aria-label="Reproduzir vídeo: {{ $praise->title }}"
                      data-video-id="{{ $praise->youtube_id }}"
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
                        <button class="btn {{ $praise->vote === 'like' ? 'btn-primary':'btn-light' }} btn-like" data-id="{{ $praise->youtube_id }}">
                          👍 Like
                        </button>
                        <button class="btn {{ $praise->vote === 'dislike' ? 'btn-primary':'btn-light' }} btn-dislike" data-id="{{ $praise->youtube_id }}">
                          👎 Dislike
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection
@section('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll(".video-wrapper").forEach(box => {
        const play = () => {
          const videoId = box.dataset.videoId;
          if (!videoId) return;

          let iframe = document.createElement("iframe");
          iframe.width = "100%";
          iframe.height = "100%";
          iframe.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
          iframe.frameBorder = "0";
          iframe.allow =
            "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
          iframe.allowFullscreen = true;

          box.replaceChildren(iframe);
        }
        
        box.addEventListener("click", play, { once: true });
      });

      document.querySelectorAll(".btn-like, .btn-dislike").forEach(btn => {
        btn.addEventListener("click", function () {
          const youtube_id = this.dataset.id;
          const type = this.classList.contains("btn-like") ? "like" : "dislike";
          this.innerHTML = `<span class="spinner"></span>`;

          fetch("{{ route('vote.praises.register') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ youtube_id, type })
          }).then(r => r.json()).then((res) => {
            document.querySelector(`[data-id="${youtube_id}"].btn-like`).classList.add(type === 'like' ? 'btn-primary' : 'btn-light');
            document.querySelector(`[data-id="${youtube_id}"].btn-like`).classList.remove(type === 'like' ? 'btn-light' : 'btn-primary');
            document.querySelector(`[data-id="${youtube_id}"].btn-dislike`).classList.add(type === 'dislike' ? 'btn-primary' : 'btn-light');
            document.querySelector(`[data-id="${youtube_id}"].btn-dislike`).classList.remove(type === 'dislike' ? 'btn-light' : 'btn-primary');
             
            this.innerHTML = type === "like" ? "👍 Like" : "👎 Dislike";
          }).catch((e) => {
            console.error(e);
            this.innerHTML = type === "like" ? "👍 Like" : "👎 Dislike";
          })
        });
      });
    });
  </script>
@endsection