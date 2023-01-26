<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportRequests\ReportRequestStoreRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\ReportRequest;
use Exception;


class ReportRequestController extends Controller
{
    public function index(){
        try{
            $reports = ReportRequest::whereUserId( auth()->user()->id )->get(['id','currencies','amount','type','status', 'created_at']);
        }catch(Exception $e){
            return new ApiErrorResponse($e,'Unable to process your request!');
        }
        return new ApiSuccessResponse($reports,['message'=> 'Request process successful!']);
    }

    public function store (ReportRequestStoreRequest $request){
        try{
            $report = ReportRequest::create(
                array_merge($request->validated(),['user_id'=>auth()->user()->id])
            );
        }catch(Exception $e){
            return new ApiErrorResponse($e , "Unable to process your request!");
        }
        return new ApiSuccessResponse($report,['message'=> 'Report Request created successful!']);
    }

    public function details ( ReportRequest $reportRequest){
        try{
            return new ApiSuccessResponse($reportRequest ->report,[]);
        }catch(Exception $e){
            return new ApiErrorResponse($e , "Unable to process your request!");
        }

    }
}
