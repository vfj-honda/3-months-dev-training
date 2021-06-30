@extends('adminlte::page')

@section('title', '')

@section('content_header')
    <h1>通知編集</h1>
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
    <form action="{{ route('admin.notification.update') }}" method="post" onsubmit="return false;">
    @method('PUT')
    @csrf

      <div class="form-group">
        <label for="chatwork_flag">Chatworkで通知する</label>
        <input type="checkbox" name="chatwork_flag" id="chatwork_flag" {{ $notification->chatwork_flag==1 ? 'checked' : ''}}>
      </div>
      <div class="form-group">
        <label for="chatwork_text">文面 (chatwork)</label>
        <span><button class="btn btn-secondary" id="btn-preview">preview</button></span>
        @csrf
        <label for=""> $today:当日の投稿者, $next:次営業日の投稿者, $afternext:次々営業日の投稿者 </label>
        <textarea name="chatwork_text" id="chatwork_text" class="form-control input-lg" rows="7">
        {{ $notification->chatwork_text }}
        </textarea>
      </div>
      <div class="form-group">
        <label for="mail_flag">E-mailで通知する</label>
        <input type="checkbox" name="mail_flag" id="mail_flag" {{ $notification->mail_flag==1 ? 'checked' : ''}}>
      </div>
      <div class="form-group">
        <label for="mail_text">文面 (E-mail)</label>
        <textarea type="textarea" name="mail_text" id="mail_text" class="form-control" rows="7">
        {{ $notification->mail_text }}
        </textarea>
      </div>
      
      <input type="button" onclick="submit();" value="更新" class="btn btn-primary">
    </form>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
   <script>

$('#btn-preview').click(function(){

    let data = {
      'chatwork_text': $('#chatwork_text').val()
     }

    $.ajax({
      type: 'post',
      url: '/api/admin/notification/preview',
      data: data,
      dataType: 'json',
    })
    .done((res) => {
      alert(res.converted_notification_text)
    })
    .fail((error) => {
      alert('失敗しました')
    })
  })
   </script>
@stop