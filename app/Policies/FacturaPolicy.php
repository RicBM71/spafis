<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Factura;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacturaPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $authUser, Factura $factura)
    {
        if (!esAdmin()){
            return $this->deny('Permiso administrador requerido');
        }

        return true;
    }

    public function delete(User $authUser, Factura $factura)
    {
        if (!esAdmin()){
            return $this->deny('Permiso administrador requerido');
        }

        return true;
    }
}
