<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sanction;
use Illuminate\Http\Request;
use App\Helpers\EncryptionHelper;
use Laravel\Sanctum\PersonalAccessToken;


class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        $key="0vXqvr7q9JMMsF4kvnlSTbZ8StibB+MU";
        try
        {
            $decryptedData = EncryptionHelper::decrypt(file_get_contents("php://input"), $key);
            $data=json_decode($decryptedData,true);
            $token=$data['token']??null;
            if (!$token) {
                return response()->json(['message' => 'Token is missing','status'=>'fail'], 401);
            }
            $user = PersonalAccessToken::findToken($token)->tokenable;
            if (!$user) {
                return response()->json(['message' => 'Invalid or expired token','status'=>'fail'], 401);
            }

            if($user->role=='gp')
            {
                $response = [
                    'message' => 'User details retrieved successfully',
                    'status'=>'success',
                    'data' => [
                        'gp' => $user->gp_name,
                        'block'=>$user->block_name,
                        'district'=>$user->district,
                        'zone'=>'Not defined'
                    ],
                ];
            }
            else if($user->role=='xen')
            {
                $response = [
                    'message' => 'User details retrieved successfully',
                    'status'=>'success',
                    'data' => [
                        'gp' =>'Not defined',
                        'block'=>'Not defined',
                        'district'=>'Not defined',
                        'zone' => $user->zone,
                    ],
                ];
            }
            $encryptedRes=EncryptionHelper::encrypt(json_encode($response),$key);
            return response($encryptedRes,200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => $e->getMessage(),'status'=>'fail'], 401);
        }
    }

    public function dashboard(Request $request)
    {
        try
        {
            $decryptedData=EncryptionHelper::decrypt(file_get_contents("php://input"),env('API_KEY'));
            $data=json_decode($decryptedData,true);
            $token=$data['token']??null;
            if (!$token) {
                return response()->json(['message' => 'Token is missing','status'=>'fail'], 401);
            }
            $user = PersonalAccessToken::findToken($token)->tokenable;
            if (!$user) {
                return response()->json(['message' => 'Invalid or expired token','status'=>'fail'], 401);
            }
            if($user->role=='gp')
            {
                $gpName=$user->gp_name;
                $blockName=$user->block_name;
                $district=$user->district;
                $count=Sanction::where('district',$district)->where('block',$blockName)->where('gp',$gpName)->count();
                $response = [
                    'message' => 'Count retrieved successfully',
                    'status'=>'success',
                    'data' => [
                        'count' => $count
                    ],
                ];
            }
            else if($user->role=='xen')
            {
                $zone=$user->zone;
                $sanctionQuery=Sanction::where('status','xen')->with('progress');
                if($zone==='Shimla')
                {
                    $sanctionQuery->where(function($query)
                    {
                        $query->whereIn('district',['Shimla','Sirmaur','Solan','Kinnaur'])
                        ->orWhereIn('block',['Spiti','Anni','Nirmand']);
                    });
                }
                else if($zone==='Dharamshala')
                {
                    $sanctionQuery->where(function($query)
                    {
                        $query->whereIn('district',['Kangra','Una','Hamirpur'])
                        ->orWhereIn('block',['Bharmour','Bhatiyat','Chamba','Mehla','Salooni','Tissa']);
                    }); 
                }
                else if($zone==='Mandi')
                {
                    $sanctionQuery->where(function($query)
                    {
                        $query->whereIn('district',['Mandi','Bilaspur'])
                        ->orWhereIn('block',['Banjar','Bhunter','Kullu','Naggar','Lahaul','Pangi']);
                    });
                }
                $count=$sanctionQuery->count();
                $response = [
                    'message' => 'Count retrieved successfully',
                    'status'=>'success',
                    'data' => [
                        'count' => $count
                    ],
                ];
            }
            $encryptedRes=EncryptionHelper::encrypt(json_encode($response),env('API_KEY'));
            return response($encryptedRes,200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>$e->getMessage(),'status'=>'fail'],401);
        }
    }
}
