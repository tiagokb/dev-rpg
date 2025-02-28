<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CampaignPolicy
{
    public function join(User $user, Campaign $campaign): Response

    {

        if ($user->id === $campaign->user_id) {
            return Response::deny('Você é o mestre desta campanha.');
        }

        if ($campaign->players()->count() >= Campaign::max_players) {
            return Response::deny('Limite de jogadores atingido.');
        }


        return $campaign->players->contains($user)
            ? Response::deny('Você já está participando desta campanha.')
            : Response::allow('Seja bem vindo');
    }

    public function limit(User $user, Campaign $campaign): Response
    {
        return $campaign->players->count() >= $campaign->max_players
            ? Response::deny('Limite de jogadores atingido.')
            : Response::allow('Você entrou em uma campanha');
    }

    public function leave(User $user, Campaign $campaign): Response
    {
        if ($user->id === $campaign->user_id) {
            return Response::deny('Você não pode sair de uma campanha que você é o mestre.');
        }

        return $campaign->players->contains($user)
            ? Response::allow('Você saiu da campanha')
            : Response::deny('Você não está participando desta campanha.');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->user_id || $campaign->players->contains($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campaign $campaign): Response
    {
        return $user->id !== $campaign->user_id
            ? Response::deny('Você não é o mestre desta campanha.')
            : Response::allow('Atualizado com sucesso');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Campaign $campaign): Response
    {
        return  $user->id === $campaign->user_id
            ? Response::allow('Campanha deletada com sucesso')
            : Response::deny('Você não é o mestre desta campanha.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return false;
    }

    public function transfer(User $user, Campaign $campaign): Response
    {

        return $user->id === $campaign->user_id
            ? Response::allow('Campanha transferida com sucesso')
            : Response::deny('Você não é o mestre desta campanha.');
    }
}
