<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RolesEnum;
use App\Filament\Resources\UserResource;
use App\Models\Agenda;
use App\Models\Horario;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create($data);
        if(Role::findById($data['roles'])->name === RolesEnum::ODONTOLOGO->name ){
            $agenda =  $user->agenda()->create();
            $horarios = [];
            foreach($data['horarios'] as $data_horario)
            {
                $horarios[] = Horario::where('horario', $data_horario['horario'])
                ->where('dia_semana', $data_horario['dia_semana'])->first()->id;
            }
            $agenda->horarios()->sync($horarios);
        }
        return $user;
    }
}
