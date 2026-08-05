<?php

namespace Wsmallnews\Member\Filament\Resources\Members\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Wsmallnews\Member\Filament\Resources\Members\MemberResource;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;


    /**
     * 从关联的 User 模型填充字段到表单。
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var \Wsmallnews\Member\Models\Member $record */
        $record = $this->getRecord();

        $data['username'] = $record->user?->username;
        $data['email'] = $record->user?->email;
        // $data['mobile'] = $record->user?->mobile;
        $data['name'] = $record->user?->name;
        $data['avatar_url'] = $record->user?->avatar_url;
        $data['gender'] = $record->user?->gender;
        $data['birthday'] = $record->user?->birthday;

        return $data;
    }

    /**
     * 保存前将 User 字段同步到关联的 User 模型，只保留 Member 自身字段。
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var \Wsmallnews\Member\Models\Member $record */
        $record = $this->getRecord();

        // 提取可编辑的 User 字段并同步到关联的 User
        $userFields = Arr::only($data, ['name', 'avatar_url', 'gender', 'birthday']);
        if (! empty($userFields) && $record->user) {
            $record->user->update($userFields);
        }

        // 只保留 Member 自身的字段
        return Arr::except($data, ['username', 'email', 'mobile', 'name', 'avatar_url', 'gender', 'birthday']);
    }
}
