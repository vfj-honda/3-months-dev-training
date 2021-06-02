@extends('layouts.main')

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

@endsection
