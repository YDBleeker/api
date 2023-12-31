<?php

namespace App\Modules\Services;

use App\Modules\Services\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserService extends Service
{
    protected $userModel;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    protected $_rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ];


    public function registerUser($data) {

        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $data['password'] = Hash::make($data['password']);
        $model = $this->_model->create($data);
        return $model;
    }

    private $credentailRules = [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ];

    function login($data) : ?string {
        $validator = Validator::make($data, $this->credentailRules);
        if ($validator->fails()) return null;

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        $token = auth()->attempt($credentials);
        return $token;
    }


}
