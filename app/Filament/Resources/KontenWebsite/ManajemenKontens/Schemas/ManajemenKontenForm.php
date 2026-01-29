<?php

namespace App\Filament\Resources\KontenWebsite\ManajemenKontens\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Repeater;

class ManajemenKontenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(6)
            ->schema([

             Section::make('Konten Halaman')
                ->schema([
                    Builder::make('content')
                        ->label('Susunan Konten')
                        ->blocks([
                            Block::make('hero_banner')
                                ->schema([
                                    FileUpload::make('image')->image()->directory('pages'),
                                    TextInput::make('headline'),
                                    TextInput::make('sub_headline'),
                                ]),

                            Block::make('text_content')
                                ->schema([
                                    RichEditor::make('text')
                                        ->label('Konten Tulisan')
                                ]),

                            Block::make('feature_grid')
                                ->schema([
                                    Repeater::make('items')
                                        ->schema([
                                            FileUpload::make('icon')->image(),
                                            TextInput::make('title'),
                                            TextInput::make('description'),
                                        ])
                                ]),
                        ])
                        ->collapsible()
                        ->cloneable(),
                ])
                ->columnSpan(4),

            Section::make('Atribut Halaman')
                ->schema([
                    TextInput::make('title')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->required(),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Select::make('parent_id')
                        ->label('Induk Halaman (Parent)')
                        ->relationship('parent', 'title', fn($query, $record) => 
                            $record ? $query->where('id', '!=', $record->id) : $query
                        )
                        ->searchable()
                        ->placeholder('Pilih jika ini adalah sub-menu (Opsional)'),
                        
                    Toggle::make('is_published')->default(true)->label('Publish Halaman'),
                ])
                ->columns(1)
                ->columnSpan(2),

        ]);
    }
}
