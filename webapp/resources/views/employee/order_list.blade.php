@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>順番リスト</h1>
@stop

@section('content')
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
<form action="{{ route('admin.employee.order_list.switch') }}" method="post">
@method('PUT')
@csrf

<div class="form-group">

  <select name="switch_1" id="switch_1" class="form-controll">
    @foreach ($orders as $order)
      <option value="{{ $order->order_number }}"> {{ $order->name }} </option>
    @endforeach
  </select>

と

  <select name="switch_2" id="switch_2" class="form-controll">
    @foreach ($orders as $order)
      <option value="{{ $order->order_number }}"> {{ $order->name }} </option>
    @endforeach
  </select>

を入れ替える

  <input type="submit" value="決定" class="btn btn-primary">
</div>

</form>

<form action="{{ route('admin.employee.order_list.insert') }}" method="post">
@method('PUT')
@csrf

<div class="form-group">

  <select name="insert_1" id="insert_1" class="form-controll">
    @foreach ($orders as $order)
      <option value="{{ $order->order_number }}"> {{ $order->name }} </option>
    @endforeach
  </select>

を

  <select name="insert_2" id="insert_2" class="form-controll">
    @foreach ($orders as $order)
      <option value="{{ $order->order_number }}"> {{ $order->name }} </option>
    @endforeach
  </select>

の後ろに入れる

  <input type="submit" value="決定" class="btn btn-primary">
</div>

</form>


@foreach ($orders as $order)

{{ $order->order_number }}
{{ $order->name }}

@endforeach

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop