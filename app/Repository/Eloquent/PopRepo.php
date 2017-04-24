<?php 
namespace App\Repository\Eloquent;

use App\Pop;

class PopRepo extends BaseRepository
{
   protected $model;

   // 定义字段用于前台vuetable检索
   protected $fieldSearchable = [
     'name', 'phone', 'identify'
   ];
   public function __construct(Pop $pop)
   {
   		$this->model = $pop;
   }
}