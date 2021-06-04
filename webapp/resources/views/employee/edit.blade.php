@extends('adminlte::page')

@section('title', '')

@section('content_header')
    <h1>社員情報編集</h1>
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
    <form action="/employee/update/{{ $employee->id }}" method="post">
    @method('PUT')
    @csrf
      <div class="form-group">
        <label for="name">名前</label>
        <input type="text" name="name" id="name" value="{{ $employee->name }}">
      </div>
      <div class="form-group">
        <label for="email">e-mail</label>
        <input type="email" name="email" id="email" value="{{ $employee->email }}">
      </div>
      <div class="form-group">
        <label for="chatwork_id">chatwork_id</label>
        <input type="text" name="chatwork_id" id="chatwork_id" value="{{ $employee->chatwork_id }}">
      </div>

      <input type="submit" value="更新" class="btn btn-primary">
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
   
@stop