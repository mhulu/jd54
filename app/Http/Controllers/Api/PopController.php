<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\PopFormRequest;
use App\Repository\Eloquent\PopRepo;
use App\Transformers\PopEditTransformer;
use App\Transformers\PopTransformer;
use Illuminate\Http\Request;
use Star\utils\StarJson;

class PopController extends ApiController
{
    protected $repo;
    public function __construct(PopRepo $repo)
    {
        parent::__construct();
        $this->repo = $repo;
        $this->middleware('permission:manage_pops');
    }

    public function index()
    {
        $pops = $this->repo->dataTableProvider();
        return $this->respondWithPaginator($pops, new PopTransformer());
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pop = $this->repo->find($id);
        return $this->respondWithItem($pop, new PopEditTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PopFormRequest $request, $id)
    {
        $input = $request->all();
        return $this->repo->update($id, $input);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            return StarJson::create(200, '成功删除用户');
        }
    }
}
