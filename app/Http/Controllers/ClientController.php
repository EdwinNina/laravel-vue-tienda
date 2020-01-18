<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $clientes = Person::orderBy('id','desc')->paginate(5);
        }else{
            $clientes = Person::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         => $clientes->total(),
                'current_page'  => $clientes->currentPage(),
                'per_page'      => $clientes->perPage(),
                'last_page'     => $clientes->lastPage(),
                'from'          => $clientes->firstItem(),
                'to'            => $clientes->lastItem()
            ],
            'clientes' => $clientes
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Person::create($request->all());
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        Person::findOrFail($request->id)->update($request->all());
    }
}
