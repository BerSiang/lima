<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;

class TaipeiLandValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:tpevalue {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filePath = $this->argument("file");
        $fp = fopen($filePath, 'r');
        $list = [];
        fgetcsv($fp, 1024, ',');
        $num = 0;
        while($row = fgetcsv($fp, 1024, ',')) {
            array_push($list, [
                "regions" => [trim($row[0], ' '), trim($row[1], ' ')],
                "pracellary" => $row[2],
                "pracel_id" => $row[3],
                "公告土地現值" => $row[4],
                "公告地價" => $row[5]
            ]);
            $num++;
        }
        foreach($list as $record) {
            \App\Model\ExternalData\TaipeiLandValue::create($record);
        }
    }
}
