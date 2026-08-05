<?php

namespace Wsmallnews\Member\Filament\Resources\Members;

use Wsmallnews\Member\Filament\Resources\Members\Pages\EditMember;
use Wsmallnews\Member\Filament\Resources\Members\Pages\ListMembers;
use Wsmallnews\Member\MemberPlugin;
use Wsmallnews\Support\Filament\Concerns\CanBeConfigured;
use Wsmallnews\Support\Filament\Resources\ResourceConfiguration;

final class MemberResource extends BaseResource
{
    use CanBeConfigured;

    protected static ?string $configurationClass = ResourceConfiguration::class;

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'edit' => EditMember::route('/{record}'),
        ];
    }

    public static function getEssentialsPlugin(): ?MemberPlugin
    {
        return MemberPlugin::get();
    }
}
