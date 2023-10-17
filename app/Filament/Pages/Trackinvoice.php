<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Trackinvoice extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Trackinvoice Status';
    public static ?string $label = 'Track Invoice Status';
    public static ?string $title = 'Track Invoice Status';
    protected static string $view = 'filament.pages.trackinvoice';
}
