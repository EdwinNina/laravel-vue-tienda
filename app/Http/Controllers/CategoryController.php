<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $categories = Category::orderBy('id','desc')->paginate(5);
        }else{
            $categories = Category::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         => $categories->total(),
                'current_page'  => $categories->currentPage(),
                'per_page'      => $categories->perPage(),
                'last_page'     => $categories->lastPage(),
                'from'          => $categories->firstItem(),
                'to'            => $categories->lastItem()
            ],
            'categories' => $categories
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Category::create($request->all());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Category::findOrFail($request->id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'condicion' => '1',
        ]);
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Category::find($request->id)->update(['condicion' => '1']);
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Category::find($request->id)->update(['condicion' => '0']);
    }

    public function seleccionarCategoria(Request $request)
    {   
        if(!$request->ajax()) return redirect('/');
        $categorias = Category::where('condicion','=','1')
                            ->select('id','nombre')
                            ->orderBy('nombre','asc')
                            ->get();
        
        return $categorias;
    }
}
