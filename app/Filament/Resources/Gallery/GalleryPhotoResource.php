<?php

namespace App\Filament\Resources\Gallery;

use App\Filament\Resources\Gallery\GalleryPhotoResource\Pages;
use App\Models\GalleryPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryPhotoResource extends Resource
{
    protected static ?string $model = GalleryPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'Publikasi & Media';

    protected static ?string $navigationLabel = 'Kegiatan Foto';

    protected static ?string $modelLabel = 'Foto Kegiatan';

    protected static ?string $pluralModelLabel = 'Galeri Foto Kegiatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Foto / Kegiatan')
                    ->required()
                    ->maxLength(150),

                Forms\Components\Select::make('category')
                    ->label('Kategori Kegiatan')
                    ->options([
                        'jumat_berkah' => 'Jumat Berkah',
                        'donasi' => 'Donasi',
                        'dzikir' => 'Dzikir',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('activity_date')
                    ->label('Tanggal Kegiatan')
                    ->default(now())
                    ->required(),

                Forms\Components\FileUpload::make('image_path')
                    ->label('File Foto')
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->maxSize(5120)
                    ->required(fn (string $operation): bool => $operation === 'create'),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Tampilkan sebagai foto unggulan di beranda')
                    ->default(false),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Nomor Urutan Tampil')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'jumat_berkah' => 'Jumat Berkah',
                        'donasi' => 'Donasi',
                        'dzikir' => 'Dzikir',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'jumat_berkah',
                        'info' => 'donasi',
                        'warning' => 'dzikir',
                    ]),

                Tables\Columns\TextColumn::make('activity_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'jumat_berkah' => 'Jumat Berkah',
                        'donasi' => 'Donasi',
                        'dzikir' => 'Dzikir',
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
            'index' => Pages\ListGalleryPhotos::route('/'),
            'create' => Pages\CreateGalleryPhoto::route('/create'),
            'edit' => Pages\EditGalleryPhoto::route('/{record}/edit'),
        ];
    }
}
