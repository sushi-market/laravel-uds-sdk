<?php

declare(strict_types=1);

namespace SushiMarket\UdsSdk\Resources;

use Illuminate\Support\Carbon;
use SushiMarket\UdsSdk\Enums\Gender;

class CustomerDetail
{
    /** Идентификатор клиента в UDS */
    public ?string $uid;

    /** URL изображения клиента */
    public ?string $avatar;

    /** Имя клиента */
    public ?string $displayName;

    /** Пол */
    public ?Gender $gender;

    /** Номер телефона клиента */
    public ?string $phone;

    /** Дата рождения клиента */
    public ?Carbon $birthday;

    /** Информация о клиенте */
    public ?Participant $participant;

    /** Источник трафика */
    public ?string $channelName;

    /** Email клиента */
    public ?string $email;

    /** Список тегов клиента */
    public array $tags = [];

    public function __construct(array $data)
    {
        $this->uid = $data['uid'] ?? null;
        $this->avatar = $data['avatar'] ?? null;
        $this->displayName = $data['displayName'] ?? null;
        $this->gender = isset($data['gender']) ? Gender::from($data['gender']) : null;
        $this->phone = $data['phone'] ?? null;
        $this->birthday = isset($data['birthday']) ? Carbon::parse($data['birthday']) : null;
        $this->participant = new Participant($data['participant']) ?? null;
        $this->channelName = $data['channelName'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->tags = array_map(fn ($tag) => new Tag($tag), $data['tags']);
    }
}