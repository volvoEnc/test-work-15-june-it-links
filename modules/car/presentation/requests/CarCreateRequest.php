<?php

declare(strict_types=1);

namespace app\modules\car\presentation\requests;

use app\modules\car\application\commands\CreateCarCommand;
use yii\base\Model;

final class CarCreateRequest extends Model
{
    public ?string $title = null;
    public ?string $description = null;
    public $price = null;
    public ?string $photo_url = null;
    public ?string $contacts = null;
    public $options = null;

    private ?CarOptionCreateRequest $validatedOptions = null;

    public function rules(): array
    {
        return [
            [['title', 'description', 'price', 'photo_url', 'contacts'], 'required'],
            [['title', 'description', 'photo_url', 'contacts'], 'trim'],
            [['title', 'contacts'], 'string', 'max' => 255],
            ['description', 'string'],
            ['price', 'number', 'min' => 0],
            ['photo_url', 'string', 'max' => 2048],
            ['photo_url', 'url'],
            ['options', 'validateOptions'],
        ];
    }

    public function validateOptions(string $attribute): void
    {
        $this->validatedOptions = null;

        if ($this->options === null) {
            return;
        }

        if (!is_array($this->options)) {
            $this->addError($attribute, 'Options must be an object.');
            return;
        }

        $optionRequest = new CarOptionCreateRequest();
        $optionRequest->load($this->options, '');

        if (!$optionRequest->validate()) {
            foreach ($optionRequest->getErrors() as $field => $errors) {
                foreach ($errors as $error) {
                    $this->addError('options.' . $field, $error);
                }
            }
            return;
        }

        $this->validatedOptions = $optionRequest;
    }

    public function toCommand(): CreateCarCommand
    {
        return new CreateCarCommand(
            trim((string) $this->title),
            trim((string) $this->description),
            number_format((float) $this->price, 2, '.', ''),
            trim((string) $this->photo_url),
            trim((string) $this->contacts),
            $this->validatedOptions?->toCommand()
        );
    }
}
