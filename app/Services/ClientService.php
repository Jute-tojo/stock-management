<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientService
{
    public function list(?string $keyword = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->applySearch(Client::query(), $keyword)
            ->latest()
            ->paginate($perPage);
    }

    private function applySearch(Builder $query, ?string $keyword): Builder
    {
        if ($keyword) {
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        return $query;
    }

    public function store(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);

        return $client;
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }
}
