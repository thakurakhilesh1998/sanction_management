<?php

namespace App\Http\Controllers\API;

use App\Helpers\EncryptionHelper;
use App\Http\Controllers\Controller;
use App\Models\Progress;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Sanction;
use Response;

class XENController extends Controller
{
    public function viewSanction(Request $request)
    {
        try
        {
            $decryptedData=EncryptionHelper::decrypt(file_get_contents('php://input'),env('API_KEY'));
            $data=json_decode($decryptedData,true);
            $token=$data['token']??null;
            if(!$token){
                return response()->json(['message' => 'Token is missing','status'=>'fail'], 401);
            }
            $user = PersonalAccessToken::findToken($token)->tokenable;
            if (!$user) {
                return response()->json(['message' => 'Invalid or expired token','status'=>'fail'], 401);
            }
            $zone=$user->zone;
            $sanctionQuery=Sanction::where("status",'xen')->with('progress');
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
            $sanction=$sanctionQuery->get();
            $response=[
                'message' => 'Sanction fetched successfully',
                    'status'=>'success',
                    'data'=>[
                        'sanction'=>$sanction->unique(function($san){
                            return $san->district.'-'.$san->block.'-'.$san->gp;
                        })
                        ->map(function($san) {
                            $lastUpdateDate = isset($san->progress) ? \Carbon\Carbon::parse($san->progress->updated_at) : null;
                            $currentDate = \Carbon\Carbon::now();
                            $days = $lastUpdateDate ? $lastUpdateDate->diffInDays($currentDate) : null;

                            return [
                                'district_name' => $san->district,
                                'block_name' => $san->block,
                                'gp_name' => $san->gp,
                                'current_status' => isset($san->progress) ? $san->progress->completion_percentage : 'Progress Not Reported yet.',
                                'delay' => isset($san->progress)
                                    ? ($san->progress->completion_percentage === 'Work Completed'
                                        ? 'Work Completed'
                                        : "There are {$days} days since {$san->progress->completion_percentage}")
                                    : 'No Progress Added',
                            ];
                        })
                        ->values(),
                        ]
                    ];
            $encryptedData=EncryptionHelper::encrypt(json_encode($response),env('API_KEY'));
            return response($encryptedData,200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message'=>$e->getMessage(),'status'=>'fail'],401);
        }
    }

    public function viewGpSan()
    {
        try
        {
            $decryptedData=EncryptionHelper::decrypt(file_get_contents('php://input'),env('API_KEY'));
            $data=json_decode($decryptedData,true);
            $token=$data['token']??null;
            if(!$token){
                return response()->json(['message' => 'Token is missing','status'=>'fail'], 401);
            }
            $user = PersonalAccessToken::findToken($token)->tokenable;
            if (!$user) {
                return response()->json(['message' => 'Invalid or expired token','status'=>'fail'], 401);
            }
            $block=$data['block'];
            $gp=$data['gp'];
            $district=$data['district'];
            $sanction=Sanction::where('district',$district)->where('block',$block)->where('gp',$gp)->with('progress')->get();

            $data=$sanction->map(function ($sanction,$index)
            {
                $canAddProgress=!isset($sanction->progress) || optional($sanction->progress)->completion_percentage==='-1';
                $canUploadUC=$sanction->uc===null && isset($sanction->progress) && in_array($sanction->progress->completion_percentage,['Work Started', 'Partial Completion', 'Work Completed']);
                return [
                    'sr_no'=>$index+1,
                    'district_name' => $sanction->district,
                    'block_name' => $sanction->block,
                    'gp_name' => $sanction->gp,
                    'total_amount_received' => $sanction->san_amount,
                    'financial_year' => $sanction->financial_year,
                    'sanction_purpose' => $sanction->sanction_purpose,
                    'sanction_head' => $sanction->sanction_head,
                    'sanction_date' => $sanction->sanction_date,
                    'sanction_file_url' => url('xen/view-sanction-file/' . $sanction->san_sign_pdf),
                    'uc_file_url' => $sanction->uc ? url('xen/viewUCgp/' . $sanction->uc) : null,
                    'can_add_progress' => $canAddProgress,
                    'can_update_progress' => !$canAddProgress,
                    'can_upload_uc' => $canUploadUC,
                    'upload_uc_modal' => $canUploadUC ? [
                    'sanction_id' => $sanction->id,] : null,
                    'progress_status' => $sanction->progress->completion_percentage ?? 'Work Not Reported yet.',
                ];
            });

            if($data->isEmpty()){
                return response()->json(
                    [
                        'message' => 'No new sanctions found.',
                        'status' => 'info',
                        'data' => [],
                    ]
                    );
            }
            $response=[
                'message' => 'Sanction fetched successfully',
                    'status'=>'success',
                    'data'=>$data,
            ];
            $encryptedData=EncryptionHelper::encrypt(json_encode($response),env('API_KEY'));
            return response($encryptedData,200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>$e->getMessage(),'status'=>'fail'],401);
        }
    }

    // Function to show the Page to Add Progress
    public function addProgress()
    {
        try
        {
            $decryptedData=EncryptionHelper::decrypt(file_get_contents('php://input'),env('API_KEY'));
            $data=json_decode($decryptedData,true);
                $token=$data['token']??null;
                if(!$token){
                    return response()->json(['message' => 'Token is missing','status'=>'fail'], 401);
                }
                $user = PersonalAccessToken::findToken($token)->tokenable;
                if (!$user) {
                    return response()->json(['message' => 'Invalid or expired token','status'=>'fail'], 401);
                }
                $block=$data['block'];
                $gp=$data['gp'];
                $district=$data['district'];
                $sanction=Sanction::where('district',$district)->where('block',$block)->where('gp',$gp)->get();
                if(count($sanction)===0){
                    return response()->json([
                        'message'=>'No sanction found with these details',
                        'status'=>'info',
                        'data'=>null
                    ]);
                }
                $sanAmount=0;
                foreach($sanction as $san)
                {
                    $sanAmount+=$san->san_amount;
                }
                $response=[
                    'message'=>'Sanction fetched successfully',
                    'status'=>'success',
                    'data'=>['district'=>$sanction[0]->district,
                            'block'=>$sanction[0]->block,
                            'gp'=>$sanction[0]->gp,
                            'amount'=>$sanAmount
                        ],
                ];
                $encryptedData=EncryptionHelper::encrypt(json_encode($response),env('API_KEY'));
                return response($encryptedData,200);
            }
            catch (\Exception $e)
            {
                return response()->json(['message'=>$e->getMessage(),'status'=>'fail'],401);
            }
    }
    //Functions to Save Progress in Database
    public function saveProgress()
    {
        try
        {
            $decryptedData=EncryptionHelper::decrypt(file_get_contents('php://input'),env('API_KEY'));
            $data=json_decode($decryptedData,true);
            $token=$data['token']??null;
            if(!$token){
                return response()->json(['message' => 'Token is missing','status'=>'fail'], 401);
            }
            $user = PersonalAccessToken::findToken($token)->tokenable;
            if (!$user) {
                    return response()->json(['message' => 'Invalid or expired token','status'=>'fail'], 401);
                }
            $block=$data['block'];
            $gp=$data['gp'];
            $district=$data['district'];
            $remarks=$data['remarks'];
            $completion=$data['completion_percentage'];
            $currentDate=now();
            $formatDate=$currentDate->format('Y-m-d H:i:s');
            $progress = Progress::where('gp', $gp)
            ->where('block', $block)
            ->where('district', $district)
            ->first();

            if(!$progress)
            {
                $progress=new Progress;
                $progress->completion_percentage=$completion;
                $progress->remarks=$remarks;
                $progress->gp=$gp;
                $progress->block=$block;
                $progress->district=$district;
                $progress->p_update=$formatDate;
                if($progress->save())
                {
                    $response=['message'=>"Progress Added Successfully",
                    'status'=>'success'];
                }
                else
                {
                    $response=['message'=>"Something went wrong",
                    'status'=>'fail'];
                }
                $encryptedData=EncryptionHelper::encrypt(json_encode($response),env('API_KEY'));
                return response($encryptedData,200);
            }
            else if($progress->completion_percentage==='-1')
            {
                $progress->completion_percentage=$data['completion_percentage'];
                $progress->p_update=$formatDate;
                $progress->update();
                if($progress->save())
                {
                    $response=['message'=>"Progress Added Successfully",
                    'status'=>'success'];
                }
                else
                {
                    $response=['message'=>"Something went wrong",
                    'status'=>'fail'];
                }
                $encryptedData=EncryptionHelper::encrypt(json_encode($response),env('API_KEY'));
                return response($encryptedData,200);
            }

        }   
        catch(\Exception $e)
        {
            return response()->json(['message'=>$e->getMessage(),'status'=>'fail'],401);
        }
    }

    // Function to View Page of Update Progress
    public function updateProgress()
    {
        try
        {
            $decryptedData=EncryptionHelper::decrypt(file_get_contents('php://input'),env('API_KEY'));
            $data=json_decode($decryptedData,true);
            $token=$data['token']??null;
            if(!$token){
                return response()->json(['message' => 'Token is missing','status'=>'fail'], 401);
            }
            $user = PersonalAccessToken::findToken($token)->tokenable;
            if (!$user) {
                return response()->json(['message' => 'Invalid or expired token','status'=>'fail'], 401);
            }
            $block=$data['block'];
            $gp=$data['gp'];
            $district=$data['district'];
            $progress=Progress::where('gp',$gp)->where('block',$block)->where('district',$district)->first();
            $sanction=Sanction::where('gp',$gp)->where('block',$block)->where('district',$district)->get();

            if($progress->count()===0)
            {
                $response=[
                    'message'=>'No Progress Found with these details',
                    'status'=>'info',
                    'data'=>null
                ];
            }
            else
            {
                $images=$progress->image;
                $work_started_image=null;
                $work_partial_image=null;
                $work_completed_image=null;
                if($images && $images->count()>0 && $images->work_started_image)
                {
                    $work_started_image=url('uploads/images/'.$images->work_started_image);
                }

                if($images && $images->count()>0 && $images->work_partial_image)
                {
                    $work_partial_image=url('uploads/images/'.$images->work_partial_image);
                }

                if($images && $images->count()>0 && $images->work_completed_image)
                {
                    $work_completed_image=url('uploads/images/'.$images->work_completed_image);
                }


                $sanAmount=0;
                foreach($sanction as $san)
                {
                    $sanAmount+=$san->san_amount;
                }
                $response=[
                    'message'=>'Progress fetched successfully',
                    'status'=>'success',
                    'data'=>['district'=>$progress->district,
                            'block'=>$progress->block,
                            'gp'=>$progress->gp,
                            'amount'=>$sanAmount,
                            'status'=>$progress->completion_percentage,
                            'work_started_image'=>$work_started_image,
                            'work_partial_image'=>$work_partial_image,
                            'work_completed_image'=>$work_completed_image,
                        ],
                ];
            }
            $encryptedData=EncryptionHelper::encrypt(json_encode($response),env('API_KEY'));
            return response($encryptedData,200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message'=>$e->getMessage(),'status'=>'fail'],401);
        }
    }

    // Function to Update Progress in Database

    public function updateDBProgress()
    {
        try
        {
            
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>$e->getMessage(),'status'=>'fail'],401);
        }
    }

}
