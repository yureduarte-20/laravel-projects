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
            $data['horarios'] = $user->agenda->horarios->pluck('id')->toArray();
        }
        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        if($record->has('agenda')->exists() or Role::whereIn('id', $data['roles'])->get()->some(fn (Role $role) => $role->name == RolesEnum::ODONTOLOGO ) ){
             $agenda = $record->agenda()->firstOrCreate();
             $agenda->horarios()->sync($data['horarios']);
        }
        $record->fill($data)->save();
        $record->roles()->sync($data['roles']);
        return $record;
    }
}
