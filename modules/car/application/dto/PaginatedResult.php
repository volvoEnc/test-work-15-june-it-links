<?php

declare(strict_types=1);

namespace app\modules\car\application\dto;

final class PaginatedResult
{
    /**
     * @param array<int, object> $items
     */
    public function __construct(
        private readonly array $items,
        private readonly int $page,
        private readonly int $perPage,
        private readonly int $total
    ) {
    }

    /**
     * @return array<int, object>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPages(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }
}
