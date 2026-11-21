<?php

namespace Wsmallnews\Member\Filament\Resources\Members;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Wsmallnews\Member\Filament\Resources\Members\Schemas\MemberForm;
use Wsmallnews\Member\Filament\Resources\Members\Tables\MemberTable;
use Wsmallnews\Member\Support\Utils;
use Wsmallnews\Support\Filament\Concerns\RequiresConfiguration;
use Wsmallnews\Support\Filament\Resources\ResourceConfiguration;

abstract class BaseResource extends Resource
{
    use RequiresConfiguration;

    protected static ?string $configurationClass = ResourceConfiguration::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::UserGroup;

    protected static ?string $slug = 'members';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getModel(): string
    {
        return Utils::getMemberModel();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('sn-member::member.member_resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return static::$pluralModelLabel ?? __('sn-member::member.member_resource.plural_model_label');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? __('sn-member::member.member_resource.navigation_label');
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return static::$navigationGroup ?? __('sn-member::member.global_default.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return MemberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MemberTable::configure($table);
    }

    /**
     * Members are tenant-scoped by team_id only (no scopeable pair). Filament's
     * tenancy global scope is a no-op when the panel has no tenant, so a
     * tenant-less panel would list every tenant's members; scopeTenant() pins
     * it to team_id = null there and to the current tenant otherwise.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->scopeTenant();
    }
}
