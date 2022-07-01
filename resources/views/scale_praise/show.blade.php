<?php
  $disable = [
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  <div class="container mt-7" style="min-height: calc(100vh - 4rem)">
    <div class="row py-4">
      <div class="col-lg-6">
        <h3 class="text-gradient text-primary mb-0 mt-2">Ministração</h3>
        @if($scale)
          <div class="d-flex flex-wrap mx-auto mt-2 text-dark">
            <time style="font-size: 3rem; line-height: 3rem;">{{ $scale->date_formatted }}</time>
            <div class="d-flex flex-column px-3">
              <strong>{{ $scale->weekday_formatted }}</strong>
              <span>Tema: {{ $scale->theme }}</span>
            </div>
          </div>
        @else
          <h3>Ministração Particular</h3>
        @endif
      </div>      
    </div>
    <div class="row">
      <div class="col-md-8 mb-md-0 mb-5">
        <div class="h-100" id="container-youtube">
          <div class="content h-100" style="display: none;"></div>
          <div class="card m-3 empty-message h-100">
            <div class="card-body d-flex align-items-center justify-content-center">
              <strong>Nenhum vídeo disponível</strong>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        @include('components.card-minister',[
          'minister' => $minister
        ])
      </div>
      <div class="col-12 mt-5" id="container-cipher">
        <iframe
          id="embed"
          style="width: 100%; height: 100vh; border: 1px solid #eef; display: none"
          src=""
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
        ></iframe>
        <div class="card m-3 empty-message h-100">
          <div class="card-body d-flex align-items-center justify-content-center">
            <strong>Nenhuma cifra disponível</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    let nextPraise = null;
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;
    function handleRenderYoutube(youtube_id){
      let html = `<div id="player"></div>`;
      $('#container-youtube .content').html(html);
      onYouTubeIframeAPIReady(youtube_id);
    }
    function onYouTubeIframeAPIReady(id){
      if(!id){
        $('#container-youtube .empty-message').show('slow');
        $('#container-youtube .content').hide('slow')
        return;
      }else{
        $('#container-youtube .empty-message').hide('slow');
        $('#container-youtube .content').show('slow')
      }

      player = new YT.Player('player', {
        height: '100%',
        width: '100%',
        videoId: id,
        events: {
          'onReady': onPlayerReady,
          'onStateChange': onPlayerStateChange
        }
      });
    }
    function onPlayerReady(event){ event.target.playVideo(); }
    function onPlayerStateChange(event){
      if(event.data == YT.PlayerState.ENDED){
        if(nextPraise && nextPraise[0]) nextPraise.children('.praise-description').click();
        else $('.list-group-flush .list-group-item:first-child .praise-description').click();
      }
    }
    function handleSelectPraise(){
      let description = $(this).html();
      let li = $(this).parent();
      nextPraise = li.next();
      let containerLinks = $(this).next();
      let aYoutube = containerLinks.children('a.youtube_link');
      let aCipher = containerLinks.children('a.cipher_link');

      if(aYoutube[0]){
        let youtube_url = aYoutube.attr('href');
        let youtube_id = handleYoutubeId(youtube_url);
        try{
          handleRenderYoutube(youtube_id); 
        }catch(e){}
      }
      if(aCipher[0]){
        let cipher_url = aCipher.attr('href');
        $('#container-cipher .empty-message').hide('slow');
        $('#container-cipher iframe').attr('src',cipher_url).show('slow');
      }else{
        $('#container-cipher .empty-message').show('slow');
        $('#container-cipher iframe').attr('src','').hide('slow');
      }

      $('.list-group-item').removeClass('bg-light');
      li.addClass('bg-light');
    }
    function handleYoutubeId(url){
      if(!url) return null;
      try{
        if(url.includes('?v=')){
          [_, url] = url.split('?v=');
          [id, _] = url.split('&');
        }else
        if(url.includes('youtu.be/')){
          [_, url] = url.split('youtu.be/');
          [id, _] = url.split('?');
        }else return null;
        return id;
      }catch(e){ return null; }
    }
    $(function(){
      $('.see-scale').remove();
      $('.praise-description').on('click', handleSelectPraise);
      $('.praise-description').css('cursor','pointer');

      setTimeout(
        () => $('.list-group-flush .list-group-item:first-child .praise-description').click(),
        1000
      );
    });
  </script>
@endsection