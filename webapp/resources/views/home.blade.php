<?php

use App\Models\User;

$admin = auth()->user()->authority;
$username  = auth()->user()->name;
$users = User::all();
function nextMonth($currentYear, $currentMonth)
{
  $nextMonth = $currentMonth + 1 > 12 ? 1 : $currentMonth + 1;
  $nextYear  = $currentMonth + 1 > 12 ? $currentYear + 1 : $currentYear;
  return [$nextYear, $nextMonth];
}
function beforeMonth($currentYear, $currentMonth)
{
  $beforeMonth = $currentMonth - 1 == 0 ? 12 : $currentMonth - 1;
  $beforeYear  = $currentMonth - 1 == 0 ? $currentYear - 1 : $currentYear;
  return [$beforeYear, $beforeMonth];
}
?>

@extends($admin ? 'adminlte::page' : 'layouts.main')

@section('content')
    
    <div class="container">
    @if ($message = Session::get('success'))
      <div class="alert alert-success">
        <strong>{{ $message }}</strong>
      </div>
    @endif
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <div class="row">
    <label for="">
    <h2>{{ $currentYear . '年' . $currentMonth . '月' }}</h2>
    </label>
    @user
        <label for="">
          <form action="{{ route('logout') }}" method="post">
          @csrf
          <input type="submit" value="ログアウト" class='btn btn-secondary'></form>
        </label>

        <label for="">
          {{ $username }}
        </label>
    @enduser
    </div>
    <div class="row justify-content-between">
      <label for=""　class="col-2">
        <form action="{{ route('user.home', beforeMonth($currentYear, $currentMonth)) }}" method="get">
        <input type="submit" value="前月へ" class='btn btn-secondary'></form>
      </label>

      <label for=""　class="col-2">
        <form action="{{ route('user.home', nextMonth($currentYear, $currentMonth)) }}" method="get">
        <input type="submit" value="次月へ" class='btn btn-secondary'></form>
      </label>
      
    </div>

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

@admin

@if(isset($skips))
<h4>Skipの登録</h4>

<form action="{{ route('admin.skip.create') }}" method="post">
@csrf
  <div class="form-group">
    <input type="date" name="create_skip_day" id="create_skip_day">

は投稿日から除く

    <input type="submit" value="登録" class="btn btn-primary">
  </div>
</form>

<h4>Skipの削除</h4>

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



<h4>祝日ファイル(csv)を取り込む</h4>
  <form action="{{ route('admin.skips.csv_import') }}" method="post" enctype='multipart/form-data'>
  @csrf
    <div class="form-group">
      <input type="file" name="csv_file" id="csv_file">
      <input type="submit" value="送信">
    </div>
  </form>



<h4>投稿者を指定</h4>

<form action="{{ route('admin.fixed_post_date.create') }}" method="post">
@csrf
  <div class="form-group">
  <label for="">
    投稿日:
    <input type="date" name="create_fixed_post_day" id="create_fixed_post_day">
  </label>
  <label for="">
    投稿者:
    <select name="user_id" id="user_id">
      @foreach ($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
      @endforeach
    </select>
    <input type="submit" value="登録" class="btn btn-primary">
  </label>
  </div>
</form>

@if(isset($fixedPostDates))
<h4>指定投稿日を削除</h4>
<form action="{{ route('admin.fixed_post_date.destroy') }}" method="post">
@method('DELETE')
@csrf
  <div class="form-group">
    指定投稿日:
    <select name="delete_fpd_id" id="delete_fpd_id">
      @foreach ($fixedPostDates as $fpd)
        <option value="{{ $fpd->id }}">{{ substr($fpd->fixed_post_day, 5, 5) . $fpd->name }}</option>
      @endforeach
    </select>
    <input type="submit" value="更新" class="btn btn-primary">
  </label>
  </div>
</form>
@endif
@endadmin
</div>
@endsection



