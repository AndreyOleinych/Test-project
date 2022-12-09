<?php

namespace App\Http\Controllers\Api\Variant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\VariantSizeArrayPresenter;
use App\Http\Requests\Api\Variant\AddSizeValidationRequest;
use App\Http\Requests\Api\Variant\UpdateSizeValidationRequest;
use App\Models\VariantSize;
use Illuminate\Http\JsonResponse;

class VariantSizeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  VariantSizeArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function index(VariantSizeArrayPresenter $presenter): JsonResponse
    {
        $sizes = VariantSize::all();

        return $this->successResponse($presenter->presentCollection($sizes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddSizeValidationRequest  $request
     * @param  VariantSizeArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function store(AddSizeValidationRequest $request,VariantSizeArrayPresenter $presenter): JsonResponse
    {
        $size = VariantSize::query()->create([
            'name' => $request->get('name'),
        ]);

        return $this->successResponse($presenter->present($size));
    }

    /**
     * Display the specified resource.
     *
     * @param  VariantSize  $size
     * @param  VariantSizeArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function show(VariantSize $size, VariantSizeArrayPresenter $presenter): JsonResponse
    {
        return $this->successResponse($presenter->present($size));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateSizeValidationRequest  $request
     * @param  VariantSize  $size
     * @param  VariantSizeArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function update(UpdateSizeValidationRequest $request, VariantSize $size, VariantSizeArrayPresenter $presenter): JsonResponse
    {
        $size->update([
            'name' => $request->get('name')
        ]);

        return $this->successResponse($presenter->present($size));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  VariantSize  $size
     *
     * @return JsonResponse
     */
    public function destroy(VariantSize $size): JsonResponse
    {
        $size->delete();

        return $this->emptyResponse();
    }
}
