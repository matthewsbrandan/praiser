<table
  id="container-table"
  class="table table-hover align-items-center mb-0"
>
  <thead>
    <tr>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Data/Tema</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ministro</th>
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
        @class(['tr-highlight' => $row->weekday == 'sunday'])
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