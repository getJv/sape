<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class LdapController extends Controller
{


    function show(Request $request){



    }

    function login(Request $request)
    {

        try {

            $credentials = $request->only('username', 'password');
            $username = $credentials['username'];
            $password = $credentials['password'];

            $conn = \Adldap::auth()->attempt($username, $password);

            if (!$conn) throw new \Exception("Credenciais inválidas ou Falha na comunicação ou autenticação com LDAP");

            $user = $this->prettyUserData($username);

            // Check if a user with the specified email exists
            $localUser = User::whereUsername($user['username'])->first();

            if (!$localUser) {

                $localUser = User::create(
                    [
                        'username' => $username,
                        'email' => $user['email'],
                        'name' => $user['nome'],
                        'cargo' => $user['cargo'],
                        'lotacao' => $user['lotacao'],
                        'ramal' => $user['ramal'],
                        'empresa' => $user['empresa'],
                        'solicitador' => false,
                    ]
                );
            }
            $user['id'] =   $localUser->id;
            $user['access_token'] = $localUser->createToken('authToken')->accessToken;

            return response()->json([
                "data"=>[
                    'type'=>'users',
                    'id' => $user['id'],
                    'attributes'=>[
                        'username'      => $user['username'],
                        'email'         => $user['email'],
                        'name'          => $user['nome'],
                        'cargo'         => $user['cargo'],
                        'lotacao'       => $user['lotacao'],
                        'ramal'         => $user['ramal'],
                        'empresa'       => $user['empresa'],
                        'access_token'  => $localUser->createToken('authToken')->accessToken,

                        ]
                ],
                "links"=>[
                    'self'=>url('/users/' . $user['id'] )
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 401);
        }
    }

    private function prettyUserData($username)
    {

        $ldapuser = \Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();



        if (empty($ldapuser)) throw new \Exception("O usuário {$username} não encontrado.");

        $user                   =  array();
        $user['username']       =  isset($ldapuser->getAttributes()['samaccountname'][0]) ? $ldapuser->getAttributes()['samaccountname'][0] : null;
        $user['email']          =  isset($ldapuser->getAttributes()['mail'][0]) ? $ldapuser->getAttributes()['mail'][0] : null;
        $user['nome']           =  isset($ldapuser->getAttributes()['displayname'][0]) ? $ldapuser->getAttributes()['displayname'][0] : null;
        $user['cargo']          =  isset($ldapuser->getAttributes()['title'][0]) ? $ldapuser->getAttributes()['title'][0] : null;
        $user['lotacao']        =  isset($ldapuser->getAttributes()['description'][0]) ? $ldapuser->getAttributes()['description'][0] : null;
        $user['ramal']          =  isset($ldapuser->getAttributes()['telephonenumber'][0]) ? $ldapuser->getAttributes()['telephonenumber'][0] : null;
        $user['empresa']        =  isset($ldapuser->getAttributes()['company']) ? $ldapuser->getAttributes()['company'][0] : null;
        //$user['gruposAD']       =  isset($ldapuser->getAttributes()['memberof']) ? $ldapuser->getAttributes()['memberof'] : null;
        return $user;
    }
}
