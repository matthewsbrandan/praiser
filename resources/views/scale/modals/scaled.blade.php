<!-- Modal -->
<div class="modal fade" id="modalScaled" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Escala</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="rounded bg-gradient-light p-2 scale-header"></div>
        <!--  
          FAZER UMA NAVEGAÇÃO EM ABAS QUE IRÁ
          INTERCALAR ENTRE A ESCALA DE LOUVORES (ABA 1)
          E A ESCALA DE MUSICOS (ABA 2)
        -->
        <div class="table-responsive mt-3 scale-table-scaled">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Função</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Escalados</th>
              </tr>
            </thead>
            <tbody class="text-sm"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function callModalScaled(scale){
    let [_,month,day] = scale.date.split('-');
    $('#modalScaled .modal-title').html(`Escala ${day}/${month}`);
    
    $('#modalScaled .modal-body .scale-header').html(`
      <h6 class="mb-0">Tema: ${scale.theme}</h6>
      <span class="text-sm text-muted">${scale.weekday_name} - ${scale.hour}</span>
    `);

    $('#modalScaled .modal-body .scale-table-scaled tbody').html(scale.resume.map(item => {
      return `
        <tr>
          <th>${handleFormatteFunction(item.ability)}</th>
          <td>${item.users.join(', ')}</td>
        </tr>
      `;
    }).join(' '));

    $('#modalScaled').modal('show');
  }
  function handleFormatteFunction(index){
    let functions = {
      "ministro": "Ministro",
      "violao": "Violão",
      "back-vocal": "Backvocal",
      "baixo": "Baixo",
      "cajon": "Cajon",
      "bateria": "Bateria",
      "datashow": "Datashow",
      "guitarra": "Guitarra",
      "mesario": "Mesário",
    }
    return functions[index] ?? index;
  }
</script>