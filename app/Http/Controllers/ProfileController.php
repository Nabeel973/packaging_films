<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $path = storage_path("app/public/{$user->image}");
        return view('profile.edit', [
            'user' => $user,
            'image_path'  => $path
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        //dd($request->all(),$request->user());
        //$request->authenticate();
        //dd( $request->user()->fill($request->validated()));
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        //dd($request->hasFile('image'));
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $userDirectory = 'profile_images/' .  $request->user()->id;

            // Delete the previous image if it exists
            if ($request->user()->image) {
                Storage::delete($request->user()->image); // Assuming the 'local' disk
            }

            // Store the new image in the user's directory
            $imagePath = $image->storeAs($userDirectory, $imageName);
            $request->user()->image = $imagePath;
        }
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'Profile Updated Successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
