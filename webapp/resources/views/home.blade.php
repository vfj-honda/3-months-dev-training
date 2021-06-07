@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>カレンダー</h1>
    <form action="{{ route('user.home', [$currentYear, $currentMonth-1]) }}" method="get">
    <input type="submit" value="前月へ" class='btn btn-secondary'></form>
    <form action="{{ route('user.home', [$currentYear, $currentMonth+1]) }}" method="get">
    <input type="submit" value="次月へ" class='btn btn-secondary'></form>
    <!-- <form action="{{ route('user.root') }}" method="get">
    <input type="submit" value="今月" class='btn btn-secondary'></form> -->
@stop

@section('content')
 
<table class="table table-bordered">
  <thead>
    <tr>
      @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
      <th>{{ $dayOfWeek }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($dates as $date)
    @if (is_array($date))
    
    @if ($date["date"]->dayOfWeek == 0)
    <tr>
    @endif
      <td
        @if ($date["date"]->month != $currentMonth)
        class="bg-secondary"
        @endif
      >
        {{ $date['date']->day }}
        {{ $date['user']->name }}
      </td>
    @if ($date['date']->dayOfWeek == 6)
    </tr>
    @endif

    @else
    @if ($date->dayOfWeek == 0)
    <tr>
    @endif
      <td
        @if ($date->month != $currentMonth)
        class="bg-secondary"
        @endif
      >
        {{ $date->day }}
      </td>
    @if ($date->dayOfWeek == 6)
    </tr>
    @endif

    @endif
    @endforeach
  </tbody>
</table>

@if (isset($skips))
<h1>Skipの登録</h1>

<form action="{{ route('admin.skip.create') }}" method="post">
@csrf
  <div class="form-group">
    <input type="date" name="create_skip_day" id="create_skip_day">

は投稿日から除く

    <input type="submit" value="登録" class="btn btn-primary">
  </div>
</form>

<h1>Skipの削除</h1>

<form action="{{ route('admin.skip.destroy') }}" method="post">
@method('DELETE')
@csrf
  <div class="form-group">
    <select name="delete_skip_id" id="delete_skip_id">
      @foreach ($skips as $skip)
        <option value="{{ $skip->id }}"> {{ substr($skip->skip_day, 0, 10) }} </option>
      @endforeach
    </select>

を投稿日にする

    <input type="submit" value="更新" class="btn btn-primary">
  </div>
</form>

@endif

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

