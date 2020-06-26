<?php


namespace App\Modules\Realtime\Services;


/**
 * Interface UserRealtimeDependencyServiceContract
 * @package App\Modules\Realtime\Services
 */
interface UserRealtimeDependencyServiceContract
{
    /**
     * @param array $chatsIds
     * @return bool|null
     */
    public function addLoggedUserAsListener(array $chatsIds): ?bool;

    /**
     * @return bool|null
     */
    public function removeLoggedUserFromListeners(): ?bool;
}
