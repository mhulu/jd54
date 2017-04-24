<?php

namespace App\Transformers;

use App\Unit;
use League\Fractal\TransformerAbstract;

class UnitTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Unit $unit)
    {
        return [
            'id' => $unit->id,
            'name' => $unit->name
        ];
    }
}
