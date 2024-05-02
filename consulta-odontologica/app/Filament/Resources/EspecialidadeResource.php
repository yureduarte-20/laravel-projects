<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EspecialidadeResource\Pages;
use App\Models\Especialidade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class EspecialidadeResource extends Resource
{
    protected static ?string $model = Especialidade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required(),
                Forms\Components\Textarea::make('descricao')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tempo_medio_consulta_minutos')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempo_medio_consulta_minutos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(fn (Especialidade $e) => $e->has('users')->exists() ?
                        Notification::make()
                            ->title('Não é possível apagar especialidade com usuários cadastrados!')
                            ->danger()
                            ->send()
                        : $e->delete()),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $record) {
                            if (! auth()->user()->is_admin()) {
                                return Notification::make()
                                    ->danger()->title('Você não possui autorização para isso')->send();
                            }
                            if ($record->some(fn (Especialidade $e) => $e->has('users')->exists())) {
                                return Notification::make()
                                    ->danger()->title('Não é possível apagar especialidade com usuários cadastrados! ')->send();
                            }

                            return $record->each->delete();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEspecialidades::route('/'),
        ];
    }
}
