<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;

use App\RentalStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RentalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!empty (\Illuminate\Support\Facades\Request::query('status')))
            try {
                $status = RentalStatus::from(\Illuminate\Support\Facades\Request::query('status'));
                return response()->json(Rental::with('car')
                    ->where('user_id', '=', $user->id)
                    ->where('status', $status->value)->get()
                );
            } catch (\ValueError $e) {
                return response()->json(['message' => 'Status inválido'], 400);
            }
        foreach ($user->rentals as $rental) {
            $rental->car;
        }
        return response()->json($user->rentals);
    }

    private function diffDays($start, $end): int
    {
        return Carbon::parse($end)->diffInDays(Carbon::parse($start));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|numeric|exists:cars,id',
            'expected_rental' => 'required|date|after:' . Carbon::now(),
            'expected_return' => 'required|date|after:expected_rental',
        ]);
        $car = Car::with(['price', 'rentals' => function ($query) {
            $query->whereNull('returned_at')
                ->whereIn('status', [RentalStatus::RESERVED->value, RentalStatus::IN_PROGRESS->value, RentalStatus::LATE->value])
                ->exists();
        }])->findOrFail($validated['car_id']);
        if (!$car->is_available) return response()->json(['message' => 'Carro indisponível'], 422);
        if (count($car->rentals) > 0) return response()->json(['message' => 'Carro reservado'], 422);
        $validated['estimated_cost'] = ($this->diffDays($validated['expected_rental'], $validated['expected_return']) == 0 ? 1 : $this->diffDays($validated['expected_rental'], $validated['expected_return'])) * $car->price->price_per_day;
        $rental = new Rental;
        $rental->status = RentalStatus::RESERVED;
        $car->is_available = false;
        $rental->fill($validated);
        $rental->car()->associate($car);
        $rental->user()->associate($request->user());
        $rental->save();
        $car->save();
        return response()->json($rental, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(Rental::with('car')->where('user_id', '=', Auth::user()->id)->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $rentalId)
    {
        $validated = $request->validate([
            'expected_rental' => 'required|date|after:' . Carbon::now(),
            'expected_return' => 'required|date|after:expected_rental',
        ]);
        $rental = Rental::with(['car', 'car.price'])->where('user_id', '=', Auth::user()->id)->findOrFail($rentalId);
        if ($rental != RentalStatus::RESERVED) return response()->json(['message' => 'você não pode alterar sem estar no estado reservado'], 422);
        $validated['estimated_cost'] = ($this->diffDays($validated['expected_rental'], $validated['expected_return']) == 0 ? 1 : $this->diffDays($validated['expected_rental'], $validated['expected_return'])) * $rental->car->price->price_per_day;
        $rental->fill($validated);
        $rental->save();
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel($id)
    {
        $rental = Rental::where('user_id', '=', Auth::user()->id)->findOrFail($id);
        if ($rental->status != RentalStatus::RESERVED) return response()->json(['message' => 'Você não pode cancelar um empréstimo sem estar "reservado"'], 422);
        if (!empty($rental->rental_date) && empty($rental->returned_at)) return response()->json(['message' => 'Você não pode cancelar um empréstimo sem ter devolvido o carro'], 422);
        Rental::where('user_id', '=', Auth::user()->id)->where('id', '=', $id)->update(
            ['status' => RentalStatus::CANCELED->value]
        );
        return response()->noContent();
    }

}
