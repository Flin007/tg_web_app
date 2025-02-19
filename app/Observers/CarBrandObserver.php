<?php

namespace App\Observers;

use App\Storages\CarBrandStorage;
use Psr\SimpleCache\InvalidArgumentException;

class CarBrandObserver
{
    private CarBrandStorage $storage;

    public function __construct(CarBrandStorage $storage)
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
