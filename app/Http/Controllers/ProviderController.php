<?php

namespace App\Http\Controllers;

use App\Person;
use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public function index(Request $request)
    {

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $proveedores = Provider::join('people','people.id','=','providers.person_id')
                ->select('people.id','people.nombre','people.tipo_documento','people.num_documento','people.direccion','people.telefono','people.email',
                        'providers.contacto','providers.telefono_contacto')
                ->orderBy('people.id','desc')->paginate(5);
        }else{
            $proveedores = Provider::join('people','people.id','=','providers.person_id')
                ->select('people.id','people.nombre','people.tipo_documento','people.num_documento','people.direccion','people.telefono','people.email',
                    'providers.contacto','providers.telefono_contacto')
                ->where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         => $proveedores->total(),
                'current_page'  => $proveedores->currentPage(),
                'per_page'      => $proveedores->perPage(),
                'last_page'     => $proveedores->lastPage(),
                'from'          => $proveedores->firstItem(),
                'to'            => $proveedores->lastItem()
            ],
            'proveedores' => $proveedores
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try {

            DB::beginTransaction();

            $persona = Person::create([
                'nombre' => $request->nombre,
                'tipo_documento' => $request->tipo_documento,
                'num_documento' => $request->num_documento,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email
            ]);

            Provider::create([
                'person_id' => $persona->id,
                'contacto' => $request->contacto,
                'telefono_contacto' => $request->telefono_contacto,
            ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }

    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();
               
            Person::findOrFail($request->id)->update([
                'nombre' => $request->nombre,
                'tipo_documento' => $request->tipo_documento,
                'num_documento' => $request->num_documento,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email
            ]);

            Provider::where('person_id',$request->id)->update([
                'contacto' => $request->contacto,
                'telefono_contacto' => $request->telefono_contacto,
            ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }

    }

    public function seleccionarProveedores(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $proveedores = Provider::join('people','people.id','=','providers.person_id')
                            ->where('people.nombre','like','%' . $filtro . '%')
                            ->orWhere('people.num_documento','like','%' . $filtro . '%')
                            ->select('people.id','people.nombre','people.num_documento')
                            ->orderBy('people.nombre','asc')->get();
        return $proveedores;
    }
}
