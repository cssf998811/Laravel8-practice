<?php

namespace App\Http\Controllers;
use App\Models\Article;
use Illuminate\Http\Request;


class ArticlesController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except('index');
    }

    public function index() {
        // $articles = Article::all();
        // $articles = Article::orderBy('id', 'desc')->get();
        $articles = Article::paginate(5);
        return view('articles.index', ['articles' => $articles]);
    }

    public function create() {
        return view('articles.create');
    }

    public function store(Request $request) {
        $content = $request->validate([
            'title' => 'required',
            'content' => 'required|min:10'
        ]);

        // dd(auth()->user());
        auth()->user()->articles()->create($content);
        return redirect()->route('root')->with('notice', '文章新增成功!');
    }

    public function edit($id) {
        // 不認主人，誰都可以改文章內容
        // $article = Article::find($id);

        // 透過登入的使用者找符合他id的文章
        $article = auth()->user()->articles->find($id);
        return view('articles.edit', ['article' => $article]);
    }

    public function update(Request $request, $id) {
        $article = auth()->user()->articles->find($id);

        $content = $request->validate([
            'title' => 'required',
            'content' => 'required|min:10'
        ]);

        $article->update($content);
        return redirect()->route('root')->with('notice', '文章更新成功');

    }
}
