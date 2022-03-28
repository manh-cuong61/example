<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Models\Product;
use App\Repositories\UserRepository;
use App\Repositories\UserInterface;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private $users;
    private $product;
    private $userRepository;
    public function __construct(User $users, Product $product, UserInterface $userRepository)
    {
        $this->users = $users;
        $this->product = $product;
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->users->with('roles', 'roles.permissions')->withCount('roles');

        if (!empty($role = $request->input('role'))) {
            $users = $users->role($role);
        };
        if (!empty($search = $request->input('search'))) {
            $users = $users->search($search);
        };
        if (!empty($permission = $request->input('permission'))) {
            $users = $users->permission($permission);
        };
        $users =  $users->paginate(2);
        //dd($users);
        // return response()->json([
        //     'data' => UserResource::collection($users),
        //     'users_count' => $users->count(),
        // ]);
        return UserResource::collection(($users))
            ->additional([
                'aggregate' =>
                ['users_count' => $users->count()],
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $credentials = $request->only(['name', 'email', 'phone', 'password', 'roles']);
        $credentials['password'] = Hash::make($credentials['password']);

        $user = User::create($credentials);
        if (array_key_exists('roles', $credentials) && !empty($credentials['roles'])) {
            $user->roles()->sync($credentials['roles']);
            $user->load('roles');
        }

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $credentials = $request->only(['name', 'password', 'phone', 'email', 'roles']);
        $user = User::findOrFail($id);
        $credentials['password'] = Hash::make($credentials['password']);
        $user->roles()->sync($credentials['roles']);
        $user->update($credentials);
        $user->load('roles');

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $user->roles()->detach();
        return response()->json([
            'data' => 'ok'
        ]);
    }

    public function updateMutipleUser(Request $request)
    {
        $credentials = $request->only('data');
        $result = collect($credentials['data'])->map(function ($value) {

            $user = User::findOrFail($value['id']);
            $value['password'] = Hash::make($value['password']);
            $user->roles()->sync($value['roles']);
            $user->load('roles');

            $user->update($value);

            return $user;
        });

        return UserResource::collection($result);
    }

    public function getPermissions()
    {
        $users = User::get();
        foreach ($users as $user) {
            $roles = $user->roles;
            $roles->map(function ($role) {
                $role->load('permissions');
            });
        }
        return response()->json([
            'data' =>  $users,
        ]);
    }

    public function pushShoppingCart(Request $request)
    {
        Session::push('idProducts', $request->input('id'));
    }

    public function getShoppingCart(Request $request)
    {
        //dd(Session::get('idProducts'));
        $products = [];
        $idProducts = Session::get('idProducts');
        foreach ($idProducts as $idProduct) {
            if (empty($this->product->find($idProduct))) {
                continue;
            } else {
                $product = $this->product->find($idProduct);
                if (empty($products)) {
                    array_push($products, $product);
                } else {

                    foreach ($products as $key => $value) {
                        if (!empty($value['quantity'])) {
                            if ($product->id == $value->id) {
                                $value['quantity'] += 1;
                            }
                        } else {
                            //$value['quantity'] = 1;
                            $product['quantity'] = 1;
                            array_push($products, $product);
                        }
                    }
                }
                // if($value['quantity'] == 1){
                //array_push($products, $product);
                // }
            }
        };



        return ProductResource::collection($products);
    }

    public function getAll()
    {
        $users =  $this->userRepository->getAll();
        return $users;
    }
}
