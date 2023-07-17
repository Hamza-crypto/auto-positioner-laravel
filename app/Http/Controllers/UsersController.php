<?php

namespace App\Http\Controllers;

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
        $user = new User();
        $user->name = $request->input('name');
        $user->age = $request->input('age');
        $user->time_in = $request->input('time_in');
        $user->time_out = $request->input('time_out');
        $user->break_in = $request->input('break_in');
        $user->break_out = $request->input('break_out');

        $user->save();

        // Attach positions to the user (assuming "positions" is a many-to-many relationship)
        $user->positions()->attach($request->input('positions'));

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
        $user->time_in = $request->input('time_in');
        $user->time_out = $request->input('time_out');
        $user->break_in = $request->input('break_in');
        $user->break_out = $request->input('break_out');

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

    public function update_wallet_info(Request $request, User $user)
    {
        //dd($request->all());

        if ($request->usdt != null) {
            $user->metas()->updateOrCreate(
                ['meta_key' => 'usdt_address'],
                ['meta_value' => $request->usdt]);
        }

        if ($request->btc != null) {
            $user->metas()->updateOrCreate(
                ['meta_key' => 'btc_address'],
                ['meta_value' => $request->btc]);
        }

        if ($request->trc != null) {
            $user->metas()->updateOrCreate(
                ['meta_key' => 'trc_address'],
                ['meta_value' => $request->trc]);
        }

        if (Auth()->user()->role == 'admin') {
            $user->metas()->updateOrCreate(
                ['meta_key' => 'rate'],
                ['meta_value' => $request->rate]);
        } else {
            $user->metas()->updateOrCreate(
                ['meta_key' => 'rate'],
                ['meta_value' => $user->rate]);
        }

        Session::flash('account', 'Wallet info updated successfully.');

        return redirect()->back();
    }

    public function update_gateway(Request $request, User $user)
    {

        if (Auth()->user()->role == 'admin') {

            $user->metas()->updateOrCreate(
                ['meta_key' => 'gateway'],
                ['meta_value' => $request->gateway_id]);
        }

        Session::flash('account', 'Gateway updated successfully.');

        return redirect()->back();
    }

    public function update_parent(Request $request, User $user)
    {

        if (Auth()->user()->role == 'admin') {

            if ($request->parent == 0) {
                $parent_id = null;
            } else {
                $parent_id = $request->parent;
            }
            $user->updateOrCreate(
                ['id' => $user->id],
                ['parent_id' => $parent_id]);
        }

        Session::flash('account', 'Successfully assigned.');

        return redirect()->back();
    }

    public function update_order_category(Request $request, User $user)
    {
        $user->metas()->updateOrCreate(
            ['meta_key' => 'order_category'],
            ['meta_value' => $request->category]);

        Session::flash('account', 'Order Category updated successfully.');

        return redirect()->back();

    }

    public function update_payable_section(Request $request, User $user)
    {
        $status = 0;

        if ($request->has('status')) {
            $status = 1;
        }
        $user->metas()->updateOrCreate(
            ['meta_key' => 'payable_visible'],
            ['meta_value' => $status]);

        Session::flash('account', 'Successfully updated.');

        return redirect()->back();

    }
}
