@extends('layouts.app')

@section('content')
    <h4>新規メモ作成</h4>
    <form method = 'POST' action = "/store">
        @csrf    <!--csrfトークン　フォームに必ず入れるユーザー乗っ取りセキュリティ対策-->
        <input type = 'hidden' name='user_id' value = "{{ $user['id'] }}">   <!--hiddenを着けることでユーザーが見えずにデータを渡すことが出来る-->
        <textarea name='content' rows = "10"></textarea>
        <button type = 'submit'>保存</button>
    </form>
@endsection
