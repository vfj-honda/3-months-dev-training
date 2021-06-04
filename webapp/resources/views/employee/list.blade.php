@extends('adminlte::page')

@section('title', '')

@section('content_header')
    <h1>社員リスト</h1>
@stop

@section('content')
    <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th> 名前 </th>
            <th> e-mail </th>
            <th> chatwork_id </th>
            <th> 編集 </th>
          </tr>
        </thead>
        <tbody>
        @foreach ($employee as $e)
          <tr>
            <td>{{ $e->name }}</td>
            <td>{{ $e->email }}</td>
            <td>{{ $e->chatwork_id }}</td>
            <td>
              <input type="button" value="編集" class="btn btn-secondary" onclick="location.href='{{ route("admin.employee.edit", $e->id) }}'">
              <input type="button" value="削除" class="btn btn-danger">
            </td>
          </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
   
@stop