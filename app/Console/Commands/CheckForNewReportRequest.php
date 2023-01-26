<?php

namespace App\Console\Commands;

use App\Jobs\ReportRequests\ProcessReportRequestJob;
use App\Models\ReportRequest;
use Illuminate\Console\Command;

class CheckForNewReportRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:checkfornewreportrequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This commands dispatch process report request job for new reports with pending status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ReportRequest::whereStatus(ReportRequest::PENDING)->chunkById(100, function($reports){
            $reports->map(fn ($report) => ProcessReportRequestJob::dispatch($report->id));
        });
        return Command::SUCCESS;
    }
}
