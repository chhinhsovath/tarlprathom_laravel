<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Sortable
{
    /**
     * Apply sorting to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function applySorting($query, Request $request, array $allowedSorts, string $defaultSort = 'id', string $defaultOrder = 'asc'): array
    {
        $sortField = $request->get('sort', $defaultSort);
        $sortOrder = $request->get('order', $defaultOrder);

        // Validate sort field
        if (! in_array($sortField, $allowedSorts)) {
            $sortField = $defaultSort;
        }

        // Validate sort order
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = $defaultOrder;
        }

        $query->orderBy($sortField, $sortOrder);

        return [
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ];
    }
}
