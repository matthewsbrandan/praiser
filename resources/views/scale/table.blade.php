<table
  id="container-table" style="display: none;"
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
      <tr @class(['tr-highlight' => $row->weekday == 'sunday'])>
        <td> 
          <div class="d-flex align-items-center">
            <span class="p-1 pe-2 text-lg font-weight-bold">{{ $row->day }}</span>
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