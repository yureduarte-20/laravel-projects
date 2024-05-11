<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RolesEnum;
use App\Filament\Resources\UserResource;
use App\Models\Horario;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = User::find($data["id"]);
        if ($user->has('agenda')->exists()) {
            $data['horarios'] = [];
            foreach ($user->agenda->horarios as $horario) {
                array_push(
                    $data['horarios'],
                    [
                        'dia_semana' => $horario->dia_semana->name,
                        'horario' => $horario->horario,
                    ]
                );
            }
        }

        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if($record->has('agenda')->exists() ){
            $agenda =  $record->agenda;
            $horarios = [];
            foreach($data['horarios'] as $data_horario)
            {
                $horarios[] = Horario::where('horario', $data_horario['horario'])
                ->where('dia_semana', $data_horario['dia_semana'])->first()->id;
            }
            $agenda->horarios()->sync($horarios);
        }
        return $record;
    }
}
