<?php

namespace Oxy\Maker\Console;

trait MakerComponentTrait
{
    protected function getIsType(string $type): bool
    {
        return match ($type) {
            'admin'  => $this->option('with-admin') === true || $this->option('only') === 'admin',
            'public' => $this->option('with-public') === true || $this->option('only') === 'public',
            default  => !in_array($this->option('only'), ['admin', 'public'])
        };
    }


}