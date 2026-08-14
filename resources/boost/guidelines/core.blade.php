## Member 包（wsmallnews/member）

`wsmallnews/member` 是会员管理插件，将 User 模型包装为会员实体，支持多租户、活动日志和 preference 集成（点赞/关注/浏览）。命名空间根为 `Wsmallnews\Member`，Blade 视图前缀为 `sn-member`，配置文件为 `config/sn-member.php`。

### 核心架构

- **Member 模型**：继承 `SupportModel`，实现 `HasSnIdentifiable` 接口，通过 `user_id` 关联 User 模型
- **MemberResource**：Filament 资源，使用 `CanBeConfigured` 支持插件配置覆盖
- **ResolveMember 中间件**：自动从登录用户解析/创建 Member 记录，设置 request attributes

### Member 模型

`Wsmallnews\Member\Models\Member` 继承 `SupportModel`，实现 `HasSnIdentifiable`：

```php
use Wsmallnews\Member\Models\Member;

// 核心特性：
// - extends SupportModel（scopeTenant、snScope）
// - implements HasSnIdentifiable（preference 包集成）
// - use UserIdentifiable（自动映射 id/name/email/avatar_url）
// - use HasActivityLog（活动日志）
// - use Commenter + BeReplyer（评论系统集成）
// - use Follower + Liker + Viewer（preference 互动）
// - use SoftDeletes
```

**属性映射**：Member 本身不存储 name/email/avatar_url，通过 `Attribute` 从关联的 User 模型获取：

```php
protected $appends = ['name', 'email', 'avatar_url'];

protected function name(): Attribute
{
    return Attribute::make(get: fn () => $this->user?->name);
}
```

**查找或创建**：

```php
$member = Member::findOrCreate($user);  // 自动处理 team_id
```

### Member 资源

继承 `Wsmallnews\Member\Filament\Resources\Members\BaseResource`：

```php
use Wsmallnews\Member\Filament\Resources\Members\BaseResource;

// BaseResource 已提供：
// - getModel() → Utils::getMemberModel()
// - form() → MemberForm
// - table() → MemberTable
// - 图标、slug、导航排序、翻译标签
```

可配置的具体实现：

```php
use Wsmallnews\Member\Filament\Resources\Members\MemberResource;

// 在 PanelProvider 中注册
$panel->resources([MemberResource::class]);
```

### 中间件

`Wsmallnews\Member\Http\Middleware\ResolveMember` 已注册为 Livewire 持久中间件：

- 从登录用户自动解析/创建 Member 记录
- 检查 Member 状态，禁用时自动登出用户
- 设置 `request()->attributes`：`has_sn_member`、`current_sn_member`

### 辅助函数

| 函数 | 说明 |
|---|---|
| `has_member()` | 前端是否有当前会员（从 request attributes 读取） |
| `current_member()` | 前端当前会员 Model |

### 配置

`config/sn-member.php`：

```php
return [
    'models' => [
        'member' => Models\Member::class,  // 可替换模型
    ],
    'panel_register' => [
        'global_default' => [
            'navigation_group' => 'sn-member::member.global_default.navigation_group',
        ],
        'resources' => [
            MemberResource::class,
        ],
    ],
    'file_directory' => 'sn/member/',
];
```

### Utils 工具类

`Wsmallnews\Member\Support\Utils` — 全部为静态方法：

| 方法 | 说明 |
|---|---|
| `getConfig(?string $name, $default)` | 读取 `sn-member` 配置（dot notation） |
| `getPanelRegister($type)` | 获取面板注册配置（pages/resources） |
| `getModel(string $name, bool $shouldException = true)` | 获取配置的模型类名，`false` 时不抛异常 |
| `getMemberModel()` | `getModel('member')` 快捷方式 |
| `getFileDirectory(?string $type)` | 获取文件目录（自动追加日期） |

### 正确命名空间速查

| 类别 | 命名空间 |
|---|---|
| Member 模型 | `Wsmallnews\Member\Models\Member` |
| MemberResource | `Wsmallnews\Member\Filament\Resources\Members\MemberResource` |
| BaseResource | `Wsmallnews\Member\Filament\Resources\Members\BaseResource` |
| MemberPlugin | `Wsmallnews\Member\MemberPlugin` |
| MemberStatus | `Wsmallnews\Member\Enums\MemberStatus` |
| Utils | `Wsmallnews\Member\Support\Utils` |
| Facade | `Wsmallnews\Member\Facades\Member` |
| ResolveMember 中间件 | `Wsmallnews\Member\Http\Middleware\ResolveMember` |
| ServiceProvider | `Wsmallnews\Member\MemberServiceProvider` |

### 常见错误

- **Member 不存储 name/email/avatar_url**，这些属性从关联的 User 模型获取。确保 User 模型存在这些字段。
- **`CanPagination` 已包含 `WithPagination`**，不要在 Livewire 组件中重复 `use WithPagination`。
- **counter 字段使用 JSON 格式**，模型中需配合 support 包的 `CounterCast` 使用：`'counter' => CounterCast::class`。
- **`Utils` 所有方法都是静态的**，使用 `Utils::getConfig()` 而非 `(new Utils)->getConfig()`。
- **`Utils::getModel()` 默认会抛异常**，传递 `false` 作为第二个参数以允许返回 `null`。
- **Member 状态被禁用时**，ResolveMember 中间件会自动登出用户，无需手动处理。
