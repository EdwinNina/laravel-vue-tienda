<?php

namespace App\Http\Controllers;

use App\User;
use App\Ingreso;
use Carbon\Carbon;
use App\DetalleIngreso;
use Illuminate\Http\Request;
use App\Notifications\NotifyAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $ingresos = Ingreso::join('people','people.id','=','ingresos.provider_id')
                ->join('users','users.id','=','ingresos.user_id')
                ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante',
                        'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado',
                        'people.nombre','users.name')
                ->orderBy('ingresos.id','desc')->paginate(3);
        }else{
            $ingresos = Ingreso::join('people','people.id','=','ingresos.provider_id')
            ->join('users','users.id','=','ingresos.user_id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante',
                    'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado',
                    'people.nombre','users.name')
                ->where('ingresos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('ingresos.id','desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total'         => $ingresos->total(),
                'current_page'  => $ingresos->currentPage(),
                'per_page'      => $ingresos->perPage(),
                'last_page'     => $ingresos->lastPage(),
                'from'          => $ingresos->firstItem(),
                'to'            => $ingresos->lastItem()
            ],
            'ingresos' => $ingresos
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try {

            DB::beginTransaction();
            $mytime = Carbon::now('America/La_Paz');

            $ingreso = Ingreso::create([
                'provider_id' => $request->provider_id,
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
                DetalleIngreso::create([
                    'ingreso_id' => $ingreso->id,
                    'product_id' => $det['product_id'],
                    'cantidad' => $det['cantidad'],
                    'precio' => $det['precio'],
                ]);                
            }

            $fechaActual = date('Y-m-d');
            $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count();
            $numIngresos = DB::table('ingresos')->whereDate('created_at', $fechaActual)->count();
            
            $arregloDatos = [
                'ventas' => [
                    'numero' => $numVentas,
                    'msj' => 'Ventas'
                ],
                'ingresos' => [
                    'numero' => $numIngresos,
                    'msj' => 'Ingresos'
                ]
            ];

            $allUsers = User::all();

            foreach ($allUsers as $notificar) {
                User::find($notificar->id)->notify(new NotifyAdmin($arregloDatos));
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }    
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Ingreso::where('id',$request->id)->update(['estado' => 'anulado']);
    }

    public function obtenerIngreso(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;

        $ingreso = Ingreso::join('people','people.id','=','ingresos.provider_id')
                ->join('users','users.id','=','ingresos.user_id')
                ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante',
                        'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado',
                        'people.nombre','users.name')
                ->where('ingresos.id', '=', $id)
                ->take(1)
                ->get();
        
        return $ingreso;
    }

    public function obtenerDetalleIngreso(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;

        $detalles = DetalleIngreso::join('products','products.id','=','detalle_ingresos.product_id')
                ->select('detalle_ingresos.precio','detalle_ingresos.cantidad','products.nombre')
                ->where('detalle_ingresos.ingreso_id', '=', $id)
                ->get();
        
        return $detalles;
    }
}
