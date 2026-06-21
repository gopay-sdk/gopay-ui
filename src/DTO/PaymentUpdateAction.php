<?php

namespace Gopay\GopayUi\DTO;

use Exception;
use Illuminate\Database\Eloquent\Model;

class PaymentUpdateAction
{
    public function __construct(
        public string $model,
        public array $where,
        public array $data,
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if (empty($this->model)) {
            throw new Exception('Model is required');
        }

        if (!class_exists($this->model)) {
            throw new Exception("Model '{$this->model}' not found");
        }

        if (!is_subclass_of($this->model, Model::class)) {
            throw new Exception(
                "'{$this->model}' is not an Eloquent model"
            );
        }

        if (empty($this->where)) {
            throw new Exception(
                'PaymentUpdateAction: where clause is required'
            );
        }
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'where' => $this->where,
            'data' => $this->data,
        ];
    }
}
