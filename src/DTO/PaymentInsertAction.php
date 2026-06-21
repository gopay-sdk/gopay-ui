<?php

namespace Gopay\GopayUi\DTO;

use Exception;
use Illuminate\Database\Eloquent\Model;

class PaymentInsertAction
{
    public function __construct(
        public string $model,
        public array $data = []
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if (empty($this->model)) {
            throw new Exception('PaymentInsertAction: model is required');
        }

        if (!class_exists($this->model)) {
            throw new Exception(
                "PaymentInsertAction: model '{$this->model}' does not exist"
            );
        }

        if (!is_subclass_of($this->model, Model::class)) {
            throw new Exception(
                "PaymentInsertAction: '{$this->model}' is not an Eloquent model"
            );
        }
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'data' => $this->data,
        ];
    }
}
