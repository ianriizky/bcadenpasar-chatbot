<?php

namespace Tests\Feature\Concerns;

trait HandleDataTables
{
    /**
     * Return datatables json response format.
     *
     * @return array
     */
    protected function getDataTablesFormat(): array
    {
        return [
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ];
    }
}
