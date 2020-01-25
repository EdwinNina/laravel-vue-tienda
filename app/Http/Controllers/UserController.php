<?php

namespace App\Http\Controllers;

use App\User;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $usuarios = User::join('people','people.id','=','users.person_id')
                ->join('roles','roles.id','=','users.role_id')
                ->select('people.nombre','people.tipo_documento','people.num_documento','people.direccion','people.telefono','people.email',
                         'users.id','users.person_id','users.name','users.condicion','users.role_id','roles.nombre as role')
                ->orderBy('people.id','desc')->paginate(5);
        }else{
            $usuarios = User::join('people','people.id','=','users.person_id')
                ->join('roles','roles.id','=','users.role_id')
                ->select('people.nombre','people.tipo_documento','people.num_documento','people.direccion','people.telefono','people.email',
                         'users.id','users.name','users.condicion','users.role_id','roles.nombre as role')
                ->where('people.' . $criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         => $usuarios->total(),
                'current_page'  => $usuarios->currentPage(),
                'per_page'      => $usuarios->perPage(),
                'last_page'     => $usuarios->lastPage(),
                'from'          => $usuarios->firstItem(),
                'to'            => $usuarios->lastItem()
            ],
            'usuarios' => $usuarios
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

            User::create([
                'person_id' => $persona->id,
                'role_id' => $request->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
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

            User::where('person_id',$request->id)->update([
                'role_id' => $request->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        User::where('id',$request->id)->update(['condicion' => '1']);
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        User::where('id',$request->id)->update(['condicion' => '0']);
    }
}
