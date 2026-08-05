<?php

namespace Wsmallnews\Member\Filament\Resources\Members;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Wsmallnews\Member\Filament\Resources\Members\Schemas\MemberForm;
use Wsmallnews\Member\Filament\Resources\Members\Tables\MemberTable;
use Wsmallnews\Member\Support\Utils;

abstract class BaseResource extends Resource
{
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
}
