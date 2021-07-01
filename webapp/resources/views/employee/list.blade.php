@extends('adminlte::page')

@section('title', '')

@section('content_header')
    <h1>社員リスト</h1>
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
              <span><button class="btn btn-danger" id="btn-delete{{ $e->id }}" value="{{ $e->name }}" data-id="{{ $e->id }}" data-operator="{{ Auth::user()->id }}">削除</button></span>
              @csrf
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
<script>

$('[id^=btn-delete]').click(function(){

  let confirm_message = '「' + $(this).val() + '」を削除してよろしいですか。\n※この操作は取り消せません。' + $(this).data('id')

  if (confirm(confirm_message)) {

    let data = {
      'user_id': $(this).data('id'),
      'operator': $(this).data('operator'),
      '_method': "DELETE"
    }
    
    $.ajax({
      type: 'POST',
      url: '/api/admin/employee/destroy',
      data: data,
      dataType: 'json',
    })
    .done((res) => {
      alert(res.success_text)
      location.reload()
    })
    .fail((error) => {
      alert('社員の削除に失敗しました。')
    })
  }

})

</script>
@stop