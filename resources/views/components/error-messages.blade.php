<div style="color:red;">
  <ul>
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @if ($loop->iteration >= 2)
        @break
      @endif
    @endforeach
    @if ($has2MoreErrors)
      ...以下略
    @endif
  </ul>
</div>