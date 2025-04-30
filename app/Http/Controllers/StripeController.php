<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Contract;
use App\Models\Property;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function stripe(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        $response = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'myr', // Change currency to MYR
                        'product_data' => [
                            'name' => $request->product_name,
                        ],
                        'unit_amount' => $request->price * 100,
                    ],
                    'quantity' => $request->quantity,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);
        //dd($response);
        if(isset($response->id) && $response->id != ''){
            $t = '';
            if ($request->product_name == 'Deposit') {
                $t = 1;
            } else {
                $t = 2;
            }
            session()->put('product_name', $t);
            session()->put('contract_id', $request->contract);
            session()->put('price', $request->price);
            return redirect($response->url);
        } else {
            return redirect()->route('cancel');
        }
    }

    public function success(Request $request){
        if(isset($request->session_id)) {

            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);
            //dd($response);

            $payment = new Payment();

            Payment::create([
                'contract_id' => session()->get('contract_id'),
                'type' => session()->get('product_name'),
                'total' => session()->get('price'),
            ]);

            return redirect()->route('tenant_contract')->with('status', 'Payment is successful');


            session()->forget('product_name');
            session()->forget('contract_id');
            session()->forget('price');

        } else {
            return redirect()->route('cancel');
        }
    }

    public function cancel()
    {
        return redirect()->route('tenant_contract')->with('status', 'Payment cancelled');
    }

    //monthly
    public function mstripe(Request $request)
    {
        $cid = Contract::findOrFail($request->contract);
        $pid = Property::findOrFail($cid->property_id);

        // Validate the request
        $request->validate([
            'price' => [
                'required',
                'numeric',
                function($attribute, $value, $fail) use ($cid, $pid) {
                    if ($value > $cid->balance) {
                        $mblnce = $cid->balance / $pid->monthly;
                        $fail('The ' . $attribute . ' exceeds the balance of the contract. The monthly payment left is ' . $mblnce . 'month.');
                    }
                },
            ],
        ]);

        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        $response = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'myr', // Change currency to MYR
                        'product_data' => [
                            'name' => $request->product_name,
                        ],
                        'unit_amount' => $request->price * 100,
                    ],
                    'quantity' => $request->quantity,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('msuccess').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('mcancel'),
        ]);
        //dd($response);
        if(isset($response->id) && $response->id != ''){
            $t = '';
            if ($request->product_name == 'Deposit') {
                $t = 1;
            } else {
                $t = 2;
            }
            session()->put('product_name', $t);
            session()->put('contract_id', $request->contract);
            session()->put('price', $request->price);
            return redirect($response->url);
        } else {
            return redirect()->route('mcancel');
        }
    }

    public function msuccess(Request $request)
    {
        if(isset($request->session_id)) {

            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);
            //dd($response);

            $payment = new Payment();

            Payment::create([
                'contract_id' => session()->get('contract_id'),
                'type' => session()->get('product_name'),
                'total' => session()->get('price'),
            ]);

            return back()->with('status', 'Payment is successful');

            session()->forget('product_name');
            session()->forget('contract_id');
            session()->forget('price');

        } else {
            return redirect()->route('mcancel');
        }
    }

    public function mcancel()
    {
        return back()->with('status', 'Payment cancelled');
    }


}
