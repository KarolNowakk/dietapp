<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Hash;
use App\User;
use Facade\FlareClient\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Response as HttpResponse;
use Laravel\Passport\Passport;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new Client;

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e){
            if ($e->getCode() == 400){
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() == 401) {
                return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
            }
            return response()->json('Something went wrong one the server.', $e->getCode());
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key){
            $token->delete();
        });
        return response()->json('Logged out successfully', 200);
    }

        /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        // $acces_token = $user->token;

        // $user = User::firstOrCreate([
        //     'email' => $user->email,
        // ], [
        //     'name' => $user->nickname,
        //     'password' => Hash::create(Str::random(24)),
        //     'email_verified_at' => now(),
        //     'remember_token' => Str::random(12),
        // ]);

        // $this->getAccesToken('github', $acces_token);
    }

    // public function getAccesToken($providerName, $providerAccessToken)
    // {
    //     $http = new Client;

    //     $response = $http->post('services.passport.login_endpoint', [
    //         RequestOptions::FORM_PARAMS => [
    //             'grant_type' => 'social', // static 'social' value
    //             'client_id' => config('services.passport.client_id'), // client id
    //             'client_secret' => config('services.passport.client_secret'), // client secret
    //             'provider' => $providerName, // name of provider (e.g., 'facebook', 'google' etc.)
    //             'access_token' => $providerAccessToken, // access token issued by specified provider
    //         ],
    //         RequestOptions::HTTP_ERRORS => false,
    //     ]);
    //     $data = json_decode($response->getBody()->getContents(), true);

    //     if ($response->getStatusCode() === HttpResponse::HTTP_OK) {
    //         $accessToken = Arr::get($data, 'access_token');
    //         $expiresIn = Arr::get($data, 'expires_in');
    //         $refreshToken = Arr::get($data, 'refresh_token');

    //         return [
    //             'hej'
    //         ];

    //     } else {
    //         $message = Arr::get($data, 'message');
    //         $hint = Arr::get($data, 'hint');

    //         // error logic
    //     }
    // }
}
