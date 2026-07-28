<?php

namespace Wsmallnews\Member;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wsmallnews\Member\Commands\MemberInstallCommand;
use Wsmallnews\Member\Http\Middleware\ResolveMember;
use Wsmallnews\Member\Listeners\AutoCreateMember;
use Wsmallnews\Member\Support\Utils;

class MemberServiceProvider extends PackageServiceProvider
{
    public static string $name = 'sn-member';

    public static string $viewNamespace = 'sn-member';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasConfigFile()
            ->hasMigrations($this->getMigrations())
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // 注册模型别名
        Relation::enforceMorphMap([
            'sn-member' => Utils::getMemberModel(),
        ]);

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/member/{$file->getFilename()}"),
                ], 'member-stubs');
            }
        }

        // 登录/注册时自动创建 Member
        Event::listen(Login::class, AutoCreateMember::class);
        Event::listen(Registered::class, AutoCreateMember::class);

        // 注册 Livewire 持久化中间件
        Livewire::addPersistentMiddleware([
            ResolveMember::class,
        ]);

        // 注册 livewire 命名空间
        Livewire::addNamespace(
            namespace: 'sn-member',
            classNamespace: 'Wsmallnews\\Member\\Livewire'
        );
    }

    protected function getAssetPackageName(): ?string
    {
        return 'wsmallnews/member';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('member', __DIR__ . '/../resources/dist/components/member.js'),
            // Css::make('member-styles', __DIR__ . '/../resources/dist/member.css'),
            // Js::make('member-scripts', __DIR__ . '/../resources/dist/member.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            MemberInstallCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_sn_members_table',
        ];
    }
}
