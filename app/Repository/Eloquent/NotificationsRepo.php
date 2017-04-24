<?php
namespace App\Repository\Eloquent;

use App\User;
use Star\utils\StarJson;

/**
* 用来生成用户历史事件
*/
class NotificationsRepo
{
	protected $user;
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * 从通知提醒中提取6条最新记录
	 */
	public function timeline()
	{
		$notifications = $this->user->notifications->take(6);
		if ($notifications->isEmpty()) {
			return;
		}
		$data = $notifications->map( function ($item) {
			return [
				'id' => $item->id,
				'created_at' => $item->created_at->format('Y-m-d'),
				'time' => $item->created_at->format('H:i'),
				'title' => $item->data['title'],
				'content' => empty($item->data['content']) ? '' : $item->data['content'],
				'type' => strtolower(substr(strrchr($item->notifiable_type, '\\'), 1)),
				'color' => $item->data['type'],
			];
		});
		return $data->groupBy('created_at');
	}

	public function transform($notifications)
	{
		$result = $notifications->map(function($item) {
		    return [
		        'type' => $this->typeTrans($item->notifiable_type),
		        'data' => $item->data,
		        'read_at' => (bool) $item->read_at != null,
		        // 'title' => $item->data['title'],
		        'id' => $item->id,
		        // 'content' => empty($item->data['content']) ? null : $item->data['content'],
		        'created_at' => $item->created_at->format('Y-m-d H:i:s')
		    ];
		});
		return [
		    'total_pages' => $notifications->lastPage(),
		    'current_page' => $notifications->currentPage(),
		    'total' => $notifications->total(),
		    'per_page' => intval(($notifications->perPage())),
		    'data' => $result->toArray()
		];
	}

	public function delete($id)
	{
		$notification = $this->user->notifications()->where('id', $id)->first();
		if ($notification) {
			$notification->delete();
			return StarJson::create(200, '记录成功删除');
		} else {
			return StarJson::create(404);
		}
	}

	private function typeTrans ($type) 
	{
		if ($type == 'App\IM') {
			return '用户';
		}
		return '系统';
	}

}