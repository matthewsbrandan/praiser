<?php
  $disable = ['header'];
?>
@extends('layout.app')
@section('content')
  @include('layout.header',['header' => (object)[
    'title' => 'Louvores Sem Link',
    'subtitle' => "<span id='count-links'>0</span>/".$without_links->count()
  ]])
  <section class="pt-3 pb-4">
    <div class="container" id="container-result">
    </div>
  </section>
@endsection
@section('scripts')
  <script>
    const without_links = {!! $without_links->toJson() !!};
    let countLinks = 0;
    $(function(){
      $('body').css('background-color','#121214');
      without_links.forEach(async (praise) => {
        let data = {
          id: praise.id,
          name: praise.name,
          singer: praise.singer,
        };
        await $.post(`{{ route('gold_miner.youtube') }}`, data).done(data => {
          $('#container-result').append(`
            <p class="text-sm ${data.result ? 'text-success':'text-danger'}">${praise.name}</p>
          `);
        }).fail(err => {
          $('#container-result').append(`
            <p class="text-sm text-danger">${praise.name}</p>
          `);
        }).always(() => {
          countLinks++;
          $('#count-links').html(countLinks);
        });
      });
    });
  </script>
@endsection