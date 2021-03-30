<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Excel;

use App\Imports\PelangganImport;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $user;
    protected $file;
    public function __construct($data)
    {
        $this->file = $data['file'];
        $this->user = $data['user'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $excel = Storage::disk('s3')->get($this->file);
        $data = Excel::import(new \App\Imports\PelangganImport($this->user), $this->file, 's3'); //IMPORT FILE
        Storage::disk('s3')->delete($this->file);
    }
}
