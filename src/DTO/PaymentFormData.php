<?php

namespace Gopay\GopayUi\DTO;

use Exception;
use Gopay\GopayUi\Enums\PaymentSuccessAction;

class PaymentFormData
{
    /**
     * @param PaymentInsertAction[] $insertActions
     */
    public function __construct(
        public float $amount,
        public string $currency,
        public ?string $phone = null,
        public PaymentSuccessAction $onSuccess = PaymentSuccessAction::REFRESH_PAGE,
        public ?string $redirectUrl = null,
        public array $insertActions = [],
        public array $updateActions = [],
        public string $formColor = '#262626',
        public ?string $payBtnLabel = 'PAYER',
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if ($this->amount <= 0) {
            throw new Exception(
                "PaymentFormData: invalid amount '{$this->amount}'"
            );
        }

        if (!in_array($this->currency, ['USD', 'CDF'], true)) {
            throw new Exception(
                "PaymentFormData: invalid currency '{$this->currency}'"
            );
        }

        if (
            ($this->currency === 'CDF' && $this->amount < 500)
            || ($this->currency === 'USD' && $this->amount < 1)
        ) {
            throw new Exception(
                "PaymentFormData: minimum amount is 500 CDF or 1 USD"
            );
        }

        if (
            $this->phone !== null &&
            !preg_match('/^[0-9]{9}$/', $this->phone)
        ) {
            throw new Exception(
                "PaymentFormData: invalid phone '{$this->phone}'"
            );
        }

        foreach ($this->insertActions as $action) {
            if (!$action instanceof PaymentInsertAction) {
                throw new Exception(
                    'PaymentFormData: insertActions must contain only PaymentInsertAction objects'
                );
            }
        }

        foreach ($this->updateActions as $action) {
            if (!$action instanceof PaymentUpdateAction) {
                throw new Exception(
                    'PaymentFormData: updateActions must contain only PaymentUpdateAction objects'
                );
            }
        }

        if (
            $this->onSuccess === PaymentSuccessAction::GO_TO_URL
            && empty($this->redirectUrl)
        ) {
            throw new Exception(
                'PaymentFormData: redirectUrl is required when onSuccess = GO_TO_URL'
            );
        }

        if ($this->formColor !== null) {

            if (!preg_match('/^#[a-fA-F0-9]{6}$/', $this->formColor)) {
                throw new \Exception(
                    "Invalid color format. Expected HEX like #FF0000"
                );
            }
        }
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'phone' => $this->phone,
            'onSuccess' => $this->onSuccess->value,
            'redirectUrl' => $this->redirectUrl,
            'formColor' => $this->formColor,
            'payBtnLabel' => $this->payBtnLabel,
            'insertActions' => array_map(
                fn($action) => $action->toArray(),
                $this->insertActions
            ),
            'updateActions' => array_map(
                fn($action) => $action->toArray(),
                $this->updateActions
            ),
        ];
    }
}
