<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenancy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();
        return response()->json($users);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => ['required','email', Rule::unique('users','email')],
            'password'          => 'required|string|min:6',
            'role'              => 'nullable|string|max:50',
            'phone'             => 'nullable|string|max:30',
            'dob'               => 'nullable|date',
            'gender'            => 'nullable|string|max:20',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'county'            => 'nullable|string|max:100',
            'postal_code'       => 'nullable|string|max:50',
            'status'            => 'nullable|string|max:50',
            'property_count'    => 'nullable|integer',
            'assigned_properties'=> 'nullable', // accept JSON string or array
            'skills'            => 'nullable', // accept JSON string or array
            'is_email_verified' => 'nullable|boolean',
            '2fa_enabled'       => 'nullable|boolean',
            'profile_photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // <= 5MB
            'profile_photo_url' => 'nullable|url', // URL option

        ]);

        // Build full name
        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        // Prepare data for mass assignment
        $data = [
            'name'                => $fullName,
            'email'               => $validated['email'],
            'password'            => Hash::make($validated['password']),
            'role'                => $request->input('role', 'landlord'),
            'phone'               => $request->input('phone'),
            'dob'                 => $request->input('dob'),
            'gender'              => $request->input('gender'),
            'address'             => $request->input('address'),
            'city'                => $request->input('city'),
            'county'              => $request->input('county'),
            'postal_code'         => $request->input('postal_code'),
            'status'              => $request->input('status', 'active'),
            'property_count'      => $request->input('property_count', 0),
            'assigned_properties'   => 'nullable|array',
            'assigned_properties.*' => 'exists:properties,id',
            'is_email_verified'   => $request->boolean('is_email_verified', false),
            // '2fa_enabled' is a column name starting with number — ensure your DB column exists exactly like this
            '2fa_enabled'         => $request->boolean('2fa_enabled', false),
            'last_login'          => null,
            'email_verified_at'   => $request->boolean('is_email_verified', false) ? now() : null,
        ];

        // Handle assigned_properties: allow array or JSON string
        if ($request->has('assigned_properties')) {
            $ap = $request->input('assigned_properties');
            if (is_array($ap)) {
                $data['assigned_properties'] = json_encode($ap);
            } else {
                // try to validate string as JSON, otherwise store as-is
                json_decode($ap);
                $data['assigned_properties'] = (json_last_error() === JSON_ERROR_NONE) ? $ap : json_encode([]);
            }
        } else {
            $data['assigned_properties'] = null;
        }

        // Handle skills similarly
        if ($request->has('skills')) {
            $sk = $request->input('skills');
            if (is_array($sk)) {
                $data['skills'] = json_encode($sk);
            } else {
                json_decode($sk);
                $data['skills'] = (json_last_error() === JSON_ERROR_NONE) ? $sk : null;
            }
        } else {
            $data['skills'] = null;
        }

        // Handle profile photo or URL
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . Str::random(8) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profiles', $filename, 'public');
            $data['profile_photo'] = $path;
            $data['profile_photo_url'] = null; // clear URL if file uploaded
        } elseif ($request->filled('profile_photo_url')) {
            $data['profile_photo'] = null;
            $data['profile_photo_url'] = $request->input('profile_photo_url'); // store URL directly
        } else {
            $data['profile_photo'] = null;
            $data['profile_photo_url'] = null;
        }

        // Create user with mass assignment using fillable attributes
        $user = User::create($data);

        // ✅ Create tenancy if role is tenant and property selected
        if ($user->role === 'tenant' && $request->property_id) {
            Tenancy::create([
                'tenant_id'   => $user->id,
                'property_id' => $request->property_id,
                'unit_id'     => $request->unit_id ?? null,
                'start_date'  => $request->start_date ?? now(),
                'status'      => 'active',
            ]);
        }  
        
        if ($request->filled('assigned_properties')) {
            $user->properties()->sync($request->assigned_properties);
        }
   

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user);        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validate only what can change
        $validated = $request->validate([
            'name'         => 'nullable|string|max:255',
            'email'             => ['nullable','email', Rule::unique('users','email')->ignore($user->id)],
            'password'          => 'nullable|string|min:6',

            'role'              => 'nullable|string|max:50',
            'phone'             => 'nullable|string|max:30',
            'dob'               => 'nullable|date',
            'gender'            => 'nullable|string|max:20',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'county'            => 'nullable|string|max:100',
            'postal_code'       => 'nullable|string|max:50',
            'status'            => 'nullable|string|max:50',
            'property_count'    => 'nullable|integer',

            'assigned_properties'=> 'nullable',
            'skills'            => 'nullable',

            'is_email_verified' => 'nullable|boolean',
            '2fa_enabled'       => 'nullable|boolean',

            'profile_photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'profile_photo_url' => 'nullable|url',
        ]);

        // Optional password update
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        // Handle assigned_properties
        if ($request->has('assigned_properties')) {
            $user->properties()->sync($request->assigned_properties);
        }

        // Handle skills
        if ($request->has('skills')) {
            $sk = $request->input('skills');
            if (is_array($sk)) {
                $validated['skills'] = json_encode($sk);
            } else {
                json_decode($sk);
                $validated['skills'] = json_last_error() === JSON_ERROR_NONE ? $sk : null;
            }
        }

        // ⭐ Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . Str::random(8) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profiles', $filename, 'public');

            // delete old file if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $validated['profile_photo'] = $path;
            $validated['profile_photo_url'] = null;
        }
        // ⭐ If URL provided instead of file
        elseif ($request->filled('profile_photo_url')) {
            $validated['profile_photo'] = null;
            $validated['profile_photo_url'] = $request->profile_photo_url;
        }

        // Update user
        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);
        return response()->json(['message' => 'Deleted']);        
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Profile Updated successfully!",
            'user' => $user
        ], 200);
    }

    public function changePassword(Request $request, $id)
    {
        {
            $user = User::findOrFail($id);

            if($user){
                $user->update(array('password' => Hash::make($request->new_password)));
                $user->save();
            }
            return response()->json([
                'status' => 200,
                'message' => 'Password changed successfully'
            ]);
        }
    }
}
