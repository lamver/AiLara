<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Wnikk\LaravelAccessRules\Models\Owner;
use Wnikk\LaravelAccessRules\Models\Inheritance;

class UserController extends Controller
{
    private int $numberPaginate = 15;

    public function __construct()
    {
       // dd(Gate::forUser(\Illuminate\Support\Facades\Auth::user())->allows('admin.*'));
       // $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate($this->numberPaginate);

        return view('admin.user.index', ['users' => $users]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create', [
            'forms' => User::all(),
            'owners' => $this->getCurrentOwners(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $request->avatar,
            'sys_user' => (bool) $request->sys_user,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));


        $user->inheritPermissionFrom(ucfirst($request->owner), strtolower($request->owner));

        return redirect()->route('admin.user.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::find($id);

        return view('admin.user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $user = User::find($id);

        return view('admin.user.edit', [
            'user' => $user,
            'owners' => $this->getCurrentOwners(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::find($id);

        $user->fill([
            'email' => $request->email,
            'name' => $request->name,
            'avatar' => $request->avatar ?? "",
            'sys_user' => (bool) $request->sys_user,
            'status' =>  (empty($request->status) ? User::STATUS_OFF : User::STATUS_ON),
        ]);

        $inheritanceParent = $user->getInheritanceParent();
        if ($inheritanceParent && $request->owner !== $inheritanceParent->original_id) {
            Inheritance::where('owner_id', $user->getOwner()->id)->delete();
            $user->inheritPermissionFrom(ucfirst($request->owner), $request->owner);
        }

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('admin.user.index');

    }

    /**
     * Delete a user from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $user = User::find($id);

        $user->delete();

        return redirect()->route('admin.user.index');

    }

    /**
     * Log in as a user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logInAsUser(Request $request)
    {
        Auth::loginUsingId($request->user_id);
       return redirect()->route('admin.user.index');
    }

    /**
     * Get the current owners.
     *
     * @return Collection|Owner[]
     */
    protected function getCurrentOwners ()
    {
        return Owner::where(function ($query) {
            $query->where('original_id', 'not regexp', '^[0-9]+$');
        })->get();
    }

}
