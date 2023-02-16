<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

class EndPoint
{
    public function host(): string
    {
        return 'https://jsonplaceholder.typicode.com';
    }

    public function list(): array
    {
        return [
            'type' => 'GET',
            'url' => $this->host() . '/users',
        ];
    }

    public function single(int $id): array
    {
        return [
            'type' => 'GET',
            'url' => $this->host() . '/users/' . $id,
        ];
    }
}
