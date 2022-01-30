<script>
  const classesAlerts = {
    primary: "alert alert-primary text-white font-weight-bold border-0",
    secondary: "alert alert-secondary text-white font-weight-bold border-0",
    success: "alert alert-success text-white font-weight-bold border-0",
    danger: "alert alert-danger text-white font-weight-bold border-0",
    warning: "alert alert-warning text-white font-weight-bold border-0",
    info: "alert alert-info text-white font-weight-bold border-0",
    light: "alert alert-light text-white font-weight-bold border-0",
    dark: "alert alert-dark text-white font-weight-bold border-0",
  };
  function notify(type, text, timeout = 6000){
    let notify_id = `${type}_${$('#container-alerts .alert').length}`;
    $('#container-alerts').prepend(`
      <div
        class="${classesAlerts[type]}"
        role="alert"
        id="${notify_id}"
        style="display: none; min-width: 8rem; max-width: 90vw;"
        onclick="$(this).hide('slow');"
      >${text}</div>
    `);
    $(`#${notify_id}`).show('slow');

    setTimeout(() => { $(`#${notify_id}`).hide('slow'); }, timeout);
  }
  $(function(){
    $('body').prepend(`
      <div class="d-flex flex-column justify-content-end" style="
        position: fixed;
        top: 0;
        right: 0;
        padding: 1rem;
        z-index: 9999;
      " id="container-alerts"></div>
    `);
  });
</script>