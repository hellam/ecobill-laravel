<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use function App\CentralLogics\get_active_branch;
use function App\CentralLogics\get_user_ref;

class BranchScope implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('branch_id',get_active_branch());
    }
}