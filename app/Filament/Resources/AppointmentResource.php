<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays(30);

        $weekends = [];

        while ($startDate->lte($endDate)) {
            if ($startDate->isSaturday() || $startDate->isSunday()) {
                 $weekends[] = $startDate->toDateString();
            }
            $startDate->addDay();
        }

        return $form
            ->schema([
                Forms\Components\Select::make('medic_id')
                ->label('Medic')
                ->options(User::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),
                Forms\Components\Select::make('patient_id')
                ->label('Patient')
                ->options(User::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),
                Forms\Components\TextInput::make('subject')->required(),
                Forms\Components\TextInput::make('observations'),
                Forms\Components\DatePicker::make('date')->displayFormat('dd/MM/yyyy')
                ->minDate(now())
                ->maxDate(now()->addDays(30))
                ->disabledDates($weekends)
                ->required(),
                Forms\Components\TimePicker::make('time')
                ->minutesStep(30)
                ->withoutSeconds()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
