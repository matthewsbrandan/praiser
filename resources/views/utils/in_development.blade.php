<div class="container mt-7">
    <div class="row py-4">
      <div class="col-lg-6">
        <h3 class="text-gradient text-primary mb-0 mt-2">Sessão em Desenvolvimento</h3>
        <h3>Ainda estamos trabalhando nessa nova funcionalidade</h3>
        <p>Ao final das atualizações você será notificado da liberação, enquanto isso veja ao seguir o que estará agregado nesta atualização.</p>
      </div>
      <div class="col-lg-6 mt-lg-0 mt-5 ps-lg-0 ps-0">
        @foreach($features as $feature)
          <div class="p-3 info-horizontal">
            <div class="icon icon-shape rounded-circle bg-gradient-primary shadow text-center">
              {!! $feature->icon !!}
            </div>
            <div class="description ps-3">
              <p class="mb-0">{{ $feature->description }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>