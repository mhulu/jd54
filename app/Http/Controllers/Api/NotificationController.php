<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\NotificationsRepo;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Star\utils\StarJson;

class NotificationController extends Controller
{
    protected $notification;
    protected $user;

    public function __construct(NotificationsRepo $notification)
    {
        $this->notification = $notification;
        // $this->middleware('auth.user');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = request()->all();
        $user = auth()->user();
        $notifications = $user->notifications()->paginate($request['per_page']);
        $unreadNotifications = $user->unreadNotifications->take(50)->map( function ($item) {
            return [
                'type' => $item->notifiable_type,
                'data' => $item->data
            ];
        });
        return collect($this->notification->transform($notifications))->merge(['unreadNotifications' => $unreadNotifications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * 将消息设置为已读
     */
    public function mark()
    {
        $ids = request()->all()['ids'];
        $user = auth()->user();
        $mark = $user->notifications()->where('id', $ids)->update(['read_at' => Carbon::now()]);
        if (!$mark) {
            return StarJson::create(403, '操作失败');
        }
    }

    public function delete()
    {
        $ids = request()->all()['ids'];
        $user = auth()->user();
        $delete = $user->notifications()->where('id', $ids)->delete();
        if ($delete) {
            return StarJson::create(200, '成功将'.$delete.'条记录设为删除');
        }
        return StarJson::create(403, '操作失败');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        $user = auth()->user();
        if ($user->notifications()->delete()) {
            return StarJson::create(200);
        }
        return StarJson::create(403, '操作失败');
    }
}
