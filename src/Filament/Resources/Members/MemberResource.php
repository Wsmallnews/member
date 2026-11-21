<?php

namespace Wsmallnews\Member\Filament\Resources\Members;

use Wsmallnews\Member\Filament\Resources\Members\Pages\EditMember;
use Wsmallnews\Member\Filament\Resources\Members\Pages\ListMembers;
use Wsmallnews\Support\Filament\Concerns\CanBeConfigured;

final class MemberResource extends BaseResource
{
    use CanBeConfigured;

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'edit' => EditMember::route('/{record}'),
        ];
    }
}
