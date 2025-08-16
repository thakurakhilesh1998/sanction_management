<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sanction;

class ProgressController extends Controller
{
    public function getData($role,$zone)
    {
        if($role=='xen')
        {
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
            else
            {
                return response()->json(['error' => 'Data not found'], 401);
            }
            $sanctions=$sanctionQuery->get();
            
            $response=[];

            foreach ($sanctions as $san) {
                if (!in_array($san->gp, array_column($response, 'gp'))) {
                    $lastUpdateDate = isset($san->progress) ? \Carbon\Carbon::parse($san->progress->updated_at) : null;
                    $currentDate = \Carbon\Carbon::now();
                    $days = isset($lastUpdateDate) ? $lastUpdateDate->diffInDays($currentDate) : null;
        
                    $workStatus = isset($san->progress) 
                        ? ($san->progress->completion_percentage === 'Work Completed' 
                            ? 'Work Completed' 
                            : $san->progress->completion_percentage)
                        : 'No Progress Added';
        
                    $response[] = [
                        'district_name' => $san->district,
                        'block_name' => $san->block,
                        'gp_name' => $san->gp,
                        'work_status' => $workStatus,
                        'delay_in_works' => $days !== null ? $days : 'N/A',
                    ];
                }
            }
            if($response==null)
            {
                return response()->json(['error' => 'Data not found'], 401);
            }
            else
            {
                return response()->json($response); // Return the JSON response
            }
            
        }
        else
        {
            return response()->json(['error' => 'Data not found'], 401);
        }
        
        
    }

    public function getSanctionDetails($gp,$block,$district)
    {   
        $sanction=Sanction::where('district',$district)->where('block',$block)->where('gp',$gp)->with('progress')->get();
        $sanctions=[];
        foreach($sanction as $san)
        {
            $sanctions[]=[
                'district_name'=>$san->district,
                'block'=>$san->block,
                'gp'=>$san->gp,
                'total_amount'=>$san->san_amount,
                'financial-year'=>$san->financial_year,
                'sanction_purpose'=>$san->sanction_purpose,
                'sanction_head'=>$san->sanction_head,
                'sanction_date'=>$san->sanction_date,
            ];
        }
        return response()->json($sanctions);
    }
}
