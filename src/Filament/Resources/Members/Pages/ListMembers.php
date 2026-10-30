<?php

namespace Wsmallnews\Member\Filament\Resources\Members\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Member\Filament\Resources\Members\MemberResource;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make()
                ->label(__('sn-member::member.member_resource.tabs.all'))
                ->badge(fn (): int => $this->getCount()),
        ];

        foreach (MemberStatus::cases() as $status) {
            $tabs[$status->value] = Tab::make()
                ->label($status->getLabel())
                ->badge(fn (): int => $this->getCount($status))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', $status));
        }

        return $tabs;
    }

    protected function getCount(?MemberStatus $status = null): int
    {
        $query = static::getResource()::getEloquentQuery();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }
}
