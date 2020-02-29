<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Storage;

class PostControlador extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('index', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $caminho = $request->file('arquivo')->store('imagens', 'public');

        $post = new Post();
        $post->email = $request->input('email');
        $post->mensagem = $request->input('mensagem');
        $post->arquivo = $caminho;
        $post->save();
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (isset($post)) {
            $arquivo = $post->arquivo;
            if ($arquivo != '')
                Storage::disk('public')->delete($arquivo);
        
            $post->delete();
        }

        return redirect('/');
    }

    /**
     * Fazer o download de um arquivo.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id) {
        $post = Post::find($id);
        if (isset($post)) {
            // Pega o caminho absoluto do arquivo.
            $arquivo = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($post->arquivo);

            // Faz o donwload do arquivo.
            return response()->download($arquivo);
        }

        return redirect('/');
    }
}
