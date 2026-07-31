<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Tables;
// Note: avoid strict Form/Table type-hints for Filament compatibility
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Products';
    protected static ?int $navigationSort = 2;

    public static function form($form)
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->reactive()
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                TextInput::make('slug')->required()->unique(ignoreRecord: true),

                Textarea::make('description')->rows(4),

                TextInput::make('price')->numeric()->required()->minValue(0),

                TextInput::make('stock')->numeric()->required()->minValue(0),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),

                FileUpload::make('image')->image()->directory('products')->preserveFilenames(),
            ]);
    }

    public static function table($table)
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('')->rounded()->size(48),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category')->sortable(),
                TextColumn::make('price')->money('usd')->sortable(),
                TextColumn::make('stock')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->relationship('category','name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
