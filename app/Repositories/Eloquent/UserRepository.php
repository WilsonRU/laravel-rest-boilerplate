<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {}

    public function byId(int $id): User
    {
        return $this->model::where('id', $id)->whereNull('deleted_at')->first();
    }

    public function byEmail(string $email): User
    {
        return $this->model::where('email', $email)->whereNull('deleted_at')->first();
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): User
    {
        $model = $this->byId($id);
        $model->update($data);

        return $model;
    }

    public function delete($id): void
    {
        $model = $this->byId($id);
        $model->delete();
    }
}
