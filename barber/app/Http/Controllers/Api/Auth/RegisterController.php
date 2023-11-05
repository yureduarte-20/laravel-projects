<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    protected $rulesCustomerResquest = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|string',
        'confirmPassword' => 'required|min:8|string|same:password',
        'phone' => 'nullable'
    ];

    public function registerNewCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesCustomerResquest, [
            'confirmPassword' => [
                'same' => 'Os campos senha e confirmar senha devem corresponder',
            ],
            'email' =>[
                'unique' => 'O email já está sendo utilizado'
            ]
        ]);
        if($validator->fails()){
            return Response::json([ 'error' => $validator->errors()->all() ])->setStatusCode(400);
        }
        $customer = Customer::newModelInstance($validator->validated());
        $user = User::newModelInstance($validator->validated());
        try{
            DB::beginTransaction();
            if($customer->save()){
                $customer->user()->save($user);
                DB::commit();
                return Response::json([ ...$user->only(['email', 'name']), ...$customer->toArray() ]);
            }
            DB::rollBack();
            return Response::json([ 'message' => 'Não foi possível criar' ])->setStatusCode(422);
        } catch (\Exception $exception){
            DB::rollBack();
            return Response::json([ 'message' => 'Não foi possível criar' ])->setStatusCode(422);
        }
    }
}
