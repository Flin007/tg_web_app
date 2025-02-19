<?php

namespace App\Observers;

use App\Storages\CarBranStorage;
use Psr\SimpleCache\InvalidArgumentException;

class CarBrandObserver
{
    private CarBranStorage $storage;

    public function __construct(CarBranStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function created(): void
    {
        $this->storage->clear();
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function updated(): void
    {
        $this->storage->clear();
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function deleted(): void
    {
        $this->storage->clear();
    }
}
