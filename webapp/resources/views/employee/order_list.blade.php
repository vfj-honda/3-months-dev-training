@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>順番リスト</h1>
@stop

@section('content')

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