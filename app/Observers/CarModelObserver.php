<?php

namespace App\Observers;

use App\Storages\CarModelStorage;
use Psr\SimpleCache\InvalidArgumentException;

class CarModelObserver
{
    private CarModelStorage $storage;

    public function __construct(CarModelStorage $storage)
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
