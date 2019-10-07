<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\Status;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Response;

class ProductController extends ApiController
{


    private $productTransformer;

    public function __construct(
        ProductTransformer $productTransformer
    )
    {
        $this->productTransformer = $productTransformer;
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
        //$this->authorize('isAdmin');
        $products = Product::with('status');
        $products->when($request->has('status'), function (\Illuminate\Database\Eloquent\Builder $q) use ($request) {
            $q->whereHas('status', function (\Illuminate\Database\Eloquent\Builder $q) use ($request) {
                $q->where('status', $request->status);
            });
        });
        $products->when($request->has('name'), function (\Illuminate\Database\Eloquent\Builder $q) use ($request) {
            $q->where('name', 'like', '%' . request()->name . '%');
        });
        $products = $products->paginate($this->limit());

        return $this->respondWithPagination($products,
            $this->productTransformer->transformCollection($products->items())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        //        $this->authorize('isAdmin');
        if ($this->validation()) {
            return $this->validation();
        }

        $product = Product::with([])->create([
            'name'=>$request->name,
            'price'=>$request->price,
        ]);
        $product->status()->insert([
            'status'=>$request->status,
            'product_id'=>$product->id,
        ]);
        return $this->responseCreated('ثبت محصول موفقیت امیز بود');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //        $this->authorize('isAdmin');
        if ($this->validation()){
            return $this->validation();
        }

        $product = Product::with([])->find($id);
        if (!$product){
            return $this->responseNotFound('این محصول وجود ندارد');
        }
         $product->update([
             'name'=>$request->name,
             'price'=>$request->price
         ]);
        $product->status()->update([
            'status'=>$request->status,
            'product_id'=>$product->id,
        ]);

        return $this->responseCreated('آپدیت محصول موفقیت آمیز بود');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //        $this->authorize('isAdmin');
        $product = Product::with([])->find($id);

        if (!$product){
            return $this->responseNotFound('این محصول وجود ندارد');
        }
        $product->delete();
        return $this->responseDeteted();
    }
}
