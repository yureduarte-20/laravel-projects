<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\RentalStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RentalController extends Controller
{
    public function index()
    {
        $status = \Illuminate\Support\Facades\Request::query('status');
        $condition = \Illuminate\Support\Facades\Request::query('condition');
        if (!empty($status)) {
            try {
                $status = RentalStatus::from($status);
            } catch (\ValueError $e) {
                return response()->json(['message' => 'Status inválido'], 400);
            }
        }

        return Inertia::render('Rental/Index', [
            'rentals' => Rental::when($status, function (Builder $query, RentalStatus $status) {
                $query->where('status', $status->value);
            })->paginate(20)->withQueryString()
        ]);
    }
}
