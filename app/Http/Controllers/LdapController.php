<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LdapController extends Controller
{
    function login(Request $request)
    {

        try {

            $credentials = $request->only('username', 'password');
            $username = $credentials['username'];
            $password = $credentials['password'];

            $conn = \Adldap::auth()->attempt($username, $password);

            if (!$conn) throw new \Exception("Credenciais inválidas ou Falha na comunicação ou autenticação com LDAP");


            $ldapuser = \Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();



            return response()->json($ldapuser);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 401);
        }
    }
}
