<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BarberController extends Controller
{
    protected $rules = [
        'email' => 'unique:users,email|required|string',
        'password' => 'string|min:8',
        'confirmPassword'=>'string|min:8|same:password',
        'phone' => 'nullable',
        'name' => 'string|min:3',
        'salary' => 'numeric|min:0'
    ];
    protected $rulesMessage = [
        'email' => [
            'unique' => 'Este email já está sendo utilizado.'
        ],
        'confirmPassword' => [
            'same' => 'A senha e a confirmação de senha devem corresponder'
        ],
        'name' => [
            'min' => 'Seu nome deve ter no mínimo 3 caracteres'
        ]
    ];
    public function __construct(
        protected NotificationService $notificationService
    )
    {
        $this->authorizeResource(Barber::class, 'barber');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barbers = Barber::with('user')->get()
            ->map(fn(Barber $barber) => [...$barber->only(['salary', 'id']), ...$barber->user->only(['email', 'name', 'phone'])]);

        return Inertia::render('Admin/Barbers/List', [ 'barbers' => $barbers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Barbers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate($this->rules, $this->rulesMessage);
        try{
            DB::beginTransaction();
            $barber = Barber::newModelInstance();
            $barber->fill($validated);
            if($barber->save()){
                $barber->user()->save(new User([...$validated, 'password' => Hash::make($validated['password'])]));
                $this->notificationService->writeSuccessNotification('Barbeiro criado com sucesso');
                DB::commit();
                return Redirect::to(route('barber.index'));
            }
            DB::rollBack();
            $this->notificationService->writeDangerNotification('Erro ao tentar criar o barbeiro.');
            return Redirect::to(route('barber.index'));
        } catch (\Exception $e){
            DB::rollBack();
            $this->notificationService->writeDangerNotification('Erro ao tentar criar o barbeiro.');
            return Redirect::to(route('barber.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barber $barber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barber $barber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barber $barber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barber $barber)
    {
        //
    }
}
