<?php

namespace App\Http\Controllers;

use App\Livro;
use Illuminate\Http\Request;
use Validator;

use File;


class LivroController extends Controller
{ //aqui não dois anderlines no construct
    //essa função obriga a entrar só logado
    public function __construct()
    {

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $livros = Livro::all();
        return view('livros.index', compact('livros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('livros.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validacao = Validator::make($request->all(),[ 
            'titulo' => 'required',
            'autor' => 'required',
            'edicao' => 'required',
            'isbn' => 'nullable|numeric',
        ]);

        if ($validacao->fails())
        {
            return redirect('livros/create')
            ->withErrors($validacao,'livro')
            ->withInput();
        }
        
        if ($request->file('imagem'))
        {
            $imagem = $request->file('imagem');
            $extensao = $imagem->getClientOriginalExtension();
            if($extensao != 'jpg' && $extensao != 'png' && $extensao != 'jpeg')
            {
              return back()->with('erro','Erro: Este arquivo não é imagem');
          }
      }

      $livro = new Livro;
      $livro->titulo = $request->titulo;
      $livro->autor = $request->autor;
      $livro->edicao = $request->edicao;
      $livro->isbn = $request->isbn;
      $livro->imagem = "";
      $livro->save();
      if ($request->file('imagem'))
      {
         File::move($imagem,public_path().'/imagem-post/livro-id_'.$livro->id.'.'.$extensao);
         $livro->imagem = '/imagem-post/livro-id_'.$livro->id.'.'.$extensao;
         $livro->save();
     }
     $request->session()->flash('alert-success','Livro adicionado com sucesso!');
     return redirect()->route('livros.index');
     
 }

    /**
     * Display the specified resource.
     *
     * @param  \App\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function show(Livro $livro)
    {
        return view('livros.show', compact('livro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function edit(Livro $livro)
    {
        $action = action('LivroController@update', $livro->id);
        return view('livros.edit', compact('livro', "action"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Livro $livro)
    {
        $validacao = Validator::make($request->all(),
            [
                'titulo' => 'required',
                'autor' => 'required',
                'edicao' => 'required',
                'isbn' => 'nullable|numeric',
            ])->validate();

        $livro->titulo = $request->titulo;
        $livro->autor = $request->autor;
        $livro->edicao = $request->edicao;
        $livro->isbn = $request->isbn;

        $livro->save();

        $request->session()->flash('alert-success', 'Livro alterado com sucesso!');
        return redirect(route('livros.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Livro $livro)
    {
        $livro->delete();
        $request->session()->flash('alert-success', 'livro apagado com sucesso!');
        return redirect()->back();
    }
}