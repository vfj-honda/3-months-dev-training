@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>管理者権限の設定</h1>
@stop

@section('content')
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


<form action="{{ route('admin.employee.authority.elevate') }}" method="post">
    @method('PUT')
    @csrf

    <select name="elevate_user_id" id="elevate_user_id">
    @foreach ($not_auth_employee as $e)
    <option value="{{ $e->id }}">{{ $e->name }}</option>
    @endforeach
    </select>
    を管理者にする

    <input type="submit" value="決定">
</form>

<form action="{{ route('admin.employee.authority.diselevate') }}" method="post">
    @method('PUT')
    @csrf

    <select name="diselevate_user_id" id="diselevate_user_id">
    @foreach ($auth_employee as $e)
    <option value="{{ $e->id }}">{{ $e->name }}</option>
    @endforeach
    </select>
    をユーザーに戻す

    <input type="submit" value="決定">
</form>




@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop