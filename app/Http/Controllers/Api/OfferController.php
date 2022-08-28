<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\OfferResource;
use App\Repositorys\OfferRepository;
use App\Traits\ResponseTrait;

class OfferController extends Controller
{

    use ResponseTrait;

    /**
     * @var OfferRepositry
     */
    protected OfferRepository $offerRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OfferRepository $offerRepositry)
    {
        $this->offerRepositry =  $offerRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $categories = $this->offerRepositry->allOffers();
        return $this->returnData('Offers', OfferResource::collection($categories), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param OfferRequest $request
     * @return void
     */
    public function store(OfferRequest $request)
    {
        $offer = $this->offerRepositry->saveOffer($request);

        if ($offer) {
            return $this->returnData('Offer', OfferResource::make($offer), __('Offer created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create Offer!'));
    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $offer = $this->offerRepositry->getOfferByID($id);

        if ($offer) {
            return $this->returnData('Offer', OfferResource::make($offer), __('Get Offer succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get Offer!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->offerRepositry->deleteOffer($id);

        return $this->returnSuccessMessage(__('Delete Offer succesfully!'));
    }


}
