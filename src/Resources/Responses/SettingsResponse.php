<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources\Responses;

use SushiMarket\UdsSdk\Enums\Currency;
use SushiMarket\UdsSdk\Enums\DiscountPolicy;
use SushiMarket\UdsSdk\Resources\LoyaltyProgramSettings;

class SettingsResponse
{
    /** Идентификатор компании в UDS */
    public int $id;

    /** Название компании */
    public string $name;

    /** Промокод компании для вступления */
    public string $promoCode;

    /** Валюта компании (стандарт ISO-4217) */
    public ?Currency $currency;

    /** Тип программы лояльности */
    public DiscountPolicy $baseDiscountPolicy;

    /** Настройки бонусной программы компании */
    public LoyaltyProgramSettings $loyaltyProgramSettings;

    /** Возможность проведения операции, используя номер телефона клиента */
    public bool $purchaseByPhone;

    /** Необходимо ли указывать номер счета при проведении оплаты через UDS Кассир */
    public bool $writeInvoice;

    /** Доменное имя, которое отображается в ссылке на веб-страницу вашей компании */
    public string $slug;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->promoCode = $data['promoCode'];
        $this->currency = isset($data['currency']) ? Currency::from($data['currency']) : null;
        $this->baseDiscountPolicy = DiscountPolicy::from($data['baseDiscountPolicy']);
        $this->loyaltyProgramSettings = new LoyaltyProgramSettings($data['loyaltyProgramSettings']);
        $this->purchaseByPhone = $data['purchaseByPhone'];
        $this->writeInvoice = $data['writeInvoice'];
        $this->slug = $data['slug'];
    }
}