<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class TimelineTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($collect)
    {
        return [
            'date' => $collect->created_at->format('Y-m-d'),
            'events' => $collect
        ];
    }
}
