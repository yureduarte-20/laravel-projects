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
use Spatie\Permission\Models\Role;

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
                    ->relationship('roles', 'name'),
                Forms\Components\Select::make('especialidades')
                    ->relationship('especialidades', 'nome')
                    ->multiple()
                    ->rules([
                        function ($get) {
                            return function (string $atribute, $value, \Closure $fail) use ($get) {
                                if ($get('roles') == Role::findByName(RolesEnum::ODONTOLOGO->name)->id && count($value) <= 0) {
                                    $fail('Para odontólogos é obrigatório adicionar suas especialidades');
                                }
                            };
                        },
                    ]),

                Forms\Components\Repeater::
                    make('horarios')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                \Filament\Forms\Components\Select::make('dia_semana')
                                    ->label('Dia da Semana')
                                    ->options(array_map(fn($item) => [$item->name => $item->name], Semanas::cases())),
                                Forms\Components\Select::make('horario')
                                ->label('Horário')
                                ->options(Horario::selectRaw('DISTINCT horario')->get()->map(fn(Horario $h) => [
                                    $h->horario => $h->horario
                                ]))
                            ])
                    ])
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
