<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakePermissionGroup extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:permission-group {name=PermissionGroupsEnum}';

    protected $description = 'Создать группу для разрешений';

    protected function getStub()
    {
        return __DIR__ . '/stubs/permission.group.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Enums\PermissionGroups';
    }
}
