<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CitaScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {

            $e = session();

            $empresa_id =  session()->get('empresa')->id;
            $facultativo_id = session()->get('facultativo_id');

            $builder->where('empresa_id', '=', $empresa_id);

            // mala idea esto aquí

            // if ($facultativo_id > 0)
            //     $builder->where('facultativo_id', '=', $facultativo_id);



    }
}
