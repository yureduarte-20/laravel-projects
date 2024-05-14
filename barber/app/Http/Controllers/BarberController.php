<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BarberController extends Controller
{
    protected $rules = [
        'email' => 'unique:users,email|required|string',
        'password' => 'string|min:8',
        'confirmPassword' => 'string|min:8|same:password',
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

        return Inertia::render('Admin/Barbers/List', ['barbers' => $barbers]);
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
        try {
            DB::beginTransaction();
            $barber = Barber::newModelInstance();
            $barber->fill($validated);
            if ($barber->save()) {
                $barber->user()->save(new User([...$validated, 'password' => Hash::make($validated['password'])]));
                $this->notificationService->writeSuccessNotification('Barbeiro criado com sucesso');
                DB::commit();
                return Redirect::to(route('barber.index'));
            }
            DB::rollBack();
            $this->notificationService->writeDangerNotification('Erro ao tentar criar o barbeiro.');
            return Redirect::to(route('barber.index'));
        } catch (\Exception $e) {
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
        return Inertia::render('Admin/Barbers/Show', [
            'barber' => [...$barber->toArray(),
                ...Collection::make($barber->user)->only(['name', 'email', 'role']),
                'appointments' => $barber->appointment()->count()]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barber $barber)
    {
        return Inertia::render('Admin/Barbers/Edit', ['barber' => [
            ...$barber->toArray(),
            ...Collection::make($barber->user)->only(['name', 'email', 'role']),]]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'password' => 'nullable|min:8',
            'confirmPassword' => 'nullable|min:8|same:password',
            'name' => 'min:3|required',
            'salary' => 'numeric|min:0|required'
        ]);
        $barber->fill($validated);
        $user = $barber->user;

        $user->fill([
            'name' => $validated['name'],
            'salary' => $validated['salary']]);
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        if ($barber->save()) {
            $user->save();
            $this->notificationService->writeSuccessNotification('Barbeiro editado com sucesso');
        } else {
            $this->notificationService->writeDangerNotification('Erro ao tentar salver o barbeiro.');
        }
        return Redirect::to(route('barber.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barber $barber)
    {
        $barber->delete();
        return Redirect::to(route('barber.index'));
    }
}
