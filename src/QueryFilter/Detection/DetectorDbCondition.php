<?php

namespace eloquentFilter\QueryFilter\Detection;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use Exception;

/**
 * Class DetectorConditions.
 */
class DetectorDbCondition
{
    protected string $errorExceptionWhileList = "You must set %s in whiteListFilter in %s
         or create an override method with name %s or call ignoreRequest method for ignore %s.";
    /**
     * @var \Illuminate\Support\Collection
     */
    private \Illuminate\Support\Collection $detector;

    /**
     * DetectorConditions constructor.
     *
     * @param array $detector
     */
    public function __construct(array $detector)
    {
        $detector_collect = collect($detector);

        $detector_collect->map(function ($detector_obj) {
            if (!empty($detector_obj)) {
                $reflect = new \ReflectionClass($detector_obj);
                if ($reflect->implementsInterface(DefaultConditionsContract::class)) {
                    return $detector_obj;
                }
            }
        })->toArray();

        $this->setDetector($detector_collect);
    }

    /**
     * @param \Illuminate\Support\Collection $detector
     */
    public function setDetector(\Illuminate\Support\Collection $detector): void
    {
        $this->detector = $detector;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDetector(): \Illuminate\Support\Collection
    {
        return $this->detector;
    }

    /**
     * @param string $field
     * @param $params
     * @param null $model
     *
     * @return string|null
     * @throws Exception
     *
     */
    public function detect(string $field, $params, $model = null): ?string
    {
        $out = $this->getDetector()->map(function ($item) use ($field, $params) {
            $query = $item::detect($field, $params);

            if (!empty($query)) {
                return $query;
            }
        })->filter();

        return $out->first();
    }
}