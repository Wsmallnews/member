<?php

namespace Wsmallnews\Member\Support;

use Wsmallnews\Member\Exceptions\MemberException;

/**
 * Utility class for Member package configuration and helpers.
 */
class Utils
{

    /**
     * Get configuration value.
     *
     * @param  string|null  $name  Configuration key (dot notation)
     * @param  mixed  $default  Default value if not found
     */
    public static function getConfig(?string $name = null, mixed $default = null): mixed
    {
        $config = config('sn-member');

        return $name ? (data_get($config, $name) ?? $default) : $config;
    }

    /**
     * Get panel register raw config.
     *
     * @param  string  $type  Register type (pages or resources)
     */
    public static function getPanelRegister(?string $type = 'pages'): mixed
    {
        if (blank($type)) {
            return self::getConfig('panel_register', null);
        }

        return self::getConfig("panel_register.$type", null);
    }

    /**
     * Get model class by name.
     *
     * @param  string  $name  Model name (e.g., 'post', 'navigation')
     * @param  bool  $shouldException  Whether to throw exception if not found
     *
     * @throws MemberException
     */
    public static function getModel(string $name, bool $shouldException = true): ?string
    {
        $model = self::getConfig('models')[$name] ?? null;

        if (blank($model) && $shouldException) {
            throw new MemberException("模型 {$name} 不存在");
        }

        return $model;
    }

    /**
     * Get Member model class.
     *
     * @return string Models\Member
     */
    public static function getMemberModel(): string
    {
        return static::getModel('member');
    }

    /**
     * Get file directory path with optional type and date.
     *
     * @param  string|null  $type  Directory type
     */
    public static function getFileDirectory(?string $type = null): string
    {
        return self::getConfig('file_directory', 'sn/member/') . ($type ? $type . '/' : '') . date('Ymd');
    }
}
