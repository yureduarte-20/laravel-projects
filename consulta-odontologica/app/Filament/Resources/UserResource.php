<?php

namespace App\Filament\Resources;

use App\Enum\RolesEnum;
use App\Enum\Semanas;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Agenda;
use App\Models\Horario;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Symfony\Component\Mime\Part\Multipart\MixedPart;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(),
                Forms\Components\Select::make('roles')
                    ->required()
                    ->label('Papel no sistema')
                    ->native(false)
                    ->relationship('roles', 'name'),
                Forms\Components\Select::make('especialidades')
                    ->relationship('especialidades', 'nome')
                    ->multiple()
                    ->native(false)
                    ->rules([
                        function ($get) {
                            return function (string $atribute, $value, \Closure $fail) use ($get) {
                                if ($get('roles') == Role::findByName(RolesEnum::ODONTOLOGO->name)->id && count($value) <= 0) {
                                    $fail('Para odontólogos é obrigatório adicionar suas especialidades');
                                }
                            };
                        },
                    ]),
                Forms\Components\Select::make('horarios')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->rules([
                        function ($get) {
                            return function (string $atribute, $value, \Closure $fail) use ($get) {
                                if ($get('roles') == Role::findByName(RolesEnum::ODONTOLOGO->name)->id && count($value) <= 0) {
                                    $fail('Para odontólogos é obrigatório adicionar suas especialidades');
                                }
                            };
                        },
                    ])
                    ->options(Horario::all([ 'id', 'dia_semana', 'horario' ])
                        ->pluck('label_option', 'id')
                        ->toArray())
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Papel no sistema'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
