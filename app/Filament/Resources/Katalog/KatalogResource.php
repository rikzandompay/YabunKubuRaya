<?php

namespace App\Filament\Resources\Katalog;

use App\Filament\Resources\Katalog\KatalogResource\Pages;
use App\Models\Katalog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KatalogResource extends Resource
{
    protected static ?string $model = Katalog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Program & Media';

    protected static ?string $label = 'Katalog Program';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('program_id')
                    ->relationship('program', 'nama_program')
                    ->required()
                    ->label('Program Induk'),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(100)
                    ->label('Judul Katalog'),
                Forms\Components\FileUpload::make('gambar')
                    ->image()
                    ->directory('katalog')
                    ->label('Gambar Katalog'),
                Forms\Components\TextInput::make('target_penerima')
                    ->maxLength(50)
                    ->label('Target Penerima Manfaat'),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull()
                    ->label('Deskripsi Lengkap'),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('aktif')
                    ->required()
                    ->label('Status Keaktifan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->square(),
                Tables\Columns\TextColumn::make('program.nama_program')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Katalog')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_penerima')
                    ->label('Target Penerima'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'gray',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters::SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKatalogs::route('/'),
            'create' => Pages\CreateKatalog::route('/create'),
            'edit' => Pages\EditKatalog::route('/{record}/edit'),
        ];
    }
}
