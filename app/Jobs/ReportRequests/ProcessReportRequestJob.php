<?php

namespace App\Jobs\ReportRequests;

use App\Contracts\CurrencyLayer\CurrencyLayerContract;
use App\Models\Report;
use App\Models\ReportRequest;
use App\Services\CurrencyLayer\Entities\CurrencyChange;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessReportRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private int $reportID
    )
    {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $report = ReportRequest::findOrFail($this->reportID);
        if ($report){
            $currencyLayerService = app()->make(CurrencyLayerContract::class);
            try{
                DB::beginTransaction();
                $result = match ($report->type) {
                    ReportRequest::ONE_YEAR => $this->handleOneYear($report, $currencyLayerService),
                    ReportRequest::ONE_MONTH =>  $this->handleOneMonth($report, $currencyLayerService),
                    ReportRequest::SIX_MONTH =>  $this->handleSixMonth($report, $currencyLayerService),
                    default => new Exception("Type not Implemented: ". $report->type),
                };
                DB::commit();
            }catch (\Exception $e){
                DB::rollBack();
                $report->status = ReportRequest::ERROR;
                $report->save();
                throw $e;
            }

        }



    }

    private function handleOneYear(ReportRequest $report, CurrencyLayerContract $currencyLayerService){
        $period =Carbon::now()->subMonth(12)->toPeriod(now()->subMonthNoOverflow(1), 1, 'months');

        foreach ($period->toArray() as $start){
            $this->saveReport(
                $currencyLayerService->getChange(
                    $start->format('Y-m-d'),
                    $start->addMonthNoOverflow(1)->format('Y-m-d'),
                    $report->currencies
                )
            );
        }
        $report->status = ReportRequest::READY;
        $report->save();
    }

    private function handleOneMonth(ReportRequest $report, CurrencyLayerContract $currencyLayerService){
        $period = CarbonPeriod::create( Carbon::now()->subMonth(1), Carbon::now()->subDays(1));
        foreach ($period->toArray() as $start){
            $this->saveReport(
                $currencyLayerService->getChange(
                    $start->format('Y-m-d'),
                    $start->addDays(1)->format('Y-m-d'),
                    $report->currencies
                )
            );
        }
        $report->status = ReportRequest::READY;
        $report->save();
    }

    private function handleSixMonth(ReportRequest $report, CurrencyLayerContract $currencyLayerService){

            $period = Carbon::now()->subMonth(6)->toPeriod(now()->subWeeks(1), 1, 'weeks');
            foreach ($period->toArray() as $start){
                $this->saveReport(
                    $currencyLayerService->getChange(
                        $start->format('Y-m-d'),
                        $start->addWeeks(1)->format('Y-m-d'),
                        $report->currencies
                    )
                );
            }
            $report->status = ReportRequest::READY;
            $report->save();
    }

    private function saveReport(CurrencyChange $currencyChange){
        $data = $currencyChange->toArray();
        unset($data['success']);
        $data['report_request_id'] = $this->reportID;
        Report::create($data);
    }
}
