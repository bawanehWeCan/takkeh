<?php

namespace App\Repositorys;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class SliderRepository
{

    /**
     * @var Slider
     */
    protected $slider;

    /**
     * __construct function
     *
     * @param Slider $slider
     */
    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    /**
     * allSliders function
     *
     * @return Collection
     */
    public function allSliders()
    {
        $sliders = $this->slider->get();
        return $sliders;
    }

    /**
     * saveSlider function
     *
     * @param Array $data
     * @return void
     */
    public function saveSlider($data)
    {

        $slider = new $this->slider;
        $slider->image = $data['image'];
        $slider->save();

        return $slider->fresh();
    }

    /**
     * getSliderByID function
     *
     * @return Collection
     */
    public function getSliderByID($id)
    {
        $slider = $this->slider->where('id', $id)->firstOrFail();
        return $slider;
    }

    /**
     * deleteSlider function
     *
     * @return bool
     */
    public function deleteSlider($id)
    {
        Slider::destroy($id);
    }
}
