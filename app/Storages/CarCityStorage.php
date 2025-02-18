<?php
namespace App\Storages;

use App\Helpers\DateTimeHelper;
use App\Models\CarCity;
use App\Repositories\CarCityRepository;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;
use Psr\SimpleCache\InvalidArgumentException;

class CarCityStorage
{
    private const PREFIX = 'CarCityStorage::';
    private string $prefix = self::PREFIX;
    private CarCityRepository $repository;
    private CacheManager $cache;

    public function __construct(CarCityRepository $repository, CacheManager $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Получаем все города из репозитория и кешируем на день.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->cache->remember(
            "{$this->prefix}all",
            DateTimeHelper::DAY,
            fn() => $this->repository->all(),
        );
    }

    /**
     * Получаем все активные города из закешированных всех городов и кешируем на день.
     *
     * @return Collection
     */
    public function allActive(): Collection
    {
        return $this->cache->remember(
            "{$this->prefix}allActive",
            DateTimeHelper::DAY,
            fn() => $this->all()->filter(function (CarCity $carCity) {
                return $carCity->is_active === true;
            }),
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function clear(): void
    {
        $this->cache->delete("{$this->prefix}all");
        $this->cache->delete("{$this->prefix}allActive");
    }
}
