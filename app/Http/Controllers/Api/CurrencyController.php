<?php

namespace App\Http\Controllers\Api;

use App\Contracts\CurrencyLayer\CurrencyLayerContract;
use App\Exceptions\CurrencyLayer\ExceptionHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CurrencyLayer\ConvertRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(CurrencyLayerContract $currencyLayerService){
        try{
            $response = $currencyLayerService->getList();
        }catch(ExceptionHandler $e){
            return new ApiErrorResponse($e,$e->getMessage());
        }catch(\Exception $e){
            return new ApiErrorResponse($e,'Sorry, something went wrong!');
        }

        return new ApiSuccessResponse($response,['message' => 'List currencies successful!']);
    }

    public function convert(ConvertRequest $request, CurrencyLayerContract $currencyLayerService){
        try{
            $response =collect($request->input('currencies'))->map(function($item) use ($request, $currencyLayerService){
                $result = $currencyLayerService->convert($request->amount,$item);
                return [
                    "from" => $result->getQuery()['from'],
                    "to" => $result->getQuery()['to'],
                    "amount" => $result->getQuery()['amount'],
                    "result" => $result->getResult()
                ];
            });
             ;
        }catch(ExceptionHandler $e){
            return new ApiErrorResponse($e,$e->getMessage());
        }catch(\Exception $e){
            return new ApiErrorResponse($e,'Sorry, something went wrong!');
        }

        return new ApiSuccessResponse($response,['message' => 'List currencies successful!']);
    }
}
