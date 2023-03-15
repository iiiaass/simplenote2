@extends('layouts.app')

@section('content')
    <h4>メモ編集画面</h4>
    <form method = 'POST' action="/delete/{{$memo['id']}}">
        @csrf    <!--csrfトークン　フォームに必ず入れるユーザー乗っ取りセキュリティ対策-->
        <button type = 'submit'>削除</button>
    </form>
    <form method = 'POST' action = "/update/{{ $memo['id'] }}">
        @csrf    <!--csrfトークン　フォームに必ず入れるユーザー乗っ取りセキュリティ対策-->
        <input type = 'hidden' name='user_id' value = "{{ $user['id'] }}">   <!--hiddenを着けることでユーザーが見えずにデータを渡すことが出来る-->
        <textarea name='content' rows = "10">{{ $memo['content'] }}</textarea>
        <button type = 'submit'>更新</button>
    </form>
@endsection
