<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function mount(): void
    {
        redirect()->to('/admin/dashboard')->send();
        exit;
    }
}
