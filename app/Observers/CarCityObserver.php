<?php

namespace App\Observers;

use App\Storages\CarCityStorage;
use Psr\SimpleCache\InvalidArgumentException;

class CarCityObserver
{
    private CarCityStorage $storage;

    public function __construct(CarCityStorage $storage)
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
