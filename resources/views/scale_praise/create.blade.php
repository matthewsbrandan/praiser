<?php
  $disable = [
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  @include('utils.in_development',[
    'features' => [(object)[
        'icon' => '<i class="fas fa-ship opacity-10"></i>',
        'description' => 'Cadastro de escala de louvores com espaço para versículo e mensagem'
      ],(object)[
        'icon' => '<i class="fas fa-handshake opacity-10"></i>',
        'description' => 'Feed de ministrações, com todas as ministrações do ministério'
      ],(object)[
        'icon' => '<i class="fas fa-hourglass opacity-10"></i>',
        'description' => 'Seção minhas ministrações, com todas as ministrações que você já fez'
      ]
    ]
  ])
@endsection