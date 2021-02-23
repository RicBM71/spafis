<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Paciente;
use Illuminate\Auth\Access\HandlesAuthorization;

class PacientePolicy
{
    use HandlesAuthorization;


// esto se ejecuta antes de cualquier método
public function before($authUser)
{

    // if($authUser->hasRole('Admin')){
    //     return true;
    // }

}


    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $authUser, Paciente $paciente)
    {

        return $authUser->hasPermissionTo('contact') ?: $this->deny("Acceso denegado. No dispone de permisos necesarios");

    }

    public function create(User $authUser, Paciente $paciente)
    {

        return esSupervisor() ?: $this->deny("Acceso denegado. Permiso root requerido");

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $authUser, Paciente $paciente)
    {

        //return $authUser->hasRole('Supervisor') ?: $this->deny("Acceso denegado. No puedes editar Pacientes");
        //return $authUser->hasPermissionTo('Edita Pacientes') ?: $this->deny("Acceso denegado. No puedes editar Pacientes");
        return true;

    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $authUser, Paciente $paciente)
    {

        return $authUser->hasRole('Admin') ?: $this->deny("Acceso denegado, solo un administrador puede borrar Pacientes");

    }
}
