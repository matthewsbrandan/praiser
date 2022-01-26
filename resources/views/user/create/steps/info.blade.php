<div class="page-contents" id="content-info">
  <div class="row">
    <h6 class="mb-4">1. Informações Pessoais</h6>
    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
      <div class="d-flex align-items-center justify-content-center flex-wrap">
        <a
          href="javascript:;"
          class="avatar avatar-xl rounded-circle mx-auto d-block"
          style="width: 6rem !important; hegiht: !important; margin: 0 0 2rem;"
          onclick="$('#info-profile').click()"
        >
          <img alt="Perfil" src="{{ $user->profile }}" id="info-image-profile" style="
            object-fit: cover;
            width: 6rem;
            height: 6rem;
          "/>
        </a>
        <small class="text-xs" style="max-width: 10rem; padding: 1rem; padding-right: 0;">
          Clique na imagem para alterar a foto
        </small>
        <input
          type="file"
          class="d-none"
          name="profile"
          id="info-profile"
          onchange="handleMirrorFileImg(event, $('#info-image-profile'))"
        />
      </div>
      <strong class="text-center text-sm bg-light rounded" style="
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
      ">{{ $user->email }}</strong>
    </div>
    <div class="col-md-6">
      <label>Nome</label>
      <div class="input-group mb-4">
        <input
          class="form-control"
          placeholder="Digite seu Nome"
          aria-label="Digite seu Nome"
          type="text"
          name="name"
          id="info-name"
          value="{{ $user->name }}"
          required
        />
      </div>
    </div>
    <div class="col-md-6 ps-md-2">
      <label>Whatsapp</label>
      <div class="input-group">
        <input
          type="tel"
          name="whatsapp"
          id="info-whatsapp"
          class="form-control"
          placeholder="(00) 00000-0000"
        />
      </div>
    </div>
    <div class="col-md-6">
      <label>Senha</label>
      <div class="input-group mb-4">
        <input
          class="form-control"
          placeholder="Digite sua senha"
          aria-label="Digite sua senha"
          type="password"
          name="password"
          id="info-password"
        />
      </div>
    </div>
    <div class="col-md-6 ps-md-2">
      <label>Confirmar Senha</label>
      <div class="input-group">
        <input
          type="password"
          class="form-control"
          placeholder="Confirmar senha"
          id="info-conf-password"
        />
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <button
        type="button"
        class="btn bg-gradient-primary mt-3 mb-0"
        onclick="handlePagination(2)"
      >Próximo</button>
    </div>
  </div>
</div>