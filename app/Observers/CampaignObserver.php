<?php

namespace App\Observers;

use App\Models\Campaign;

class CampaignObserver
{


    /**
     * Handle the Campaign "creating" event.
     */
    public function creating(Campaign $campaign): void
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';

            for ($i = 0; $i < 10; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (Campaign::where('invite_code', $code)->exists());

        $campaign->invite_code = $code;
    }


    /**
     * Handle the Campaign "created" event.
     */



    /**
     * Handle the Campaign "updated" event.
     */
    public function updated(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "deleted" event.
     */
    public function deleted(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "restored" event.
     */
    public function restored(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "force deleted" event.
     */
    public function forceDeleted(Campaign $campaign): void
    {
        //
    }
}
