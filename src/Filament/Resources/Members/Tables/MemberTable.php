<?php

namespace Wsmallnews\Member\Filament\Resources\Members\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Member\Models\Member;
use Wsmallnews\Support\Filament\Actions\ActionComponents;
use Wsmallnews\Support\Filament\Resources\ActivityLogs\Concerns\CauserTimelineAction;
use Wsmallnews\Support\Filament\Tables\ColumnComponents;
use Wsmallnews\User\Support\Utils as UserUtils;

class MemberTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                static::IDColumn(),
                static::userInfoColumn(),
                static::statusColumn(),
                static::createdAtColumn(),
                static::updatedAtColumn(),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('user'))
            ->searchPlaceholder(__('sn-member::member.member_resource.table.search_placeholder'))
            ->filtersFormWidth(Width::Medium)
            ->filters([
                static::statusFilter(),
            ])
            ->recordActions([
                ...ActionComponents::recordActions([
                    EditAction::make(),
                    CauserTimelineAction::make()->color('info'),
                    static::toggleStatusAction(),
                ]),
            ])
            ->toolbarActions([
                ...ActionComponents::toolbarActions([
                    static::bulkEnableAction(),
                    static::bulkDisableAction(),
                ]),
            ]);
    }

    // ========================= Columns =========================

    protected static function IDColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('id')
            ->label('ID')
            ->searchable()
            ->sortable()
            ->alignCenter()
            ->toggleable();
    }

    protected static function userInfoColumn(): Tables\Columns\TextColumn
    {
        return ColumnComponents::relationColumn(
            name: 'user.name',
            label: __('sn-member::member.member_resource.table.user'),
            modelResolver: fn ($record) => $record->user,
            relationTypeResolver: fn ($record) => UserUtils::getUserModel(),
            relationIdResolver: fn ($record) => $record->user_id,
        );
    }

    protected static function statusColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('status')
            ->label(__('sn-member::member.member_resource.table.status'))
            ->badge()
            ->toggleable();
    }

    protected static function createdAtColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('created_at')
            ->label(__('sn-member::member.member_resource.table.created_at'))
            ->sortable()
            ->toggleable();
    }

    protected static function updatedAtColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('updated_at')
            ->label(__('sn-member::member.member_resource.table.updated_at'))
            ->sortable()
            ->toggleable();
    }

    // ========================= Filters =========================

    protected static function statusFilter(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('status')
            ->label(__('sn-member::member.member_resource.filter.status'))
            ->options(MemberStatus::class);
    }

    // ========================= Actions =========================

    protected static function toggleStatusAction(): Action
    {
        return ActionComponents::toggleAction(MemberStatus::class, 'status');
    }

    // ========================= Bulk Actions =========================

    protected static function bulkEnableAction(): BulkAction
    {
        return ActionComponents::bulkAction(
            name: 'bulk_enable',
            process: function (BulkAction $action, Member $record): void {
                if ($record->status === MemberStatus::Normal) {
                    $action->reportBulkProcessingFailure();

                    return;
                }

                $record->update(['status' => MemberStatus::Normal]);
            }
        )
            ->label(__('sn-member::member.member_resource.action.bulk_enable'))
            ->icon(MemberStatus::Normal->getIcon())
            ->color(MemberStatus::Normal->getColor())
            ->modalHeading(__('sn-member::member.member_resource.action.bulk_enable'))
            ->modalDescription(__('sn-member::member.member_resource.action.bulk_enable_description'));
    }

    protected static function bulkDisableAction(): BulkAction
    {
        return ActionComponents::bulkAction(
            name: 'bulk_disable',
            process: function (BulkAction $action, Member $record): void {
                if ($record->status === MemberStatus::Disabled) {
                    $action->reportBulkProcessingFailure();

                    return;
                }

                $record->update(['status' => MemberStatus::Disabled]);
            }
        )
            ->label(__('sn-member::member.member_resource.action.bulk_disable'))
            ->icon(MemberStatus::Disabled->getIcon())
            ->color(MemberStatus::Disabled->getColor())
            ->modalHeading(__('sn-member::member.member_resource.action.bulk_disable'))
            ->modalDescription(__('sn-member::member.member_resource.action.bulk_disable_description'));
    }
}
