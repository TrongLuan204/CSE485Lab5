<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

       
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Lưu ảnh mới
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }
        
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
