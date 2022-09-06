<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class AuthController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $photo = $request->file('photo');
        if ($photo) {
            $fileName = time().'_'.$photo->getClientOriginalName();
            $filePath = $photo->storeAs('images/users', $fileName, 'public');
        }

        $phone_number = $request['phone_number'];
        if ($phone_number) {
            if ($request['phone_number'][0] == "0") {
                $phone_number = substr($phone_number, 1);
            }
    
            if ($phone_number[0] == "8") {
                $phone_number = "62" . $phone_number;
            }
        }
        


        $user = User::create([
            'photo'         => $filePath ?? null,
            'name'          => $request->name,
            'email'         => $request->email,
            'phone_number'  => $phone_number ?? null,
            'password'      => Hash::make($request->password)
         ]);

        $this->whatsappNotification($user->phone_number, $user->name, $user->email);

        // $user->notify(new WelcomeEmailNotification($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function profile()
    {
        return response()->json(['message' => 'Your Profile','data' => Auth::user()]);
    }

    private function whatsappNotification($recipient, $userName, $userEmail)
    {
        $sid     = env("TWILIO_AUTH_SID");
        $token   = env("TWILIO_AUTH_TOKEN");
        $wa_from = env("TWILIO_WHATSAPP_FROM");
        $twilio  = new Client($sid, $token);
        

        $body = 'Hello '.$userName.', welcome to Kampus Merdeka and your email is '.$userEmail.', thankyou';

        return $twilio->messages->create("whatsapp:+$recipient",[
                                        "from" => "$wa_from",
                                        "body" => $body
                                    ]);
    }


    public function login(Request $request) {
        if (!Auth::attempt($request->only('email','password'))) {
            return response()->json([
                'message' => 'unauthorized'
            ], 401);
        }

        $user = User::Where('email', $request->email)->firstorFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hi '. $user->name.', welcome to home',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
