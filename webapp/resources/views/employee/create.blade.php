@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>アカウント作成</h1>
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

    <form action="{{ route('admin.employee.store') }}" method="post">
    @csrf
        <table>
            <tbody>

            <div class="form-group">
            <tr>
                <th>名前</th>
                <td><input type="text" name="name" id="name"></td>
            </tr>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
            <tr>
                <th>E-mail</th>
                <td><input type="email" name="email" id="email"></td>
            </tr>
            </div>

            <div class="form-group">
            <tr>
                <th>ChatworkID</th>
                <td><input type="text" name="chatwork_id" id="chatwork_id"></td>
            </tr>
            </div>

            </tbody>
        </table>

        <input type="submit" value="送信">
    </form>




@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop