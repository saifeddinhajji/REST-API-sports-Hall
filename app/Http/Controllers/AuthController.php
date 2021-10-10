<?php

namespace App\Http\Controllers;
use App\Models\Gym;
use App\Models\Offer;
use App\Models\SubscriptionGym;
use App\Models\User;
use App\Libs\Result;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class AuthController extends BaseAuthController
{
    /**
     * Create a new AuthController instance.
     * @return void
     */
    public function __construct()
    {

    }
    public function getEndAt($start_at,$duration,$type)
    {
        //
        $day= Carbon::parse($start_at);
        ;
        switch ($type)
        {
            case "day":
                return    $day->addDay($duration)->format('Y-m-d');
            case "month":
                return    $day->addMonth($duration)->format('Y-m-d');
            case "year":
                return    $day->addYear($duration)->format('Y-m-d');
        }
    }
    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $res = new Result();
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            $selectedUser = User::where(['email' => $request->get('email')])->first();
            if($selectedUser['role'] != "admin" && $selectedUser['status'] ==false)
            {
                $res->fail('l\'utilisateur est bloqué');
                return response()->json($res,400);
            }
            $responseToken = $this->respondWithToken($token);
            $responseToken['user'] = $selectedUser;

            if(in_array($selectedUser['role'],['manager','secretary']))
            {

                $sub=SubscriptionGym::where('status','accepter')->where('gym_id',$selectedUser['gym_id'])->orderBy('id','desc')->first();

             if(($sub && $sub['end_at'] < new DateTime('now')) || $sub==null)
                {

                    $responseToken['user']['is_blocked_service']=true;
                    $responseToken['is_blocked_service']=true;

                }

            }
            else
            {
                $responseToken['is_blocked_service']=false;
            }
          //  dd($responseToken);
            $res->success($responseToken);
            return response()->json($res);
        }
        $res->fail('Email ou mot de passe incorrect');
        return response()->json($res,400);
    }


    /**
     * Log the user out (Invalidate the token)
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $res = new Result();
        $this->guard()->logout();
        $res->success('Déconnecté avec succès');
        return response()->json($res);

    }



    /**
     * Refresh a token.
     * @return JsonResponse
     */
    public function signUp(Request $request)
    {

        $res = new Result();
        try {
        DB::beginTransaction();
        $dataGym = $request->all(['name','description','logo','code_fiscal','vacation_day','url_fcb','addressGym']);
        $gym =  new Gym();
        $res = $gym->CreateOne($dataGym);
        if(!$res->success)
        {
            return response()->json($res,400);
        }
        $data = $request->all(['first_name', 'last_name', 'email','password','address','phone','photo']);
        $data['gym_id'] = $res->data->id;
        $data['role'] = "manager";
        $user = new User();
        $res = $user->CreateOne($data);
        if(!$res->success) { return response()->json($res,400);}
        $dataSubscription = $request->all(['start_at','offer_id','payment_receipt']);

        $offer=Offer::find($request->get('offer_id'));
        $date=  $this->getEndAt($request->get('start_at'),$offer['duration'],$offer['unit']);

        $dataSubscription['end_at']=$date;
        $dataSubscription['gym_id']=$res->data->gym_id;
        $subscriptionGym=new SubscriptionGym();
        $res= $subscriptionGym->CreateOne($dataSubscription);
        if(!$res->success) { return response()->json($res,400); }
        $res->success();
        $res->message="inscription a été effectué avec succès";
        DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            $res->fail($e->getMessage());
        }

        return response()->json($res);
    }


    /**
     * Get the token array structure.
     * @param string $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'token_type'=>"Bearer",
            'token' => $token,
            'expires_in' => $this->guard()->factory()->getTTL()*60
        ];
    }


    public function updatePassword(Request $request)
    {
        $res = new Result();
        $data = $request->all(['newPassword', 'oldPassword']);
        $check = Validator::make($data, [
            'newPassword' => 'required',
            'oldPassword' => 'required'
        ]);
        if ($check->fails()) {
            $res->fail($check->errors()->first());
            return response()->json($res);
        }
        $user = User::find($this->guard()->user()['id']);
        if (!Hash::check($data['oldPassword'], $user->getAttribute('password'))) {
            $res->fail("old password don't match");
            return response()->json($res);
        }
        $userModel = new User();
        $res = $userModel->UpdateOne(['password' => $data['newPassword']], $this->guard()->user()['id']);
        return response()->json($res);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
    public function me()
    {
        $res=new Result();
        $res->success($this->guard()->user());
        return response()->json($res);
    }
    public function update(Request $request)
    {
        $res=new Result();
        try {
          $user=new User();
         $res= $user->UpdateOne($request->all(),$this->guard()->id());
        }
        catch (\Exception $e)
        {
            $res->fail($e->getMessage());
            return response()->json($res,400);
        }
        return response()->json($res);
    }


}
