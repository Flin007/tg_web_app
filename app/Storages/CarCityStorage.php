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

    public function all(): Collection
    {
        return $this->cache->remember(
            "{$this->prefix}all",
            DateTimeHelper::HOUR,
            fn() => $this->repository->all(),
        );
    }

    public function allActive(): Collection
    {
        return $this->all()->filter(function (CarCity $carCity) {
            $carCity->is_active = true;
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function clear(): void
    {
        $this->cache->delete("{$this->prefix}all");
    }
}
