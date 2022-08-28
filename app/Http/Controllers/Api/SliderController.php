<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\SliderRequest;
use App\Http\Resources\SliderResource;
use App\Repositorys\SliderRepository;
use App\Traits\ResponseTrait;

class SliderController extends Controller
{

    use ResponseTrait;

    /**
     * @var SliderRepositry
     */
    protected SliderRepository $sliderRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SliderRepository $sliderRepositry)
    {
        $this->sliderRepositry =  $sliderRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $categories = $this->sliderRepositry->allSliders();
        return $this->returnData('Sliders', SliderResource::collection($categories), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param SliderRequest $request
     * @return void
     */
    public function store(SliderRequest $request)
    {
        $slider = $this->sliderRepositry->saveSlider($request);

        if ($slider) {
            return $this->returnData('Slider', SliderResource::make($slider), __('Slider created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create Slider!'));
    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $slider = $this->sliderRepositry->getSliderByID($id);

        if ($slider) {
            return $this->returnData('Slider', SliderResource::make($slider), __('Get Slider succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get Slider!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->sliderRepositry->deleteSlider($id);

        return $this->returnSuccessMessage(__('Delete Slider succesfully!'));
    }


}
