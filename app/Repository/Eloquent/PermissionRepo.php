<?php 
namespace App\Repository\Eloquent;

use Star\Permission\Models\Permission;

class PermissionRepo extends BaseRepository
{
   protected $model;
   public function __construct(Permission $permission)
   {
   		$this->model = $permission;
   }
}