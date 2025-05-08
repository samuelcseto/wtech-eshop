<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        
        return view('profile.index', compact('user', 'addresses'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profil bol úspešne aktualizovaný.');
    }

    /**
     * Show form to create new address.
     */
    public function createAddress()
    {
        // Fetch active countries for the dropdown
        $countries = \App\Models\Country::where('is_active', true)->orderBy('name')->get();
        
        return view('profile.address-form', [
            'address' => null,
            'countries' => $countries,
        ]);
    }

    /**
     * Store a new address for the user.
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'address_line1' => 'required|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'is_default' => 'sometimes|boolean',
        ]);

        $user = Auth::user();
        
        // Start a transaction to ensure consistency when setting default address
        DB::transaction(function() use ($request, $user) {
            // If this is marked as default, unset any other default addresses
            if ($request->has('is_default') && $request->is_default) {
                $user->addresses()->update(['is_default' => false]);
            }
            
            // Create the new address
            $user->addresses()->create([
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'is_default' => $request->has('is_default') ? $request->is_default : false,
            ]);
        });

        return redirect()->route('profile.show')
            ->with('success', 'Nová adresa bola úspešne pridaná.');
    }

    /**
     * Show form to edit an address.
     */
    public function editAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->where('address_id', $id)->firstOrFail();
        
        // Fetch active countries for the dropdown
        $countries = \App\Models\Country::where('is_active', true)->orderBy('name')->get();
        
        return view('profile.address-form', compact('address', 'countries'));
    }

    /**
     * Update the user's address.
     */
    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'address_line1' => 'required|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'is_default' => 'sometimes|boolean',
        ]);

        $user = Auth::user();
        $address = $user->addresses()->where('address_id', $id)->firstOrFail();
        
        // Start a transaction to ensure consistency when setting default address
        DB::transaction(function() use ($request, $user, $address) {
            // If this is marked as default, unset any other default addresses
            if ($request->has('is_default') && $request->is_default) {
                $user->addresses()->where('address_id', '!=', $address->address_id)
                     ->update(['is_default' => false]);
            }
            
            // Update the address
            $address->update([
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'is_default' => $request->has('is_default') ? $request->is_default : false,
            ]);
        });

        return redirect()->route('profile.show')
            ->with('success', 'Adresa bola úspešne aktualizovaná.');
    }

    /**
     * Set an address as the default address.
     */
    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        
        // Start a transaction to ensure consistency
        DB::transaction(function() use ($user, $id) {
            // Unset all default addresses
            $user->addresses()->update(['is_default' => false]);
            
            // Set the selected address as default
            $user->addresses()->where('address_id', $id)->update(['is_default' => true]);
        });
        
        return redirect()->route('profile.show')
            ->with('success', 'Predvolená adresa bola úspešne nastavená.');
    }

    /**
     * Delete an address.
     */
    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->where('address_id', $id)->firstOrFail();
        
        // Check if this is the only address
        if ($user->addresses()->count() <= 1) {
            return redirect()->route('profile.show')
                ->with('error', 'Nemôžete vymazať jedinú adresu. Najskôr pridajte novú adresu.');
        }
        
        // If deleting the default address, set another one as default
        if ($address->is_default) {
            $newDefault = $user->addresses()->where('address_id', '!=', $id)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }
        
        $address->delete();
        
        return redirect()->route('profile.show')
            ->with('success', 'Adresa bola úspešne vymazaná.');
    }
}