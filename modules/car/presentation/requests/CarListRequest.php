<?php

declare(strict_types=1);

namespace app\modules\car\presentation\requests;

use yii\base\Model;

final class CarListRequest extends Model
{
    public $page = 1;

    public function rules(): array
    {
        return [
            ['page', 'default', 'value' => 1],
            ['page', 'integer', 'min' => 1],
        ];
    }

    public function getPage(): int
    {
        return (int) $this->page;
    }
}
