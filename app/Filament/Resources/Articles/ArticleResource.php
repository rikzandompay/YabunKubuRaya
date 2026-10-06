<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Program & Media';

    protected static ?string $label = 'Artikel Program';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('program_category_id')
                    ->relationship('programCategory', 'name')
                    ->label('Kategori Program'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul Artikel'),
                Forms\Components\FileUpload::make('header_image')
                    ->image()
                    ->directory('articles')
                    ->label('Gambar Header'),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Tanggal Terbit'),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publikasikan')
                    ->default(true),
                Forms\Components\Textarea::make('excerpt')
                    ->columnSpanFull()
                    ->label('Ringkasan / Excerpt'),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->label('Konten Artikel'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('header_image')
                    ->label('Header')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('programCategory.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Dipublikasi'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tgl Terbit'),
            ])
            ->filters([
                Tables\Filters::TernaryFilter::make('is_published')
                    ->label('Status Publikasi'),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
