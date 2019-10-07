<?php

namespace App\Http\Controllers\Api\Sold;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Model\Sold;
use App\Transformers\SoldTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SoldController extends ApiController
{

    private $soldTransformer;


    public function __construct(
        SoldTransformer $soldTransformer
    )
    {
        $this->soldTransformer = $soldTransformer;
    }

    //Validation
    private function validation()
    {

        if (!request()->name || !request()->price || request()->status == null || !in_array(request()->status, [0, 1])) {
            return $this
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError('Parameter failed validation for a product.');
        } else {
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
       // $this->authorize('isAdmin');

        $sold = Sold::with('user');
        $sold->when($request->has('user'),function (\Illuminate\Database\Eloquent\Builder $q) use($request){
            $q->whereHas('user',function (\Illuminate\Database\Eloquent\Builder $q) use($request){
                $q->where('user',$request->user);
            });
        });
        $sold->when($request->has('product_name'),function (\Illuminate\Database\Eloquent\Builder $q) use($request){
            $q->where('product_name', 'like', '%' . request()->product_name . '%');
        }) ;
        $sold = $sold->paginate($this->limit());
         return $this->respondWithPagination($sold,
             $this->soldTransformer->transformCollection($sold->items())
             );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $this->authorize('isAdmin');

        if ($this->validation()){
            return $this->validation();
        }
        Sold::with([])->create([
            'product_name'=>$request->product_name,
            'weight'=>$request->weight,
            'price'=>$request->price,
            'user_id'=>$request->user_id
        ]);
        return $this->responseCreated(' با موفقیت اضافه شد');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
//        $this->authorize('isAdmin');

        if ($this->validation()){
            return $this->validation();
        }
        $sold = Sold::with([])->find($id);
        if (!$sold){
            return $this->responseNotFound('این محصول وجود ندارد');
        }
        $sold->update([
            'product_name'=>$request->product_name,
            'weight'=>$request->weight,
            'price'=>$request->price,
        ]);
        return $this->responseUpdate('محصول با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy($id)
    {
//        $this->authorize('isAdmin');

        $sold = Sold::with([])->find($id);
        if (!$sold){
            return $this->responseNotFound('این محصول وجود ندارد');
        }
        $sold->delete();
        return $this->responseDeteted();
    }
}
