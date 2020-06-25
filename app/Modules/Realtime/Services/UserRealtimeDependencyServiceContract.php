<?php


namespace App\Modules\Realtime\Services;


/**
 * Interface UserRealtimeDependencyServiceContract
 * @package App\Modules\Realtime\Services
 */
interface UserRealtimeDependencyServiceContract
{
    /**
     * @param array $usersIds
     * @return bool|null
     */
    public function addLoggedUserAsListener(array $usersIds): ?bool;

    /**
     * @return bool|null
     */
    public function removeLoggedUserFromListeners(): ?bool;
}
