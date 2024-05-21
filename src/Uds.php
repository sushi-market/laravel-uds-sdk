<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk;

use Illuminate\Http\Client\PendingRequest;
use InvalidArgumentException;
use SushiMarket\UdsSdk\Exceptions\UnauthorizedException;
use SushiMarket\UdsSdk\Interfaces\HttpClientInterface;
use SushiMarket\UdsSdk\Resources\ExternalCashier;
use SushiMarket\UdsSdk\Resources\Nonce;
use SushiMarket\UdsSdk\Resources\Responses\CalculateTransactionResponse;
use SushiMarket\UdsSdk\Resources\Responses\CreateTransactionResponse;
use SushiMarket\UdsSdk\Resources\Responses\RefundTransactionResponse;
use SushiMarket\UdsSdk\Resources\Responses\SettingsResponse;

class Uds
{
    /** Основной URI для доступа к API */
    protected string $baseUrl = 'https://api.uds.app/partner/v2';

    protected PendingRequest $client;

    /**
     * @param int $companyId ID компании
     * @param string $apiKey API ключ интеграции
     */
    public function __construct(
        protected readonly int $companyId,
        protected readonly string $apiKey,
    ) {
        $this->client = app(HttpClientInterface::class, [
            'baseUrl' => $this->baseUrl,
            'companyId' => $this->companyId,
            'token' => $this->apiKey,
        ])->getClient();
    }

    /**
     * Рассчитать информацию транзакции по коду на оплату
     *
     * Информацию о доступных бонусных баллах для списания или размере скидки
     * (в зависимости от настроек бонусной программы),
     * сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов,
     * которые будут начислены после выполнения транзакции.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations~1calc/post
     *
     * @param string $code Код на оплату
     * @param array $receipt Информация о чеке
     * @return CalculateTransactionResponse Информация о транзакции
     */
    public function calculateTransactionByCode(string $code, array $receipt): CalculateTransactionResponse
    {
        return $this->calculateTransaction($receipt, $code);
    }

    /**
     * Рассчитать информацию транзакции по номеру телефона
     *
     * Информацию о доступных бонусных баллах для списания или размере скидки
     * (в зависимости от настроек бонусной программы),
     * сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов,
     * которые будут начислены после выполнения операции.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations~1calc/post
     *
     * @param string $phone Номер телефона
     * @param array $receipt Информация о чеке
     * @return CalculateTransactionResponse Информация о транзакции
     */
    public function calculateTransactionByPhone(string $phone, array $receipt): CalculateTransactionResponse
    {
        return $this->calculateTransaction($receipt, participant: ['phone' => $phone]);
    }

    /**
     * Рассчитать информацию транзакции через идентификатор клиента в UDS
     *
     * Информацию о доступных бонусных баллах для списания или размере скидки
     * (в зависимости от настроек бонусной программы),
     * сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов,
     * которые будут начислены после выполнения транзакции.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations~1calc/post
     *
     * @param string $uid Идентификатор клиента в UDS
     * @param array $receipt Информация о чеке
     * @return CalculateTransactionResponse Информация о транзакции
     */
    public function calculateTransactionByUid(string $uid, array $receipt): CalculateTransactionResponse
    {
        return $this->calculateTransaction($receipt, participant: ['uid' => $uid]);
    }

    /**
     * Рассчитать информацию транзакции идентификатор клиента в UDS, номер телефона или коду на оплату
     *
     * Информация о доступных бонусных баллах для списания или размере скидки
     * (в зависимости от настроек бонусной программы),
     * сумме к оплате после применения скидок / бонусов и о количестве бонусных баллов,
     * которые будут начислены после выполнения транзакции.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations~1calc/post
     *
     * @param array $receipt Информация о чеке
     * @param string|null $code Код на оплату
     * @param array|null $participant Информация о клиенте
     * @return CalculateTransactionResponse Информация о транзакции
     * @noinspection PhpUnusedParameterInspection
     */
    public function calculateTransaction(
        array $receipt,
        string $code = null,
        array $participant = null,
    ): CalculateTransactionResponse
    {
        $definedVars = get_defined_vars();

        if (!$code && !$participant) {
            throw new InvalidArgumentException('Code or participant required');
        }

        return new CalculateTransactionResponse($this->client->post('operations/calc', $definedVars)->json());
    }

    /**
     * Получение настроек компании
     *
     * Получение настроек компании, способа предоставления скидки и промокода для вступления в компанию.
     * Способ предоставления скидки настраивается в UDS.
     *
     * @see https://docs.uds.app/#tag/Settings/paths/~1settings/get
     *
     * @return SettingsResponse Настройки компании
     */
    public function getSettings(): SettingsResponse
    {
        return new SettingsResponse($this->client->get('settings')->json());
    }

    /**
     * Протестировать введённые $companyId и $apiKey
     *
     * @return bool
     * @noinspection PhpRedundantCatchClauseInspection
     */
    public function testCredentials(): bool
    {
        try {
            return $this->getSettings()->id === $this->companyId;
        } catch (UnauthorizedException) {
            return false;
        }
    }

    /**
     * Проведение транзакции по коду на оплату
     *
     * Проведение операции в UDS. После успешного завершения операция отобразится в списке операций в UDS,
     * а администратор и клиент получат push-уведомление о покупке.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations/post
     *
     * @param string $code Код на оплату
     * @param array $receipt Информация о чеке
     * @param ExternalCashier|null $cashier Информация о сотруднике
     * @param int[]|null $tags Список тегов компании, назначаемых клиенту при проведении операции
     * @param Nonce|null $nonce Уникальный идентификатор операции
     * @return CreateTransactionResponse Созданная транзакция
     */
    public function createTransactionByCode(
        string $code,
        array $receipt,
        ?ExternalCashier $cashier = null,
        ?array $tags = null,
        ?Nonce $nonce = null,
    ): CreateTransactionResponse
    {
        return $this->createTransaction($receipt, cashier: $cashier, tags: $tags, nonce: $nonce, code: $code);
    }

    /**
     * Проведение транзакции по номеру телефона
     *
     * Проведение операции в UDS. После успешного завершения операция отобразится в списке операций в UDS,
     * а администратор и клиент получат push-уведомление о покупке.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations/post
     *
     * @param string $phone Номер телефона
     * @param array $receipt Информация о чеке
     * @param ExternalCashier|null $cashier Информация о сотруднике
     * @param int[]|null $tags Список тегов компании, назначаемых клиенту при проведении операции
     * @param Nonce|null $nonce Уникальный идентификатор операции
     * @return CreateTransactionResponse Созданная транзакция
     */
    public function createTransactionByPhone(
        string $phone,
        array $receipt,
        ?ExternalCashier $cashier = null,
        ?array $tags = null,
        ?Nonce $nonce = null,
    ): CreateTransactionResponse
    {
        return $this->createTransaction($receipt, cashier: $cashier, tags: $tags, nonce: $nonce, participant: ['phone' => $phone]);
    }

    /**
     * Проведение транзакции по идентификатору клиента в UDS
     *
     * Проведение операции в UDS. После успешного завершения операция отобразится в списке операций в UDS,
     * а администратор и клиент получат push-уведомление о покупке.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations/post
     *
     * @param string $uid Идентификатор клиента в UDS
     * @param array $receipt Информация о чеке
     * @param ExternalCashier|null $cashier Информация о сотруднике
     * @param int[]|null $tags Список тегов компании, назначаемых клиенту при проведении операции
     * @param Nonce|null $nonce Уникальный идентификатор операции
     * @return CreateTransactionResponse Созданная транзакция
     */
    public function createTransactionByUid(
        string $uid,
        array $receipt,
        ?ExternalCashier $cashier = null,
        ?array $tags = null,
        ?Nonce $nonce = null,
    ): CreateTransactionResponse
    {
        return $this->createTransaction($receipt, cashier: $cashier, tags: $tags, nonce: $nonce, participant: ['uid' => $uid]);
    }

    /**
     * Проведение транзакции
     *
     * Проведение операции в UDS. После успешного завершения операция отобразится в списке операций в UDS,
     * а администратор и клиент получат push-уведомление о покупке.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations/post
     *
     * @param array $receipt Информация о чеке
     * @param string|null $code Код на оплату
     * @param array|null $participant Информация о клиенте
     * @param ExternalCashier|null $cashier Информация о сотруднике
     * @param int[]|null $tags Список тегов компании, назначаемых клиенту при проведении операции
     * @param Nonce|null $nonce Уникальный идентификатор операции
     * @return CreateTransactionResponse Созданная транзакция
     * @noinspection PhpUnusedParameterInspection
     */
    protected function createTransaction(
        array $receipt,
        ?ExternalCashier $cashier,
        ?array $tags = null,
        ?Nonce $nonce = null,
        ?string $code = null,
        ?array $participant = null,
    ): CreateTransactionResponse
    {
        $payload = compact('receipt');

        if ($code) {
            $payload['code'] = $code;
        }

        if ($participant) {
            $payload['participant'] = $participant;
        }

        if ($cashier) {
            $payload['cashier'] = [
                'externalId' => $cashier->externalId,
                'name' => $cashier->name,
            ];
        }

        if ($tags) {
            $payload['tags'] = $tags;
        }

        if ($nonce) {
            $payload['nonce'] = $nonce->getUuid();
        }

        return new CreateTransactionResponse($this->client->post('operations', $payload)->json());
    }

    /**
     * Возврат транзакции
     *
     * Возврат операции по ее идентификатору.
     * Если указан параметр partialAmount, то возврат будет частичным, иначе - полным.
     *
     * @see https://docs.uds.app/#tag/Operations/paths/~1operations~1{id}~1refund/post
     *
     * @param int $id Идентификатор транзакции
     * @param float|null $partialAmount Сумма возврата
     * @return RefundTransactionResponse Возвращенная транзакция
     * @noinspection PhpUnusedParameterInspection
     */
    public function refundTransaction(int $id, ?float $partialAmount = null): RefundTransactionResponse
    {
        return new RefundTransactionResponse($this->client->post("operations/$id/refund", ['partialAmount' => $partialAmount])->json());
    }
}