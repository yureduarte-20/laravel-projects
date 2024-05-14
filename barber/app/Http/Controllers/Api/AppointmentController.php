<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index(Request $request){
        $appCount = User::where('email', $request->user()->email)->with([
            'userable' => function (MorphTo $morphTo){
                $morphTo->withCount('appointment');
            }
        ])->first();
        return Response::json([
            $appCount
        ]);
    }
}
