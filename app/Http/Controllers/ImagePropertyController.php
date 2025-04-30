<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageProperty;
use Illuminate\Support\Facades\File;

class ImagePropertyController extends Controller
{
    public function edit(int $id)
    {
        $images = ImageProperty::where('property_id', $id)->get();
        return view('landlord.property.image', compact('images'));
    }

    public function destroy(int $id){
        $propertyImage = ImageProperty::findOrFail($id);
        if(File::exists($propertyImage->image)){
            File::delete($propertyImage->image);
        }
        $propertyImage->Delete();

        return back()->with('status','Image Deleted');

    }
}
