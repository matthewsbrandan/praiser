<!-- Modal -->
<div class="modal fade" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
      </div>
    </div>
  </div>
</div>
<script>
  function callModalMessage(body, header = 'Praise'){
    $('#modalMessage .modal-title').html(header);
    $('#modalMessage .modal-body').html(body);

    $('#modalMessage').modal('show');
  }
</script>