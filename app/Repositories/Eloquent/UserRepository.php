<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {}

    public function byId(int $id): User
    {
        $result = $this->model::where('id', $id)->whereNull('deleted_at')->firstOrFail();

        if ($result instanceof User) {
            return $result;
        }

        throw new Exception('The requested user could not be found');
    }

    public function byEmail(string $email): User
    {
        $result = $this->model::where('email', $email)->whereNull('deleted_at')->firstOrFail();

        if ($result instanceof User) {
            return $result;
        }

        throw new Exception('The requested user could not be found');
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
