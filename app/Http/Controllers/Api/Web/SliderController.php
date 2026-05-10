<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Slider;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource; // Import Resource dari folder yang benar
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1. Ambil data slider dari database
        $sliders = Slider::latest()->get();

        // 2. Kirim ke SliderResource (Pastikan file SliderResource sudah ada di App\Http\Resources)
        // Kita kirim 3 parameter: status, message, dan data
        return new SliderResource(true, 'List Data Sliders', $sliders);
    }
}
