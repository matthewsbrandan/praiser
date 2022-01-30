<div class="page-contents" id="content-days">
  <div class="row">
    <div class="col-md-12">
      <label>Adicione os dias</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          type="date"
          id="scale-days"
          required
        />
        <button
          type="button"
          class="btn bg-gradient-light mb-0"
          onclick="handleAddDays()"
        >Adicionar</button>
      </div>
    </div>
    <div class="col-md-12">
      <div class="d-flex flex-wrap" style="gap: .6rem;" id="container-days-selected"></div>
    </div>
    <div class="col-md-12 text-center">
      <button 
        type="button" class="btn bg-gradient-primary mt-3 mb-0"
        onclick="handlePagination(2)"
      >Próximo</button>
    </div>
  </div>
</div>