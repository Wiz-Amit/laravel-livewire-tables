<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Traits\Configuration\SortingConfiguration;
use Rappasoft\LaravelLivewireTables\Traits\Helpers\SortingHelpers;

trait WithSorting
{
    use SortingConfiguration,
        SortingHelpers;

    public array $sorts = [];
    protected bool $sortingStatus = true;
    protected bool $singleColumnSortingStatus = false;
    protected ?string $defaultSortColumn = null;
    protected string $defaultSortDirection = 'asc';

    /**
     * @param  string  $field
     *
     * @return string|null
     */
    public function sortBy(string $field): ?string
    {
        if ($this->sortingIsDisabled()) {
            return null;
        }

        // If single sorting is enabled and there are sorts but not the field that is being sorted,
        // then clear all the sorts
        if ($this->singleSortingIsEnabled() && $this->hasSorts() && ! $this->hasSort($field)) {
            $this->clearSorts();
        }

        if (! $this->hasSort($field)) {
            return $this->setSortAsc($field);
        }

        if ($this->isSortAsc($field)) {
            return $this->setSortDesc($field);
        }

        $this->clearSort($field);

        return null;
    }

    // TODO: Test
    public function applySorting(Builder $builder): Builder
    {
        if ($this->hasDefaultSort() && ! $this->hasSorts()) {
            return $builder->orderBy($this->getDefaultSortColumn(), $this->getDefaultSortDirection());
        }

        foreach ($this->getSorts() as $field => $direction) {
            if (! in_array($direction, ['asc', 'desc'])) {
                $direction = 'desc';
            }

//            $column = $this->getColumn($field);
//
//            dd($column);
        }

        return $builder;
    }
}
