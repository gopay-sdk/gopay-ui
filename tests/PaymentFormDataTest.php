<?php

use Gopay\GopayUi\DTO\PaymentFormData;

it('accepts valid USD amount', function () {

    $dto = new PaymentFormData(
        amount: 1,
        currency: 'USD'
    );

    expect($dto)->toBeInstanceOf(PaymentFormData::class);
});

it('accepts valid CDF amount', function () {

    $dto = new PaymentFormData(
        amount: 500,
        currency: 'CDF'
    );

    expect($dto)->toBeInstanceOf(PaymentFormData::class);
});

it('rejects invalid currency', function () {

    expect(fn() => new PaymentFormData(
        amount: 10,
        currency: 'EUR'
    ))->toThrow(Exception::class);
});

it('rejects usd amount below minimum', function () {

    expect(fn() => new PaymentFormData(
        amount: 0.99,
        currency: 'USD'
    ))->toThrow(Exception::class);
});

it('rejects cdf amount below minimum', function () {

    expect(fn() => new PaymentFormData(
        amount: 499,
        currency: 'CDF'
    ))->toThrow(Exception::class);
});

it('rejects invalid phone number', function () {

    expect(fn() => new PaymentFormData(
        amount: 100,
        currency: 'USD',
        phone: '123'
    ))->toThrow(Exception::class);
});

it('accepts valid phone number', function () {

    $dto = new PaymentFormData(
        amount: 100,
        currency: 'USD',
        phone: '991234567'
    );

    expect($dto->phone)->toBe('991234567');
});
