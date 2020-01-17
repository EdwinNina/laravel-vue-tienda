<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
            $productos = Product::with('category')->orderBy('id','desc')->paginate(5);
        }else{
            $productos = Product::with('category')->where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         => $productos->total(),
                'current_page'  => $productos->currentPage(),
                'per_page'      => $productos->perPage(),
                'last_page'     => $productos->lastPage(),
                'from'          => $productos->firstItem(),
                'to'            => $productos->lastItem()
            ],
            'productos' => $productos
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
        Product::create($request->all());
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
        Product::findOrFail($request->id)->update([
            'category_id' => $request->category_id,
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'descripcion' => $request->descripcion,
            'condicion' => '1',
        ]);
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Product::find($request->id)->update(['condicion' => '1']);
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Product::find($request->id)->update(['condicion' => '0']);
    }
}
