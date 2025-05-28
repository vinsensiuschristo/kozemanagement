<?php

namespace App\Filament\Resources\JenisKamarResource\Pages;

use App\Filament\Resources\JenisKamarResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ViewField;

class ViewJenisKamar extends ViewRecord
{
    protected static string $resource = JenisKamarResource::class;
    protected static string $view = 'filament.resources.jenis-kamar.pages.view-jenis-kamar';
}
