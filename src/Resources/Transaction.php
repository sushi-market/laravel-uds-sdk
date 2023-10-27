<?php

namespace SushiMarket\UdsSdk\Resources;

use Illuminate\Support\Carbon;
use SushiMarket\UdsSdk\Enums\Action;
use SushiMarket\UdsSdk\Enums\ActionState;

class Transaction
{
    /** ID транзакции в базе UDS */
    public int $id;

    /** Дата транзакции */
    public Carbon $dateCreated;

    /** Тип транзакции */
    public Action $action;

    /** Статус транзакции */
    public ActionState $state;

    /** Информация о клиенте */
    public CustomerShortInfo $customer;

    /** Информация о сотруднике */
    public ?Cashier $cashier;

    /** Информация о филиале */
    public ?Branch $branch;

    /**
     * Количество бонусных баллов, которое будет списано с клиента после завершения транзакции.
     * Отрицательное значение говорит о списании, а положительное - о начислении бонусных баллов.
     */
    public float $points;

    /** Номер чека */
    public ?string $receiptNumber;

    /** Для сторнирующей транзакции - ссылка на оригинальную транзакцию */
    public ?Origin $origin;

    /** Общая сумма чека до применения скидок в денежных единицах */
    public float $total;

    /** Оплачиваемая сумма в денежных единицах */
    public float $cash;

    /**
     * @param array{
     *     id: int,
     *     dateCreated: string,
     *     action: string,
     *     state: string,
     *     customer: array{
     *         id: int,
     *         uid: string,
     *         displayName: string,
     *         membershipTier: ?array{
     *             uid: string,
     *             name: string,
     *             rate: float,
     *             maxScoresDiscount: ?string,
     *         }
     *     },
     *     cashier: array{
     *         id: int,
     *         displayName: string,
     *     },
     *     branch: array{
     *         id: int,
     *         displayName: string,
     *     },
     *     points: float,
     *     receiptNumber: ?string,
     *     origin: array{
     *         id: int,
     *         displayName: string,
     *     },
     *     total: float,
     *     cash: float,
     *  } $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->dateCreated = Carbon::parse($data['dateCreated']);
        $this->action = Action::from($data['action']);
        $this->state = ActionState::from($data['state']);
        $this->customer = new CustomerShortInfo($data['customer']);
        $this->cashier = isset($data['cashier']) ? new Cashier($data['cashier']) : null;
        $this->branch = isset($data['branch']) ? new Branch($data['branch']) : null;
        $this->points = $data['points'];
        $this->receiptNumber = $data['receiptNumber'] ?? null;
        $this->origin = isset($data['origin']) ? new Origin($data['origin']) : null;
        $this->total = $data['total'];
        $this->cash = $data['cash'];
    }
}