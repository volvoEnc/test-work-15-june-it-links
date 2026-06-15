<?php

declare(strict_types=1);

namespace app\modules\car\presentation\requests;

use app\modules\car\application\commands\CreateCarOptionCommand;
use yii\base\Model;

final class CarOptionCreateRequest extends Model
{
    public ?string $brand = null;
    public ?string $model = null;
    public $year = null;
    public ?string $body = null;
    public $mileage = null;

    public function rules(): array
    {
        return [
            [['brand', 'model', 'year', 'body', 'mileage'], 'required'],
            [['brand', 'model', 'body'], 'trim'],
            [['brand', 'model', 'body'], 'string', 'max' => 255],
            ['year', 'integer', 'min' => 1886, 'max' => 2100],
            ['mileage', 'integer', 'min' => 0],
        ];
    }

    public function toCommand(): CreateCarOptionCommand
    {
        return new CreateCarOptionCommand(
            trim((string) $this->brand),
            trim((string) $this->model),
            (int) $this->year,
            trim((string) $this->body),
            (int) $this->mileage
        );
    }
}
