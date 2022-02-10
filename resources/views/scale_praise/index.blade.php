<?php
  $disable = [
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  <div class="container mt-7" style="min-height: calc(100vh - 4rem)">
    <div style="margin: auto; max-width: 900px">
      @foreach($ministers as $minister)
        <div class="card my-3">
          <div class="card-body">
            @include('components.card-minister',[
              'minister' => $minister
            ])
          </div>
        </div>
      @endforeach
      @if($ministers->count() === 0)
        <p class="text-center text-muted text-sm">Nenhuma escala cadastrada</p>
      @endif
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    function handleDeleteScale(scale_id){
      callModalMessageConfirm(
        `{{ substr(route('scale_praise.delete', ['id' => 0]),0,-1) }}${scale_id}`,
        'Tem certeza que deseja excluir essa escala?'
      );
    }
  </script>
@endsection