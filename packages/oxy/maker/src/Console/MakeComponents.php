<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MakeComponents extends Command
{
    use MakerComponentTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:components {model} {--with-admin} {--with-public} {--template-full} {--only=} {--created-template=}';

    protected $description = 'Создать компоненты';

    protected $createdComponents = [];

    /** @var array */
    protected $components = [];

    public function handle()
    {
        $createdTemplate = $this->option('created-template') ?? 'default';
        $this->createdComponents = config('maker.created_templates_components.' . $createdTemplate);

        $this->components = $this->getComponentNames();


        $this->checkModel();

        !in_array('route', $this->createdComponents) ?: $this->createRoute();
        !in_array('builder', $this->createdComponents) ?: $this->createBuilder();
        !in_array('permission', $this->createdComponents) ?: $this->createPermission();
        !in_array('policy', $this->createdComponents) ?: $this->createPolicy();
        !in_array('resource', $this->createdComponents) ?: $this->createResource();
        !in_array('collection', $this->createdComponents) ?: $this->createCollection();
        !in_array('service', $this->createdComponents) ?: $this->createService();
        !in_array('request', $this->createdComponents) ?: $this->createRequest();
        !in_array('controller', $this->createdComponents) ?: $this->createController();
        !in_array('testPestController', $this->createdComponents) ?: $this->createControllerTest();
    }

    protected function getNameWithGroup(string $suffix = '', ?string $prefix = null): string
    {
        $model = Str::studly($this->argument('model'));

        if (Str::of($model)->contains('/') === true) {
            $path = Str::of($model)->explode('/')->toArray();
            $fileNameKey = array_key_last($path);

            $pluralModel = Str::pluralStudly($path[$fileNameKey]);

            $path[$fileNameKey] = sprintf('%s/%s%s', $pluralModel, $path[$fileNameKey], $suffix);

            $name = implode('/', $path);
        } else {
            $pluralModel = Str::pluralStudly($model);

            $name = sprintf('%s/%s%s', $pluralModel, $model, $suffix);
        }

        if (isset($prefix)) {
            $name = $this->getNameWithPrefix($name, $prefix);
        }

        return $name;
    }

    protected function getNameWithoutGroup($suffix = '', ?string $prefix = null): string
    {
        $name = Str::studly($this->argument('model'));

        if (isset($prefix)) {
            $name = $this->getNameWithPrefix($name, $prefix);
        }

        return sprintf('%s%s', $name, $suffix);
    }

    protected function getNameWithPrefix(string $name, string $prefix): string
    {
        $splittedPath = Str::of($name)->explode('/')->toArray();

        $splittedPath[array_key_last($splittedPath)] = $prefix . Arr::last($splittedPath);;

        return implode('/', $splittedPath);
    }

    protected function getComponentNames(): array
    {
        return [
            'resource'               => $this->getNameWithGroup('Resource'),
            'resource.admin'         => $this->getNameWithGroup('Resource', 'Admin'),
            'resource.public'        => $this->getNameWithGroup('Resource', 'Public'),
            'collection'             => $this->getNameWithGroup('Collection'),
            'collection.admin'       => $this->getNameWithGroup('Collection', 'Admin'),
            'collection.public'      => $this->getNameWithGroup('Collection', 'Public'),
            'builder'                => $this->getNameWithGroup('Builder'),
            'builder.admin'          => $this->getNameWithGroup('Builder', 'Admin'),
            'builder.public'         => $this->getNameWithGroup('Builder', 'Public'),
            'permission'             => $this->getNameWithoutGroup('PermissionsEnum'),
            'policy'                 => $this->getNameWithoutGroup('Policy'),
            'service'                => $this->getNameWithGroup('Service'),
            'request.store.admin'    => $this->getNameWithGroup('StoreRequest', 'Admin'),
            'request.update.admin'   => $this->getNameWithGroup('UpdateRequest', 'Admin'),
            'controller'             => $this->getNameWithGroup('Controller'),
            'controller.admin'       => $this->getNameWithGroup('Controller', 'Admin'),
            'controller.public'      => $this->getNameWithGroup('Controller', 'Public'),
            'test.controller'        => $this->getNameWithGroup('Test'),
            'test.controller.admin'  => $this->getNameWithGroup('Test', 'Admin'),
            'test.controller.public' => $this->getNameWithGroup('Test', 'Public'),
        ];
    }

    protected function createResource()
    {
        $this->call(
            'oxy:maker:resource',
            [
                'name'  => Arr::get($this->components, 'resource'),
                'model' => $this->argument('model'),
            ]
        );

        if ($this->option('with-admin') === true) {
            $this->call(
                'oxy:maker:resource',
                [
                    'name'  => Arr::get($this->components, 'resource.admin'),
                    'model' => $this->argument('model'),
                ]
            );
        }

        if ($this->option('with-public') === true) {
            $this->call(
                'oxy:maker:resource',
                [
                    'name'  => Arr::get($this->components, 'resource.public'),
                    'model' => $this->argument('model'),
                ]
            );
        }
    }

    protected function createCollection()
    {
        $this->call('oxy:maker:collection', ['name' => Arr::get($this->components, 'collection')]);

        if ($this->option('with-admin') === true) {
            $this->call('oxy:maker:collection', ['name' => Arr::get($this->components, 'collection.admin')]);
        }

        if ($this->option('with-public') === true) {
            $this->call('oxy:maker:collection', ['name' => Arr::get($this->components, 'collection.public')]);
        }
    }

    protected function createBuilder()
    {
        if ($this->getIsType('protected')) {
            $this->call(
                'oxy:maker:builder',
                [
                    'name'  => Arr::get($this->components, 'builder'),
                    'model' => $this->argument('model'),
                ]
            );
        }

        if ($this->getIsType('admin')) {
            $this->call(
                'oxy:maker:builder',
                [
                    'name'  => Arr::get($this->components, 'builder.admin'),
                    'model' => $this->argument('model'),
                ]
            );
        }

        if ($this->getIsType('public')) {
            $this->call(
                'oxy:maker:builder',
                [
                    'name'  => Arr::get($this->components, 'builder.public'),
                    'model' => $this->argument('model'),
                ]
            );
        }
    }

    protected function createPermission()
    {
        $this->call('oxy:maker:permission', ['name' => Arr::get($this->components, 'permission')]);
    }

    protected function createPolicy()
    {
        $this->call(
            'oxy:maker:policy',
            [
                'name'       => Arr::get($this->components, 'policy'),
                'permission' => Arr::get($this->components, 'permission'),
            ]
        );
    }

    protected function createService()
    {
        $this->call(
            'oxy:maker:service',
            [
                'name'  => Arr::get($this->components, 'service'),
                'model' => $this->argument('model'),
            ]
        );
    }

    protected function createRequest()
    {
        if ($this->getIsType('admin')) {
            $this->call(
                'oxy:maker:request', [
                    'name'  => Arr::get($this->components, 'request.store.admin'),
                    'model' => $this->argument('model'),
                    'type'  => 'store',
                ]
            );

            $this->call(
                'oxy:maker:request',
                [
                    'name'  => Arr::get($this->components, 'request.update.admin'),
                    'model' => $this->argument('model'),
                    'type'  => 'update',
                ]
            );
        }
    }

    protected function createController()
    {
        if ($this->getIsType('protected')) {
            $this->call(
                'oxy:maker:controller',
                [
                    'name'       => Arr::get($this->components, 'controller'),
                    'model'      => $this->argument('model'),
                    'builder'    => Arr::get($this->components, 'builder'),
                    'collection' => Arr::get($this->components, 'collection'),
                    'resource'   => Arr::get($this->components, 'resource'),
                    '--full'     => $this->option('template-full'),
                ],
            );
        }

        if ($this->getIsType('admin')) {
            $this->call(
                'oxy:maker:controller',
                [
                    'name'           => Arr::get($this->components, 'controller.admin'),
                    'model'          => $this->argument('model'),
                    'builder'        => Arr::get($this->components, 'builder.admin'),
                    'collection'     => Arr::get($this->components, 'collection.admin'),
                    'resource'       => Arr::get($this->components, 'resource.admin'),
                    'service'        => Arr::get($this->components, 'service'),
                    'request.store'  => Arr::get($this->components, 'request.store.admin'),
                    'request.update' => Arr::get($this->components, 'request.update.admin'),
                    'prefix'         => 'Admin',
                    '--full'         => true,
                ],
            );
        }

        if ($this->getIsType('public')) {
            $this->call(
                'oxy:maker:controller',
                [
                    'name'       => Arr::get($this->components, 'controller.public'),
                    'model'      => $this->argument('model'),
                    'builder'    => Arr::get($this->components, 'builder.public'),
                    'collection' => Arr::get($this->components, 'collection.public'),
                    'resource'   => Arr::get($this->components, 'resource.public'),
                    'prefix'     => 'Public',
                    '--full'     => $this->option('template-full'),
                ],
            );
        }
    }

    protected function createControllerTest()
    {
        if ($this->getIsType('protected')) {
            $this->call(
                'oxy:maker:test:pest:controller',
                [
                    'name'       => Arr::get($this->components, 'test.controller'),
                    'model'      => $this->argument('model'),
                    'permission' => Arr::get($this->components, 'permission'),
                ]
            );
        }

        if ($this->getIsType('admin')) {
            $this->call(
                'oxy:maker:test:pest:controller',
                [
                    'name'       => Arr::get($this->components, 'test.controller.admin'),
                    'model'      => $this->argument('model'),
                    'permission' => Arr::get($this->components, 'permission'),
                    'type'       => 'admin',
                ]
            );
        }

        if ($this->getIsType('public')) {
            $this->call(
                'oxy:maker:test:pest:controller',
                [
                    'name'       => Arr::get($this->components, 'test.controller.public'),
                    'model'      => $this->argument('model'),
                    'permission' => Arr::get($this->components, 'permission'),
                    'type'       => 'public',
                ]
            );
        }
    }

    protected function createRoute()
    {
        if (Str::of($this->argument('model'))->contains('/')) {
            $this->error('Route не может быть создан. Необходимо создать его вручную.');
            return;
        }

        if ($this->getIsType('protected')) {
            $this->call(
                'oxy:maker:route',
                [
                    'name'       => $this->argument('model'),
                    'controller' => Arr::get($this->components, 'controller'),
                    '--full'     => $this->option('template-full'),
                ]
            );
        }

        if ($this->getIsType('admin')) {
            $this->call(
                'oxy:maker:route',
                [
                    'name'       => $this->argument('model'),
                    'controller' => Arr::get($this->components, 'controller.admin'),
                    'type'       => 'admin',
                    '--full'     => true,
                ]
            );
        }

        if ($this->getIsType('public')) {
            $this->call(
                'oxy:maker:route',
                [
                    'name'       => $this->argument('model'),
                    'controller' => Arr::get($this->components, 'controller.public'),
                    'type'       => 'public',
                    '--full'     => $this->option('template-full'),
                ]
            );
        }
    }

    protected function checkModel()
    {
        try {
            resolve('App\\Models\\' . Str::of($this->argument('model'))->replace('/', '\\'));
        } catch (BindingResolutionException $exception) {
            $this->error(sprintf('Model %s does not exist', $this->argument('model')));
            $isCreateModel = Str::of($this->ask('Create model? [Y/n]'))->lower();

            if ($isCreateModel == 'y' || $isCreateModel == 'yes') {
                $this->call(
                    'make:model',
                    [
                        'name'        => $this->argument('model'),
                        '--factory'   => true,
                        '--migration' => true,
                        '--seed'      => true,
                    ]
                );
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            exit();
        }
    }
}
