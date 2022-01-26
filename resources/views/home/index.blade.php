@extends('layout.app')
@section('content')
  <section class="pt-3 pb-4" id="count-stats">
    <div class="container">
      @if(auth()->user()->currentMinistry)
        @if(auth()->user()->currentMinistry->my_status() == 'active')
          @include('home.partials.ministry')
        @else
          @include('home.partials.disable-ministry')
        @endif
      @else
        @include('home.partials.no-ministry')
      @endif
    </div>
  </section>
@endsection