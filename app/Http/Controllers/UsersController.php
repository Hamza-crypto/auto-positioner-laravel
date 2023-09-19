<?php

namespace App\Http\Controllers;

use App\Models\UserPosition;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('positions:name')->get();

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        $positions = Position::all();
        return view('pages.users.add', get_defined_vars());
    }

    public function store(Request $request)
    {
        //for users table query
        $user = new User();
        $user->name = $request->input('name');
        $user->age = $request->input('age');
        $user->save();

        //for userPositions table
        $userId = $user->id;

        $selectedPositions = $request->input('positions', []);

        foreach ($selectedPositions as $positionId) {
            $userPosition = new UserPosition();
            $userPosition->user_id = $userId;
            $userPosition->position_id = $positionId; 
            $userPosition->save();
        }
        // Redirect the user to a success page or any other page as per your requirement
        return redirect()->route('users.index')->with('success', 'User information stored successfully!');
    }

    public function edit(User $user)
    {
        $positions = Position::all();

        $user_positions = array_map(function ($item) {
            return $item['id'];
        }, $user->positions->toArray());

        return view('pages.users.edit', get_defined_vars());
    }

    public function update(User $user, Request $request)
    {
        $user->name = $request->input('name');
        $user->age = $request->input('age');

        $positions = $request->input('positions', []); // Get the positions from the request (assuming it's an array)
        $user->positions()->sync($positions);

        // Save the updated user record
        $user->save();

        // Redirect the user to a success page or any other page as per your requirement
        return redirect()->route('users.index')->with('success', 'User information updated successfully!');

        Session::flash('success', __('Account information successfully updated.'));

        return back();
        //return redirect()->route('users.edit', $user->id);
    }

    public function password_update(Request $request, User $user)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        Session::flash('password_update', 'Password updated successfully.');

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        Session::flash('success', 'User deleted successfully.');

        return redirect()->route('users.index');
    }

   
}
