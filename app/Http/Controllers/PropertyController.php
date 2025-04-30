<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\ImageProperty;
use App\Models\LocationProperty;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{

    //Landlord
    public function lview(){
        $properties = Property::where('landlord_id', Auth::guard('landlord')->user()->id)
            ->where('status', '!=', 5)
            ->get();
        return view('landlord.LProperty', compact('properties'));
    }

    public function create(){
        return view('landlord.property.create');
    }



    public function store(Request $request){


        // Validate request data
        $minImageCount = 8;
        $images = $request->file('images');
        $pattern = '/https:\/\/(www\.)?google\.com\/maps\/.*@(-?\d+\.\d+),(-?\d+\.\d+)/';


        // Begin validation
        $request->validate([
            'state' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'address' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\s\.\(\)\-]+$/',
            ],
            'deposit' => 'required|numeric|max:5250',
            'monthly' => 'required|numeric|max:1500',
            'room' => [
                'required',
                'integer',
                'max:8'
            ],
            'type' => 'required',
            'area' => [
                'required',
                'digits_between:1,4',
                'integer',
                'max:2600'
            ],
            'description' => ['required', function ($attribute, $value, $fail) {
                $formattedDescription = ucwords(strtolower($value));
                $formattedDescription = str_replace(',', ', ', $formattedDescription);
                if (!preg_match('/^[A-Za-z, ]*$/', $formattedDescription)) {
                    $fail("The $attribute format is invalid.");
                }
            }],
            'title' => ['required', 'regex:/^[\w\s()]+$/'],
            'toilet' => 'required',
            'google_maps_link' => [
        'required',
        'url',
        function($attribute, $value, $fail) use ($pattern) {
            // Regex that checks for latitude and longitude in a broader set of Google Maps URLs
            $pattern = '/https:\/\/(www\.)?google\.com\/maps\/.*@(-?\d+\.\d+),(-?\d+\.\d+)/';
            if (!preg_match($pattern, $value)) {
                $fail("The {$attribute} must be a valid Google Maps URL with latitude and longitude.");
            }
        }
    ],
            // Additional rule for images count
            'images' => ['required', function ($attribute, $value, $fail) use ($images, $minImageCount) {
                if (count($images) < $minImageCount) {
                    $fail("You must upload at least {$minImageCount} images.");
                }
            }],
            'images.*' => 'image|mimes:jpg,jpeg,png,webp', // Not making it required here, because it's handled by the 'images' rule
        ]);

        $landlordId = Auth::guard('landlord')->user()->id;

        $state = '';
        $state = ucwords(str_replace('_', ' ', $request->state));

        $address = $request->address . ', ' . $request->postcode . ' ' . $request->city . ', ' . $state;
        $description = $request->title . ', ' . $request->room . ' rooms, ' . $request->toilet . ' toilet, ' . $request->area . 'sqft, ' . $request->description;

        // Execute the stored procedure
        $result = DB::select('CALL InsertProperty(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $landlordId, null, null, $address, $request->type, 3, 2, $request->deposit, $request->monthly, $description
        ]);

        $propertyId = $result[0]->propertyId;

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imageData = [];
            $files = $request->file('images');

            foreach ($files as $key => $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = $key . '-' . time() . '.' . $extension;
                $path = "uploads/properties/";
                $file->move($path, $filename);

                $imageData[] = [
                    'property_id' => $propertyId,
                    'image' => $path . $filename,
                ];
            }

            // Insert image data into the database
            ImageProperty::insert($imageData);

            // Update property status to '3' since the validation ensures we have at least 8 images
            DB::table('properties')->where('id', $propertyId)->update(['status' => 3]);
        }

        // Extract latitude and longitude from Google Maps link
        $googleMapsLink = $request->google_maps_link;
        $regex = '/@(-?\d+\.\d+),(-?\d+\.\d+)/';
        preg_match($regex, $googleMapsLink, $matches);

        if (count($matches) >= 3) {
            $latitude = $matches[1];
            $longitude = $matches[2];

            // Save the location property
            LocationProperty::create([
                'property_id' => $propertyId,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);
        }

        return back()->with('status', 'New Property Created');
    }



    public function edit(int $id)
    {
        $property = Property::findOrFail($id);

        $address = $property->address;
        $addressParts = explode(", ", $address);

        // Assigning values to fields
        $address = isset($addressParts[0]) ? $addressParts[0] : ''; // No.82 Taman Bukit Bintang
        $postcodeCity = isset($addressParts[1]) ? $addressParts[1] : ''; // 02310 Tanah Merah
        $state = '';

        // Separate the postcode and city
        $postcodeCityParts = explode(" ", $postcodeCity, 2);
        $postcode = isset($postcodeCityParts[0]) ? $postcodeCityParts[0] : ''; // 02310
        $city = isset($postcodeCityParts[1]) ? $postcodeCityParts[1] : ''; // Tanah Merah

        // Extract state from the last part of the addressParts array
        if (count($addressParts) > 2) {
            $state = end($addressParts); // Get the last element
        }

        // Trim state to remove any leading or trailing whitespace
        $state = trim($state);
        $state = strtolower(str_replace(' ', '_', $state));

        // Fetch additional data from the database
        $deposit = $property->deposit;
        $monthly = $property->monthly;
        $types = $property->type;

        // Extract rooms, area, and description from the description column
        $description = $property->description;

        // Use regular expressions to extract title, rooms, toilet count, and area
        preg_match('/^(.+?),\s*(\d+)\s*rooms?,\s*(\d+)\s*toilets?,\s*(\d+)\s*sqr?ft,\s*(.+)$/i', $description, $matches);

        $name = isset($matches[1]) ? $matches[1] : '';
        $rooms = isset($matches[2]) ? $matches[2] : '';
        $toilet = isset($matches[3]) ? $matches[3] : '';
        $area = isset($matches[4]) ? $matches[4] : '';
        $description = isset($matches[5]) ? $matches[5] : '';

        $type = '';
        $value = '';
        if ($types == 1) {
            $type = 'Bungalow/Villa';
            $value = '1';
        } elseif ($types == 2) {
            $type = 'Semi-D';
            $value = '2';
        } elseif ($types == 3) {
            $type = 'Terrace';
            $value = '3';
        } elseif ($types == 4) {
            $type = 'Townhouse';
            $value = '4';
        } elseif ($types == 5) {
            $type = 'Flat/Apartment';
            $value = '5';
        } elseif ($types == 6) {
            $type = 'Condominium';
            $value = '6';
        } elseif ($types == 7) {
            $type = 'Penthouse';
            $value = '7';
        } else {
            $type = 'Shop House';
            $value = '8';
        }

        $images = ImageProperty::where('property_id', $id)->get();

        return view('landlord.property.edit', [
            'property' => $property,
            'state' => $state,
            'postcode' => $postcode,
            'city' => $city,
            'address' => $address,
            'deposit' => $deposit,
            'monthly' => $monthly,
            'name' => $name,
            'toilet' => $toilet,
            'rooms' => $rooms,
            'area' => $area,
            'description' => $description,
            'type' => $type,
            'room' => $rooms,
            'value' => $value,
            'images' => $images,
        ]);
    }



    public function update(Request $request, int $id){
        $request->validate([
            'state' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'address' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\s\.\(\)\-]+$/',
            ],
            'deposit' => 'required|numeric|max:5250',
            'monthly' => 'required|numeric|max:1500',
            'room' => [
                'required',
                'integer',
                'max:8'
            ],
            'type' => 'required',
            'area' => [
                'required',
                'digits_between:1,4',
                'integer',
                'max:2600'
            ],

            'description' => ['nullable', function ($attribute, $value, $fail) {
                $formattedDescription = ucwords(strtolower($value));
                $formattedDescription = str_replace(',', ', ', $formattedDescription);
                if (!preg_match('/^[A-Za-z, ]*$/', $formattedDescription)) {
                    $fail("The $attribute format is invalid.");
                }
            }],
            'title' => ['required', 'regex:/^[\w\s()]+$/'],
            'toilet' => 'required',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'google_maps_link' => 'nullable|url'
        ]);

        $state = '';
        $state = ucwords(str_replace('_', ' ', $request->state));

        $address = $request->address . ', ' . $request->postcode . ' ' . $request->city . ', ' . $state;
        $description = $request->title . ', ' . $request->room . ' rooms, ' . $request->toilet . ' toilet, ' . $request->area . 'sqrft, ' . $request->description;
        $landlordId = Auth::guard('landlord')->user()->id;

        // Execute the stored procedure to update the property
        DB::select('CALL UpdateProperty(?, ?, ?, ?, ?, ?, ?)', [
            $id, $landlordId, $address, $request->type, $request->deposit, $request->monthly, $description
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imageData = [];
            if ($files = $request->file('images')) {
                foreach ($files as $key => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $key . '-' . time() . '.' . $extension;

                    $path = "uploads/properties/";

                    $file->move($path, $filename);

                    $imageData[] = [
                        'property_id' => $id,
                        'image' => $path . $filename,
                    ];
                }
            }
            ImageProperty::insert($imageData);
            DB::table('properties')->where('id', $id)->update(['status' => 3]);
        }

        // Check and update the location
        if (!empty($request->google_maps_link)) {
            // Extract coordinates from the Google Maps link
            preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $request->google_maps_link, $matches);

            if (isset($matches[1]) && isset($matches[2])) {
                $latitude = $matches[1];
                $longitude = $matches[2];

                // Find the existing location or create a new one
                $location = LocationProperty::firstOrNew(['property_id' => $id]);
                $location->latitude = $latitude;
                $location->longitude = $longitude;
                $location->save();
            }
        }

        return back()->with('status', 'Property Updated');
    }



    public function destroy(int $id){
        $property = Property::findOrFail($id);

        // Check if the property ID exists in the contracts table
        $contractExists = Contract::where('property_id', $id)->exists();

        if ($contractExists) {
            // If the property is found in the contracts table, update its status to 2
            $property->status = 5;
            $property->save();
            return back()->with('status', 'Property deleted succesfully.');
        } else {
            // If the property is not found in the contracts table, delete related data and the property itself
            Appointment::where('property_id', $id)->delete();
            ImageProperty::where('property_id', $id)->delete();
            LocationProperty::where('property_id', $id)->delete();

            $property->delete();
            return back()->with('status', 'Property deleted successfully.');
        }
    }



    public function view(int $id){
        $images = ImageProperty::where('property_id', $id)->get();
        $property = Property::with(['contracts' => function ($query) {
            $query->where('status', '!=', 3)
                ->where('status', '!=', 4)
                ->with('tenant');
        }, 'staff'])->findOrFail($id);

        // Fetch location property
        $locationProperty = LocationProperty::where('property_id', $id)->firstOrFail();

        return view('landlord.property.view', compact('property', 'images', 'locationProperty'));
    }


    //Agent
    public function aview(){
        $agentId = Auth::guard('staff')->user()->id;
        $properties = Property::where('agent_id', $agentId)
                            ->whereNotIn('status', [3, 4, 5])
                            ->get();

        return view('agent.property.AProperty', compact('properties'));
    }

    public function agentview(int $id){
        $images = ImageProperty::where('property_id', $id)->get();
        $property = Property::findOrFail($id);
        $contracts = Contract::with(['tenant', 'agent'])
            ->where('property_id', $id)
            ->where(function ($query) {
                $query->where('status', 1);
            })->get();

        $locationProperty = LocationProperty::where('property_id', $id)->firstOrFail();
        return view('agent.property.view', compact('property', 'images', 'contracts', 'locationProperty'));
    }

    //Staff
    public function sview()
    {
        $agents = Staff::where('role', 2)->get();
        $staffs = Staff::where('role', 1)->get();

        // Fetch properties sorted by the latest creation date and specific statuses, excluding statuses 4 and 5
        $properties = Property::with('landlord', 'agent', 'staff')
            ->whereNotIn('status', [4, 5])
            ->orderByRaw("FIELD(status, 3, 2, 1)")
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('staff.SProperty', compact('properties', 'agents', 'staffs'));
    }




    public function staffview(int $id){
        $images = ImageProperty::where('property_id', $id)->get();
        $property = Property::findOrFail($id);
        $contracts = Contract::with(['tenant', 'agent'])
            ->where('property_id', $id)
            ->where('status', 1)
            ->get();

        $payments = Payment::whereIn('contract_id', $contracts->pluck('id'))->get();

        // Fetch location property
        $locationProperty = LocationProperty::where('property_id', $id)->first();

        return view('staff.property.view', compact('property', 'images', 'contracts', 'payments', 'locationProperty'));
    }


    public function staffedit(int $id){
        $property = Property::findOrFail($id);

        $address = $property->address;
        $addressParts = explode(", ", $address);

        // Assigning values to fields
        $address = isset($addressParts[0]) ? $addressParts[0] : ''; // No.82 Taman Bukit Bintang
        $postcodeCity = isset($addressParts[1]) ? $addressParts[1] : ''; // 02310 Tanah Merah
        $state = '';

        // Separate the postcode and city
        $postcodeCityParts = explode(" ", $postcodeCity, 2);
        $postcode = isset($postcodeCityParts[0]) ? $postcodeCityParts[0] : ''; // 02310
        $city = isset($postcodeCityParts[1]) ? $postcodeCityParts[1] : ''; // Tanah Merah

        // Extract state from the last part of the addressParts array
        if (count($addressParts) > 2) {
            $state = end($addressParts); // Get the last element
        }

        // Trim state to remove any leading or trailing whitespace
        $state = trim($state);
        $state = strtolower(str_replace(' ', '_', $state));

        // Fetch additional data from the database
        $deposit = $property->deposit;
        $monthly = $property->monthly;
        $types = $property->type;

        // Extract rooms, area, and description from the description column
        $description = $property->description;

        // Use regular expressions to extract title, rooms, toilet count, and area
        preg_match('/^(.+?),\s*(\d+)\s*rooms?,\s*(\d+)\s*toilets?,\s*(\d+)\s*sqr?ft,\s*(.+)$/i', $description, $matches);

        $name = isset($matches[1]) ? $matches[1] : '';
        $rooms = isset($matches[2]) ? $matches[2] : '';
        $toilet = isset($matches[3]) ? $matches[3] : '';
        $area = isset($matches[4]) ? $matches[4] : '';
        $description = isset($matches[5]) ? $matches[5] : '';

        $type = '';
        $value = '';
        if ($types == 1) {
            $type = 'Bungalow/Villa';
            $value = '1';
        } elseif ($types == 2) {
            $type = 'Semi-D';
            $value = '2';
        } elseif ($types == 3) {
            $type = 'Terrace';
            $value = '3';
        } elseif ($types == 4) {
            $type = 'Townhouse';
            $value = '4';
        } elseif ($types == 5) {
            $type = 'Flat/Apartment';
            $value = '5';
        } elseif ($types == 6) {
            $type = 'Condominium';
            $value = '6';
        } elseif ($types == 7) {
            $type = 'Penthouse';
            $value = '7';
        } else {
            $type = 'Shop House';
            $value = '8';
        }

        $images = ImageProperty::where('property_id', $id)->get();

        return view('staff.property.edit', [
            'property' => $property,
            'state' => $state,
            'postcode' => $postcode,
            'city' => $city,
            'address' => $address,
            'deposit' => $deposit,
            'monthly' => $monthly,
            'name' => $name,
            'toilet' => $toilet,
            'rooms' => $rooms,
            'area' => $area,
            'description' => $description,
            'type' => $type,
            'room' => $rooms,
            'value' => $value,
            'images' => $images,
        ]);
    }

    public function staffupdate(Request $request, int $id){
        $request->validate([
            'state' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'address' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\s\.\(\)\-]+$/',
            ],
            'deposit' => 'required|numeric|max:5250',
            'monthly' => 'required|numeric|max:1500',
            'room' => [
                'required',
                'integer',
                'max:8'
            ],
            'type' => 'required',
            'area' => [
                'required',
                'digits_between:1,4',
                'integer',
                'max:2600'
            ],

            'description' => ['nullable', function ($attribute, $value, $fail) {
                $formattedDescription = ucwords(strtolower($value));
                $formattedDescription = str_replace(',', ', ', $formattedDescription);
                if (!preg_match('/^[A-Za-z, ]*$/', $formattedDescription)) {
                    $fail("The $attribute format is invalid.");
                }
            }],
            'title' => ['required', 'regex:/^[\w\s()]+$/'], // Validate title with letters, spaces, and parentheses
            'toilet' => 'required',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'google_maps_link' => 'nullable|url'
        ]);

        $state = '';
        $state = ucwords(str_replace('_', ' ', $request->state));

        $property = Property::findOrFail($id);

        Property::findOrFail($id)->update([
            'address' => $request->address . ', ' . $request->postcode . ' ' . $request->city . ', ' . $state,
            'type' => $request->type,
            'deposit' => $request->deposit,
            'monthly'  => $request->monthly,
            'description' => $request->title . ', ' . $request->room . ' rooms, ' . $request->toilet . ' toilet, ' . $request->area . 'sqrft, ' . $request->description,
        ]);

        if ($request->hasFile('images')) {
            $imageData = [];
            if ($files = $request->file('images')) {
                foreach ($files as $key => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $key . '-' . time() . '.' . $extension;

                    $path = "uploads/properties/";

                    $file->move($path, $filename);

                    $imageData[] = [
                        'property_id' => $id,
                        'image' => $path . $filename,
                    ];
                }
            }

            ImageProperty::insert($imageData);
            $property->status = 3;
        }

        // Check and update the location
        if (!empty($request->google_maps_link)) {
            // Extract coordinates from the Google Maps link
            preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $request->google_maps_link, $matches);

            if (isset($matches[1]) && isset($matches[2])) {
                $latitude = $matches[1];
                $longitude = $matches[2];

                // Find the existing location or create a new one
                $location = LocationProperty::firstOrNew(['property_id' => $id]);
                $location->latitude = $latitude;
                $location->longitude = $longitude;
                $location->save();
            }
        }

        // Save the property
        $property->save();

        return back()->with('status', 'Property Updated');
    }

    public function approval(int $id)
    {
        $property = Property::findOrFail($id);
        $property->status = 1;
        $property->staff_id = Auth::guard('staff')->user()->id;
        $property->save();
        return back()->with('status', 'Property has been approved');
    }

    public function active(int $id)
    {
        $property = Property::findOrFail($id);
        $property->status = 1;
        $property->staff_id = Auth::guard('staff')->user()->id;
        $property->save();
        return back()->with('status', 'Property Active');
    }

    public function inactive(int $id)
    {
        $property = Property::findOrFail($id);
        $property->status = 2;
        $property->staff_id = Auth::guard('staff')->user()->id;
        $property->save();
        return back()->with('status', 'Property Inactive');
    }

    public function available(int $id)
    {
        $property = Property::findOrFail($id);
        $property->available = 1;
        $property->staff_id = Auth::guard('staff')->user()->id;
        $property->save();
        return back()->with('status', 'Property set to Available');
    }

    public function unavailable(int $id)
    {
        $property = Property::findOrFail($id);
        $property->available = 2;
        $property->staff_id = Auth::guard('staff')->user()->id;
        $property->save();
        return back()->with('status', 'Property set to Unavailable');
    }

    public function updateAgent(Request $request)
    {
        $property = Property::findOrFail($request->property_id);
        $property->agent_id = $request->agent_id;
        $property->status = 1;
        $property->available = 1;
        $property->staff_id = Auth::guard('staff')->user()->id;
        $property->save();

        return response()->json(['message' => 'Property assigned to agent completed']);
    }


    ///Guest search property
    // public function gproperty(){
    //     $agents = Staff::all()->where('role', 2);
    //     $staffs = Staff::all()->where('role', 1);
    //     $properties = Property::with('landlord','agent','staff')->where('status', 1)->where('available', 1)->get();
    //     return view('guest.GProperty', compact('properties','agents','staffs'));
    // }

    public function exproperty(){
        $agents = Staff::where('role', 2)->get();
        $staffs = Staff::where('role', 1)->get();
        // Include the locationProperty relationship
        $properties = Property::with('landlord', 'agent', 'staff', 'locationProperty')
            ->where('status', 1)
            ->where('available', 1)
            ->get();

        return view('tenant.EXProperty', compact('properties', 'agents', 'staffs'));
    }

    public function gproperty(){
        $agents = Staff::where('role', 2)->get();
        $staffs = Staff::where('role', 1)->get();
        // Include the locationProperty relationship
        $properties = Property::with('landlord', 'agent', 'staff', 'locationProperty')
            ->where('status', 1)
            ->where('available', 1)
            ->get();

        return view('guest.GProperty', compact('properties', 'agents', 'staffs'));
    }

    public function contact()
    {
        return view('guest.contact');
    }

    public function about()
    {
        return view('guest.about');
    }
}
