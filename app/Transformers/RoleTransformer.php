<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Star\Permission\Models\Role;

class RoleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'label' => $role->label
        ];
    }
}
