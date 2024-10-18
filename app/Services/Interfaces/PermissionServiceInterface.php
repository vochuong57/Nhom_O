<?php

namespace App\Services\Interfaces;

/**
 * Interface PermissionServiceInterface
 * @package App\Services\Interfaces
 */
interface PermissionServiceInterface
{
    public function paginate($request);
    public function createPermission($request);
    public function updatePermission($id, $request);
    public function deletePermission($id);
}
