@php
  $header = (object)['title' => 'Resultado da Votação: Louvores p/ Setembro'];
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
        <div class="col-lg-12 z-index-2 border-radius-xl mt-n10 mx-auto pt-3 blur shadow-blur">
          <div class="row">
            <div class="col-md-4 position-relative">
              <h3>Mais Curtidos</h3>
              <div style="overflow: auto;">
                <table class="table table-striped table-bordered text-center align-middle">
                  <thead class="table-dark">
                    <tr>
                      <th>Título</th>
                      <th>Likes</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($topLikes as $praise)
                      <tr>
                        <td class="text-start">{{ $praise->title }}</td>
                        <td>{{ $praise->likes }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-md-4 position-relative">
              <h3>Melhor Saldo</h3>
              <div style="overflow: auto;">
                <table class="table table-striped table-bordered text-center align-middle">
                  <thead class="table-dark">
                    <tr>
                      <th>Título</th>
                      <th>Saldo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($topLikes as $praise)
                      <tr>
                        <td class="text-start">{{ $praise->title }}</td>
                        <td>{{ $praise->balance }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-md-4 position-relative">
              <h3>Pior Avaliado</h3>
              <div style="overflow: auto;">
                <table class="table table-striped table-bordered text-center align-middle">
                  <thead class="table-dark">
                    <tr>
                      <th>Título</th>
                      <th>Saldo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($topDeslike as $praise)
                      <tr>
                        <td class="text-start">{{ $praise->title }}</td>
                        <td>{{ $praise->balance }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-md-12 position-relative">
              <h3>Ranking</h3>
              <div style="overflow: auto;">
                <table class="table table-striped table-bordered text-center align-middle">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>Título</th>
                      <th>Likes</th>
                      <th>Deslikes</th>
                      <th>Saldo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($orderedPraises as $index => $praise)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start">{{ $praise->title }}</td>
                        <td class="text-success fw-bold">{{ $praise->likes }}</td>
                        <td class="text-danger fw-bold">{{ $praise->deslikes }}</td>
                        <td class="{{ $praise->balance >= 0 ? 'text-success' : 'text-danger' }}">
                          {{ $praise->balance }}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
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