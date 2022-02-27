<table
  id="container-table"
  class="table table-hover align-items-center mb-0"
>
  <thead>
    <tr>
      <th
        class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2"
        style="position: relative;"
      >
        <span
          class="opacity-7"
          onclick="$(this).next().toggle('slow');"
        >Data/Tema</span>
        <div class="card card-body p-3 border" style="
          position: absolute;
          display: none;
        ">
          <label>Filtrar</label>
          <div class="input-group mb-3">
            <input
              class="form-control"
              placeholder="Data ou tema"
              aria-label="Data ou tema"
              type="text"
              id="filter-table-by-date-theme"
              onkeyup="filterTable('date-theme',$(this).parent().parent().prev())"
            />
          </div>
        </div>
      </th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">
        <span
          class="opacity-7"
          onclick="$(this).next().toggle('slow');"
        >@include('utils.icons.search',['icon' => (object)[
          'width' => '18px',
          'height' => '18px'
        ]])</span>
        <div class="card card-body p-3 border" style="
          position: absolute;
          display: none;
        ">
          <label>Filtrar</label>
          <div class="input-group mb-3">
            <input
              class="form-control"
              placeholder="Integrante"
              aria-label="Integrante"
              type="text"
              id="filter-table-by-integrant"
              onkeyup="filterTable('integrant',$(this).parent().parent().prev())"
            />
          </div>
        </div>
        <span class="opacity-7">Ministro</span>
      </th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vozes</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Violão</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Baixo</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guitarra</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Teclado</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bateria/Cajon</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Datashow</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mesário</th>
    </tr>
  </thead>
  <tbody>
    @foreach($table as $row)
      <tr 
        data-date="{{ $row->day . $row->month }}"
        data-weekday="{{ $row->weekday_name }}"
        data-theme="{{ $row->theme }}"
        data-integrants="{{
          implode(', ', array_map(function($res){
            return implode(', ', $res['users']);
          },$row->resume))
        }}"
        @class(['tr-highlight' => $row->weekday == 'sunday','row-table'])
        onclick="callModalScaled({{ $row->toJson() }})"
      >
        <td> 
          <div class="d-flex align-items-center">
            @if($row->is_current_month)
              <span class="p-1 pe-2 text-lg font-weight-bold">{{ $row->day }}</span>
            @else
              <span
                class="p-1 pe-2 font-weight-bold d-flex flex-column text-center"
              >
                <span class="border-bottom">{{ $row->day }}</span>
                <span>{{ $row->month }}</span>
              </span>
            @endif
            <div class="text-sm">
              <strong class="d-block text-dark text-uppercase">
                {{ $row->weekday_name }}
              </strong>
              <span>{{ $row->theme }}</span>
            </div>
          </div>
          </td>
        <td class="text-sm">{{ $row->resume_table['ministro'] }}</td>
        <td class="text-sm">{{ $row->resume_table['backvocal'] }}</td>
        <td class="text-sm">{{ $row->resume_table['violao'] }}</td>
        <td class="text-sm">{{ $row->resume_table['baixo'] }}</td>
        <td class="text-sm">{{ $row->resume_table['guitarra'] }}</td>
        <td class="text-sm">{{ $row->resume_table['teclado'] }}</td>
        <td class="text-sm">
          {{ $row->resume_table['bateria'] != '-' ? $row->resume_table['bateria'] : $row->resume_table['cajon'] }}
        </td>
        <td class="text-sm">{{ $row->resume_table['datashow'] }}</td>
        <td class="text-sm">{{ $row->resume_table['mesario'] }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
<script>
  function filterTable(type, target){
    let search = $(type === 'date-theme' ? 
      '#filter-table-by-date-theme' :
      '#filter-table-by-integrant'
    ).val();
    
    if(search.length > 0) target.addClass('text-dark').removeClass('opacity-7');
    else target.addClass('opacity-7').removeClass('text-dark');

    $('.row-table').each(function(){
      if((
        $(this).attr('data-date').includes($('#filter-table-by-date-theme').val()) ||
        $(this).attr('data-weekday').includes($('#filter-table-by-date-theme').val()) ||
        $(this).attr('data-theme').includes($('#filter-table-by-date-theme').val())
      ) && (
        $(this).attr('data-integrants').includes($('#filter-table-by-integrant').val())
      )) $(this).show();
      else $(this).hide();
    });
  }
</script>