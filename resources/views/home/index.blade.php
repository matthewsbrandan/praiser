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