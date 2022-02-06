<style>
  @media(max-width: 991px){
    .dropdown-menu.dropdown-menu-animation.dropdown-md{
      max-height: fit-content;
      overflow: hidden;
    }
  }
  @media(max-width: 1200px){
    .ms-lg-12{
      margin-left: 1rem !important;
    }
  }

  
</style>
<div class="container position-sticky z-index-sticky top-0">
  <div class="row">
    <div class="col-12">
      <nav
        class="navbar navbar-expand-lg  blur blur-rounded top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
        <div class="container-fluid">
          @if(auth()->user() && auth()->user()->currentMinistry)
            <a class="navbar-brand font-weight-bolder ms-sm-3 d-flex align-items-center"
              href="{{ route('home') }}" rel="tooltip"
              title="{{ auth()->user()->currentMinistry->name }}" data-placement="bottom"
            >
              <span class="avatar avatar-xs rounded-circle">
                <img
                  alt="Ministério" src="{{ auth()->user()->currentMinistry->getImage() }}"
                  style="height: 100%; object-fit: over;"
                >
              </span>
              <span style="padding-left: .4rem;">
                {{ auth()->user()->currentMinistry->name }}
              </span>
            </a>
          @else          
            <a class="navbar-brand font-weight-bolder ms-sm-3"
              href="{{ route('index') }}" rel="tooltip"
              title="Praiser" data-placement="bottom" target="_blank"
            >
              Praiser
            </a>
          @endif
          <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
            data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
              <span class="navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>
          </button>
          <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
            @auth
              <ul class="navbar-nav navbar-nav-hover ms-lg-12 ps-lg-5 w-100">
                @if(auth()->user()->currentMinistry && auth()->user()->currentMinistry->my_status() == 'active')
                  <!-- SCALES -->
                  <li class="nav-item dropdown dropdown-hover mx-2">
                    <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center"
                      id="dropdownMenuScale" data-bs-toggle="dropdown" aria-expanded="false">
                      Escalas
                      <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-1">
                    </a>
                    <div class="dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3"
                      aria-labelledby="dropdownMenuScale"
                    >
                      <div>
                        <a href="{{ route('scale.week') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Escala da Semana</span>
                        </a>
                        <a href="{{ route('scale.month') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Escala do Mês</span>
                        </a>
                        @if(auth()->user()->currentMinistry->hasPermissionTo('can_manage_scale'))                    
                          <a href="{{ route('scale.create') }}" class="dropdown-item border-radius-md">
                            <span class="ps-3">Gerenciar Escala</span>
                          </a>
                          <a href="{{ route('scale.month.edition') }}" class="dropdown-item border-radius-md">
                            <span class="ps-3">Escalas em Edição</span>
                          </a>
                        @endif
                        @if(auth()->user()->devOnly())
                          <a
                            href="{{ route('scale.create',['import' => 'importar']) }}"
                            class="dropdown-item border-radius-md"
                          >
                            <span class="ps-3">Importar Escala</span>
                          </a>
                        @endif
                      </div>
                    </div>
                  </li>
                  <!-- PRAISES -->
                  <li class="nav-item dropdown dropdown-hover mx-2">
                    <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center"
                      id="dropdownMenuPraises" data-bs-toggle="dropdown" aria-expanded="false">
                      Louvores
                      <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-1">
                    </a>
                    <div class="dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3"
                      aria-labelledby="dropdownMenuPraises"
                    >
                      <div>
                        <a href="{{ route('praise.index') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Todos Louvores</span>
                        </a>
                        <a href="{{ route('praise.favorite') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Favoritos</span>
                        </a>
                        <a href="{{ route('praise.create') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Novo Louvor</span>
                        </a>
                        @if(auth()->user()->devOnly())
                          <a
                            href="{{ route('praise.create',['import' => 'importar']) }}"
                            class="dropdown-item border-radius-md"
                          >
                            <span class="ps-3">Importar Lista</span>
                          </a>
                          <a
                            href="{{ route('praise.without.link') }}"
                            class="dropdown-item border-radius-md"
                          >
                            <span class="ps-3">Sem Link</span>
                          </a>
                        @endif
                      </div>
                    </div>
                  </li>
                  <!-- MINISTRATIONS -->
                  <li class="nav-item dropdown dropdown-hover mx-2">
                    <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center"
                      id="dropdownMenuPraises" data-bs-toggle="dropdown" aria-expanded="false">
                      Ministrações
                      <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-1">
                    </a>
                    <div class="dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3"
                      aria-labelledby="dropdownMenuPraises"
                    >
                      <div>
                        <a href="{{ route('scale_praise.index') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Todas Ministrações</span>
                        </a>
                        <a href="{{ route('scale_praise.my') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Minhas Ministrações</span>
                        </a>
                        <a href="{{ route('scale_praise.create') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Nova Ministração</span>
                        </a>
                      </div>
                    </div>
                  </li>
                @endif
                <!-- MINISTRIES -->
                <li class="nav-item dropdown dropdown-hover mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center"
                    id="dropdownMenuMinistries" data-bs-toggle="dropdown" aria-expanded="false">
                    Ministérios
                    <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-1">
                  </a>
                  <div class="dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3"
                    aria-labelledby="dropdownMenuMinistries"
                  >
                    <div>
                      @foreach(auth()->user()->ministries as $ministry)
                        <a href="{{ route('ministry.select', ['id' => $ministry->id])}}" class="dropdown-item border-radius-md d-flex align-items-center">
                          <span class="avatar avatar-xs rounded-circle">
                            <img
                              alt="Ministério" src="{{ $ministry->getImage() }}"
                              style="height: 100%; object-fit: over;"
                            >
                          </span>
                          <span
                            style="padding-left: .4rem;"
                            @class([
                              'font-weight-bold' => $ministry->id == (auth()->user()->currentMinistry->id ?? null)
                            ])
                          >
                            {{ $ministry->name }}
                          </span>
                        </a>
                      @endforeach
                      <a href="{{ route('ministry.outhers') }}" class="dropdown-item border-radius-md">
                        <span class="ps-3">Ingressar em outro</span>
                      </a>
                      @if(auth()->user()->adminOnly())
                        <a href="{{ route('ministry.create') }}" class="dropdown-item border-radius-md">
                          <span class="ps-3">Criar Ministério</span>
                        </a>
                      @endif
                    </div>
                  </div>
                </li>
                <li class="nav-item ms-lg-auto"></li>
                <li class="nav-item my-auto ms-3 ms-lg-0">
                  <a
                    href="{{ route('user.profile') }}"
                    class="btn btn-sm  bg-gradient-primary  btn-round mb-0 me-1 mt-2 mt-md-0"
                  >Perfil</a>
                </li>
              </ul>
            @endauth
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
    </div>
  </div>
</div>