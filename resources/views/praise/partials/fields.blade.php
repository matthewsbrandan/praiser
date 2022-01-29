<div class="row">
  <div class="col-md-6">
    <div>
      <label>Nome</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Nome"
          aria-label="Nome"
          type="text"
          name="name"
          id="praise-name"
          required
        />
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div>
      <label>Cantor/Banda</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Cantor/Banda"
          aria-label="Cantor/Banda"
          type="text"
          name="singer"
          id="praise-singer"
          required
        />
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div>
      <label>Link do Youtube (opcional)</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Link do Youtube"
          aria-label="Link do Youtube"
          type="text"
          name="youtube"
          id="praise-youtube"
        />
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div>
      <label>Link da Cifra (opcional)</label>
      <div class="input-group mb-3">
        <input
          class="form-control"
          placeholder="Cifra"
          aria-label="Cifra"
          type="text"
          name="cipher"
          id="praise-cipher"
        />
      </div>
    </div>
  </div>
</div>
<hr/>
<h6 class="text-center text-sm mb-3">TAGS</h6>
<div class="d-flex flex-wrap justify-content-around">
  @foreach(\App\Models\Praise::getAvailableTags() as $tag)
    <button
      type="button"
      class="btn btn-sm bg-gradient-light mx-2 text-xs"
      onclick="toggleTags($(this),'{{ $tag }}')"
      style="width: 12rem"
    >#{{ $tag }}</button>
  @endforeach
  <input type="hidden" name="tags" id="input-tags"/>
</div>
<script>
  function toggleTags(elem, tag){
    elem.toggleClass('bg-gradient-light bg-gradient-primary');

    let tags = $('#input-tags').val() ? $('#input-tags').val().split(',') : [];
    if(tags.includes(tag)) tags = tags.filter(
      item => item != tag
    );
    else tags.push(tag);
    $('#input-tags').val(tags.join(','));
  }
</script>