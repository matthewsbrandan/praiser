<!-- Modal -->
<div class="modal fade" id="modalAddLaunch" tabindex="-1" role="dialog" aria-labelledby="exampleModalAddLaunchTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalAddLaunchTitle">
          Adicionar Lançamento
        </h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('cash.launch.store',[
          'id' => $cash->id
        ]) }}" method="POST">
          {{ csrf_field() }}
          {{-- 
            'date',
            'type',
          --}}

          <div class="row">
            <div class="col-md-6">
              <label for="cash-launch-title">Título</label>
              <div class="input-group mb-4">
                <input
                  class="form-control"
                  placeholder="Título do lançamento..."
                  aria-label="Título do lançamento..."
                  name="title"
                  id="cash-launch-title"
                  type="text"
                  required
                >
              </div>
            </div>
            <div class="col-md-6 ps-2">
              <label for="cash-launch-description">Descrição</label>
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Descrição do lançamento..."
                  aria-label="Descrição do lançamento..."
                  id="cash-launch-description"
                  name="description"
                  required
                >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label for="cash-launch-value">Valor</label>
              <div class="input-group mb-4">
                <input
                  type="number"
                  class="form-control"
                  placeholder="Valor do lançamento..."
                  id="cash-launch-value"
                  name="value"
                  required
                >
              </div>
            </div>
            <div class="col-md-6 ps-2">
              <label for="cash-launch-date">Data</label>
              <div class="input-group">
                <input
                  type="date"
                  class="form-control"
                  placeholder="Data do lançamento..."
                  aria-label="Data do lançamento..."
                  id="cash-launch-date"
                  name="date"
                  required
                >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" id="cash-launch-is-income" name="income" checked>
                <label class="form-check-label" for="cash-launch-is-income">
                  Contribuição (Desmarcar caso seja um gasto)
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <button
                type="submit"
                class="btn bg-gradient-dark w-100 mb-0"
              >Salvar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>