<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;  //このコントローラで使うモデルを定義する


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    { 
        $user = \Auth::user();  //ログインしているユーザーの情報を$userに代入
        // find idしか検索できないかつ一件しか持ってこれない
        // find_by　id以外のカラムを検索できるかつ一件しか持ってこれない
        // where　条件が一致すれば複数取って来れる
        $memos = Memo::where('user_id' , $user['id'])->where('status' ,1)->orderby('updated_at','DESC')->get();  //自分が所有しているメモ,かつstatusが１のメモを取得する    //where->取ってくるデータの条件を指定できる   //orderby->並べ方を指定できる(ASC=昇順、DESC=降順)   
        return view('create',compact('user','memos'));   //conpactを使うことによって$userの情報をviewで使うことが出来る
    }                                                   

    public function create()
    {
        $user = \Auth::user();  //ログインしているユーザーの情報を$userに代入
        $memos = Memo::where('user_id' , $user['id'])->where('status' ,1)->orderby('updated_at','DESC')->get();  //自分が所有しているメモ,かつstatusが１のメモを取得する    //where->取ってくるデータの条件を指定できる   //orderby->並べ方を指定できる(ASC=昇順、DESC=降順)   
        return view('create',compact('user','memos'));   //conpactを使うことによって$userの情報をviewで使うことが出来る
    }

    public function store(Request $request) //Requestを使うと、フォームに入力されたメモの内容、ユーザーidをコントローラで受け取ることが出来る。
    {
        $data = $request->all(); //$dataの中に$request allでHTMLから投げられたデータを全て$dataに入れている
        //dd($data);    //dd->その$dataの中身を分解して画面で確認できる
        // POSTされたデータをDB（memosテーブル）に挿入
        // MEMOモデルにDBへ保存する命令を出す
        Memo::insertGetId([   //insert それぞれデータを定義して、データベースに挿入していっている
            'content' => $data['content'],
             'user_id' => $data['user_id'], 
             'status' => 1
        ]); 

        // リダイレクト処理->別のページへ遷移すること
        return redirect()->route('home');
    }

    public function edit($id) //$id->URLパラメータの事  
    {
        $user = \Auth::user();  //ログインしているユーザーの情報を$userに代入
        $memos = Memo::where('user_id' , $user['id'])->where('status' ,1)->orderby('updated_at','DESC')->get();  //自分が所有しているメモ,かつstatusが１のメモを取得する    //where->取ってくるデータの条件を指定できる   //orderby->並べ方を指定できる(ASC=昇順、DESC=降順)   
        $memo = Memo::where('status', 1)->where('id', $id)->where('user_id', $user['id'])  //statusが1かつ、memosテーブルのidがURLパラメータ（URLの数字）と同じものかつ、userのidが今ログインしているuserのidと一致すること
          ->first();    //first->条件が一致した物を一行だけ取ってくるメソッド
        //dd($memo);
        return view('edit',compact('user','memos','memo'));   //conpactを使うことによって$userの情報をviewで使うことが出来る
    }

    public function update(Request $request, $id)  //$id->editのURLパラメータと同じく$idと書くことによってどこの行を更新するか受け取ることができる
    {
        $inputs = $request->all();
        //dd($inputs);
        Memo::where('id',$id)->update(['content'=> $inputs['content']]);   //whereでどこをupdateするのか指定する。idはURLパラメータに入っているもの、そのあとupdateしたい内容を配列で指定。contentの内容を更新したいので$inputsのcontentの内容に更新します。という意味
        return redirect()->route('home');   // リダイレクト処理->別のページへ遷移すること
    }

    public function delete(Request $request, $id)
    {
        $inputs = $request->all();
        //dd($inputs);
        // 論理削除なので、status=2
        Memo::where('id', $id)->update([ 'status' => 2 ]);
        // // ↓は物理削除
        // // Memo::where('id', $id)->delete();
        //                                                     //フラッシュメッセージ
        return redirect()->route('home')->with('success', 'メモの削除が完了しました！');
        
    }
}
