<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\CustomUser;

class CustomUserProvider implements UserProvider
{
    /**
     * Create a new custom user provider.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable  $model
     * @return void
     */
  protected $model;

    public function __construct()
    {
        $this->model = CustomUser::class;
    }

    // You can add custom methods or overrides here if needed.
}
