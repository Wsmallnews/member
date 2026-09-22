<?php

namespace Wsmallnews\Member\Commands;

use Wsmallnews\Support\Commands\PackageInstallCommand;

class MemberInstallCommand extends PackageInstallCommand
{
    protected string $packageName = 'sn-member';
}
