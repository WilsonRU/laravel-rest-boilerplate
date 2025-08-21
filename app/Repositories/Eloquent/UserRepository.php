<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {}

    public function byId(int $id): User
    {
        /** @var User $user */
        $user = $this->model::where('id', $id)->whereNull('deleted_at')->firstOrFail();

        return $user;
    }

    public function byEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = $this->model::where('email', $email)->whereNull('deleted_at')->first();

        return $user;
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): User
    {
        $model = $this->byId($id);
        $model->update($data);

        return $model;
    }

    public function delete(int $id): void
    {
        $model = $this->byId($id);
        $model->delete();
    }
}
