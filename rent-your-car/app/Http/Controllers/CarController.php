<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CarController extends Controller
{
    protected $rules = [
        'plate' => 'required|unique:cars,plate',
        'model' => 'required|string',
        'brand' => 'required|string',
        'year' => 'required|numeric',
        'price' => 'required|array|required_array_keys:price_per_day,late_price_per_day',
        'is_available' => 'required|bool'
    ];

    protected $rulesMessages = [
        'plate' => [
            'unique' => 'Essa placa já está cadastrada no sistema'
        ],
        'model' => [
            'required' => 'Campo obrigatório',

        ],
        'brand' => [
            'required' => 'Campo obrigatório',

        ],
        'price' => [
            'required' => 'Campo obrigatório',
        ],
    ];

    public function __constructor()
    {
        $this->authorizeResource(Car::class, 'car');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Car/Index', [
            'cars' => Car::paginate()->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Car/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules, $this->rulesMessages);
        try {
            DB::beginTransaction();
            $car = Car::newModelInstance($validated);
            $price = Price::newModelInstance($validated['price']);
            $car->save();
            $price->car()->associate($car);
            $price->save();
            DB::commit();
            return Redirect::route('admin.car.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return Redirect::route('admin.car.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $car->price = $car->price()->first();
        return Inertia::render('Car/Edit', [
            'car' => $car
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $rules = array_map(fn($key, $value) => $key == 'plate' ?  'required|exists:cars,plate' :
             $value, array_keys($this->rules), array_values($this->rules));
        $rules = array_combine(array_keys($this->rules), $rules);
        $validated = $request->validate($rules, $this->rulesMessages);
        try {
            DB::beginTransaction();
            $car->update($validated);
            $car->price()->update($validated['price']);
            DB::commit();
            return Redirect::route('admin.car.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return Redirect::route('admin.car.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }
}
