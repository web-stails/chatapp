<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRoute extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:route {name} {controller} {type=default} {--F|full}';

    protected $description = 'Создать новый pest test для controller';

    protected function getStub()
    {
        if ($this->option('full')) {
            return __DIR__ . '/stubs/route.full.stub';
        }

        return __DIR__ . '/stubs/route.stub';
    }

    public function handle()
    {
        if (Str::of($this->argument('name'))->contains('/')) {
            $this->error('Route не может быть создан. Необходимо создать его вручную.');
            return;
        }

        return parent::handle();
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    public function getPath($name)
    {
        $path = Str::of($this->getPreparedName($name))->snake();

        $path = match ($this->argument('type')) {
            'admin' => base_path() . '/routes/api/admin/' . $path . '.php',
            'public' => base_path() . '/routes/api/public/' . $path . '.php',
            default => base_path() . '/routes/api/' . $path . '.php'
        };

        return $path;
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $replace = [
            'DummyControllerNamespace' => Str::of($this->argument('controller'))->replace('/', '\\'),
            'DummyController'          => Str::of($this->argument('controller'))->explode('/')->last(),
            'DummyRoute'               => Str::of($this->getPreparedName($name))->kebab(),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }

    protected function getPreparedName($name): string
    {
        return Str::of($name)
            ->replace('App\\', '')
            ->replace('\\', '/')
            ->replace('/-', '/')
            ->explode('/')
            ->map(function ($item) {
                return Str::of($item)->plural();
            })
            ->implode('/');
    }
}
