<?php

namespace App\Http\Controllers;

use App\Models\BarberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class BarberServiceController extends Controller
{
    protected $rules = [
        'price' => 'numeric',
        'name' => 'string|min:3',
        'description' => 'string'
    ];
    public function __construct()
    {
       $this->authorizeResource(BarberService::class, 'barberService');

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Inertia::render('Admin/Services/List', [
            'services' => BarberService::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Admin/Services/Create', [

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $service = BarberService::newModelInstance();
        $service->fill($validated);
        //$this->resetflash();
        if($service->save()){
            session()->flash('notification',  ['type' => 'success' , 'content' => 'Serviço criado com sucesso!']);
            return Redirect::to(route('barberService.index'));
        }

        session()->flash('notification', ['type' => 'error', 'content' => 'Erro ao tentar criar o serviço']);
        return Redirect::to(route('barberService.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BarberService $barberService)
    {

        return Inertia::render('Admin/Services/Show', [
           'service' => $barberService
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarberService $barberService)
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => $barberService
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarberService $barberService)
    {
        $validated = $request->validate($this->rules);
        $barberService->fill($validated);

        if($barberService->save()){
            session()->flash('notification',  [ 'type' => 'success', 'content' =>  'Serviço editado com sucesso!']);
            return Redirect::to(route('barberService.index'));
        }
        session()->flash('notification',  [ 'type' => 'error', 'content' =>  'Erro ao tentar criar o serviço']);
        return Redirect::to(route('barberService.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarberService $barberService)
    {
        BarberService::destroy($barberService->id);
        Session::flash('notification', ['type' => 'success', 'content' => 'Serviço deletado com sucesso!' ]);
        return Redirect::to(route('barberService.index'));
    }
}
