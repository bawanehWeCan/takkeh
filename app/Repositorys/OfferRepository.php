<?php

namespace App\Repositorys;

use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class OfferRepository
{

    /**
     * @var Offer
     */
    protected $offer;

    /**
     * __construct function
     *
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * allOffers function
     *
     * @return Collection
     */
    public function allOffers()
    {
        $offers = $this->offer->all();
        return $offers;
    }

    /**
     * saveOffer function
     *
     * @param Array $data
     * @return void
     */
    public function saveOffer($data)
    {

        $offer = $this->offer->create($data);

        return $offer->fresh();
    }

    /**
     * getOfferByID function
     *
     * @return Collection
     */
    public function getOfferByID($id)
    {
        $offer = $this->offer->where('id', $id)->firstOrFail();
        return $offer;
    }

    /**
     * deleteOffer function
     *
     * @return bool
     */
    public function deleteOffer($id)
    {
        Offer::destroy($id);
    }

}
