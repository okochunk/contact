<?php

namespace App\Console\Commands;

use App\Products;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid as Generator;

class importProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import product from csv';

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
        $filename = storage_path('product_list.csv');
        $delimiter = ';';

        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header      = NULL;
        $data        = [];
        $lengthArray = [];

        if (($handle = fopen($filename, 'r')) !== FALSE) {
            $index = 0;

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {

                if (isset($row[1]) && !empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3]) && !empty($row[4]) ) {
                    $lengthArray[] = count($row);

                    $internal_code = $row[0];
                    $name          = $row[1];
                    $category      = $row[2];
                    $quantity      = $row[3];
                    $price         = str_replace(',', '.', $row[4]);


                    $data[] = [
                        'name'          => $name,
                        'uuid'          => Generator::uuid4()->toString(),
                        'category_id'   => $category,
                        'quantity'      => $quantity,
                        'price'         => $price,
                        'is_active'     => 1,
                        'internal_code' => $internal_code,
                        'media_id'      => 0
                    ];
                }

                $index++;
            }

            fclose($handle);
        }

        Products::truncate();
        Products::insert($data);

    }
}
