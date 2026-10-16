<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Cafe;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait ScopesToOwner
{
    // Admin melihat semua cafe, penjual hanya cafe miliknya
    protected function cafesFor(User $user): Builder
    {
        return Cafe::query()->when(!$user->isAdmin(), fn ($q) => $q->where('user_id', $user->id));
    }

    protected function authorizeCafe(User $user, Cafe $cafe): void
    {
        abort_unless($user->ownsCafe($cafe), 403);
    }
}
