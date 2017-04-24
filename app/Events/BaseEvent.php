<?php
namespace App\Events;
use Illuminate\Database\Eloquent\Model;
abstract class BaseEvent
{
    protected $model;
    protected $action;
    public $modelName;
    public $notification;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->modelName = $this->getModelName();
    }

    /**
     * 自动拼接的Notification类名格式为 ModelOnAction
     * @return string
     */
    public function getNotification()
    {
        $classPath = 'App\\Notifications\\';
        return $classPath.studly_case($this->modelName. '_on_' .$this->action);
    }
    /**
     * Listener类需要调用Model
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
    protected function getModelName()
    {
        return class_basename($this->model);
    }
}