<ul
  class="pagination pagination-primary mt-2 mb-4 justify-content-center"
  style="gap: .6rem;"
>
  @foreach($pagination as $index => $page)
    <li
      class="to-page-contents page-item {{ $index == 0 ? 'active': 'disabled' }}"
      id="{{ $page->id }}"
    >
      <a
        class="page-link"
        href="javascript:;"
        onclick="{{ $page->onclick }}"
      >{{ $index + 1 }}</a>
    </li>
  @endforeach
</ul>