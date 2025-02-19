<?php
namespace App\Storages;

use App\Helpers\DateTimeHelper;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Repositories\CarBrandRepository;
use App\Repositories\CarModelRepository;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;
use Psr\SimpleCache\InvalidArgumentException;

class CarModelStorage
{
    private const PREFIX = 'CarModelStorage::';
    private string $prefix = self::PREFIX;
    private CarModelRepository $repository;
    private CacheManager $cache;

    public function __construct(CarModelRepository $repository, CacheManager $cache)
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
            fn() => $this->all()->filter(function (CarModel $carBrand) {
                return $carBrand->is_active === true;
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
