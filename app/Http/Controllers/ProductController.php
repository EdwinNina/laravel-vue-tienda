<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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

    public function listarProductos(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $productos = Product::with('category')->orderBy('id','desc')->paginate(5);
        }else{
            $productos = Product::with('category')->where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(5);
        }
        return $productos;
    }

    public function listarProductosVenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $productos = Product::with('category')->where('stock','>','0')->orderBy('id','desc')->paginate(5);
        }else{
            $productos = Product::with('category')->where('stock','>','0')->where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(5);
        }
        return $productos;
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Product::create($request->all());
    }

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

    public function buscarProducto(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $producto = Product::where('codigo', '=', $filtro)
                    ->select('id','nombre')
                    ->orderBy('nombre', 'asc')
                    ->take(1)
                    ->get();
        
        return $producto;
    }
    public function buscarProductoVenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $producto = Product::where('codigo', '=', $filtro)
                    ->select('id','nombre','stock','precio_venta')
                    ->where('stock','>',0)
                    ->orderBy('nombre', 'asc')
                    ->take(1)
                    ->get();
        
        return $producto;
    }
}
