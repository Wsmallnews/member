<?php

namespace Wsmallnews\Member\Filament\Resources\Members\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Member\Filament\Resources\Members\MemberResource;
use Wsmallnews\Member\Support\Utils;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label(__('sn-member::member.member_resource.tabs.all'))
                ->badge(fn () => $this->getCount()),
            'normal' => Tab::make()
                ->label(MemberStatus::Normal->getLabel())
                ->badge(fn () => $this->getCount(MemberStatus::Normal))
                ->modifyQueryUsing(fn ($query) => $query->where('status', MemberStatus::Normal)),
            'disabled' => Tab::make()
                ->label(MemberStatus::Disabled->getLabel())
                ->badge(fn () => $this->getCount(MemberStatus::Disabled))
                ->modifyQueryUsing(fn ($query) => $query->where('status', MemberStatus::Disabled)),
        ];
    }

    protected function getCount(?MemberStatus $status = null): int
    {
        $query = Utils::getMemberModel()::query()->scopeTenant();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }
}
