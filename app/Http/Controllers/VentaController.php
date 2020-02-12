<?php

namespace App\Http\Controllers;

use App\Venta;
use Carbon\Carbon;
use App\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $ventas = Venta::join('people','people.id','=','ventas.cliente_id')
                ->join('users','users.id','=','ventas.user_id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'people.nombre','users.name')
                ->orderBy('ventas.id','desc')->paginate(3);
        }else{
            $ventas = Venta::join('people','people.id','=','ventas.cliente_id')
            ->join('users','users.id','=','ventas.user_id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                    'people.nombre','users.name')
                ->where('ventas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('ventas.id','desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total'         => $ventas->total(),
                'current_page'  => $ventas->currentPage(),
                'per_page'      => $ventas->perPage(),
                'last_page'     => $ventas->lastPage(),
                'from'          => $ventas->firstItem(),
                'to'            => $ventas->lastItem()
            ],
            'ventas' => $ventas
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try {

            DB::beginTransaction();
            $mytime = Carbon::now('America/La_Paz');

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::user()->id,
                'tipo_comprobante' => $request->tipo_comprobante,
                'serie_comprobante' => $request->serie_comprobante,
                'num_comprobante' => $request->num_comprobante,
                'fecha_hora' => $mytime->toDateString(),
                'impuesto' => $request->impuesto,
                'total' => $request->total,
                'estado' => 'registrado'
            ]);

            $detalles = $request->data; //array de detalles

            foreach ($detalles as $key => $det) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'product_id' => $det['product_id'],
                    'cantidad' => $det['cantidad'],
                    'precio' => $det['precio'],
                    'descuento' => $det['descuento'],
                ]);                
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }    
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Venta::where('id',$request->id)->update(['estado' => 'anulado']);
    }

    public function obtenerVenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;

        $venta = Venta::join('people','people.id','=','ventas.cliente_id')
                ->join('users','users.id','=','ventas.user_id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante','ventas.num_comprobante',
                        'ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado',
                        'people.nombre','users.name')
                ->where('ventas.id', '=', $id)
                ->take(1)
                ->get();
        
        return $venta;
    }

    public function obtenerDetalleVenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;

        $detalles = DetalleVenta::join('products','products.id','=','detalle_ventas.product_id')
                ->select('detalle_ventas.precio','detalle_ventas.cantidad','detalle_ventas.descuento','products.nombre')
                ->where('detalle_ventas.venta_id', '=', $id)
                ->get();
        
        return $detalles;
    }

}