<?php

namespace App\Providers\Filament;

use App\Filament\Resources\DashboardResource\Widgets\KendaraanSummary;
use App\Filament\Resources\DashboardResource\Widgets\PengajuanPerBulanChart;
use App\Filament\Resources\DashboardResource\Widgets\TopAkunBprChart;
use App\Filament\Resources\DashboardResource\Widgets\TransaksiUmkSummary;
use App\Filament\Resources\DashboardResource\Widgets\UmkSummary;
use App\Models\TransaksiUMK;
use Dflydev\DotAccessData\Data;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->databaseNotifications()
            ->spa()
            ->login()
            ->passwordResetRoutePrefix('password-reset')
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->brandName('Sistem UMK Dan Inventaris')
            ->brandLogo(asset('image/logo.png'))
            ->favicon(asset('image/dp.png'))
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                UmkSummary::class,
                KendaraanSummary::class,
                TransaksiUmkSummary::class,
                TopAkunBprChart::class,
                PengajuanPerBulanChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('9rem')
            ->userMenuItems([
           'profile' => MenuItem::make()->label('Edit profile'),
            'logout' => MenuItem::make()->label('Log out'),
        ])
            ->navigationGroups([

                NavigationGroup::make()
                    ->label('Pengadaan Barang'),
                //->icon('heroicon-o-archive-box-arrow-down'),
                //heroicon-o-archive-box-arrow-down

                NavigationGroup::make()
                    //->label('Pengajuan Uang Muka'),
                    //->icon('heroicon-o-cash'),
                    ->label(fn(): string => __('navigation.Pengajuan Uang Muka'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Inventaris Kendaraan'),
                //->icon('heroicon-o-cash'),

                NavigationGroup::make()
                    ->label('Settings')
                    //->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])

            ->resources([
                config('filament-logger.activity_resource')
            ]);
    }
}
