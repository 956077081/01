<?php
namespace App\Http\Controllers\Defaul;
use App\Article;
use App\Http\Controllers\Controller;
class HomeController extends Controller
{
    public function  index(){
        $articles = Article::OrderBy("created_at")->take(8)->get();
        $user = auth()->user()->name;
        return view('admin/default/index')->with("articles",$articles);
    }
}