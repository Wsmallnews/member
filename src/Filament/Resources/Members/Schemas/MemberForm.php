<?php

namespace Wsmallnews\Member\Filament\Resources\Members\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Support\Filament\Forms\FormComponents;
use Wsmallnews\User\Enums\Gender;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...static::forms(),
            ]);
    }

    public static function forms(): array
    {
        return [
            Schemas\Components\Section::make()->schema([
                // 提示信息
                Schemas\Components\Callout::make()
                    ->description(__('sn-member::member.member_resource.form.user_sync_tip'))
                    ->warning()
                    ->columnSpanFull(),

                FormComponents::plainImageUpload('avatar_url')
                    ->label(__('sn-user::user.settings.profile.avatar'))
                    ->avatar()
                    ->columnSpanFull(),

                // 不可编辑的 User 字段（disabled）
                Forms\Components\TextInput::make('username')
                    ->label(__('sn-user::user.user_resource.table.username'))
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('email')
                    ->label(__('sn-user::user.user_resource.table.email'))
                    ->disabled()
                    ->dehydrated(false),
                // Forms\Components\TextInput::make('mobile')
                //     ->label(__('sn-user::user.user_resource.table.mobile'))
                //     ->disabled()
                //     ->dehydrated(false),

                // 可编辑的 User 字段
                Forms\Components\TextInput::make('name')
                    ->label(__('sn-user::user.user_resource.table.name'))
                    ->required()
                    ->maxLength(10),
                Forms\Components\ToggleButtons::make('gender')
                    ->label(__('sn-user::user.settings.profile.gender'))
                    ->options(Gender::class)
                    ->default(Gender::Undisclosed)
                    ->required()->grouped(),
                Forms\Components\DatePicker::make('birthday')
                    ->label(__('sn-user::user.settings.profile.birthday'))
                    ->format('Y-m-d')
                    ->displayFormat('Y-m-d'),
                Forms\Components\ToggleButtons::make('status')
                    ->label(__('sn-member::member.member_resource.table.status'))
                    ->options(MemberStatus::class)
                    ->default(MemberStatus::Normal)
                    ->required()->grouped(),
            ])
                ->columns(2)
                ->columnSpanFull(),
        ];
    }
}
