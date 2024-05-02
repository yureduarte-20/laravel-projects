<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_agendas(): void
    {
        $this->seed();
        $user = User::first();
        $this->assertNotNull($user);
        $agenda = Agenda::newModelInstance(['user_id' => $user->id]);
        $agenda->user()->associate($user);
        $agenda->save();
        $this->assertEquals($user->agenda->id, $agenda->id);
    }

    public function test_agendas_disponibilidade(): void
    {
        $this->seed();
        $this->assertDatabaseHas('users', [
            'email' => 'yure@gmail.com',
        ]);
        $user = User::where([
            'email' => 'yure@gmail.com',
        ])->first();
        $agenda = Agenda::newModelInstance(['user_id' => $user->id]);
        $agenda->user()->associate($user);
        $agenda->save();
        $this->assertEquals($user->agenda->id, $agenda->id);

    }
}
