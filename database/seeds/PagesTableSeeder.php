<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
                    ["Bird","58466df72a32a.png", 1],
                    ["Addax","58466e06ace30.png", 1],
                    ["Ant","58466e0fc7741.png", 1],
                    ["Bird","58466e25940db.png", 1],
                    ["Bird Head","58466e31f3cca.png", 1],
                    ["Deer","58466e3c8f33d.png", 1],
                    ["Dragon","58466e4768af1.png", 1],
                    ["Dog","58466e510d723.png", 1],
                    ["Elephant","58466e5d29426.png", 1],
                    ["Goa","58466e6e4e54e.png", 1],
                    ["Horse","58466e7943225.png", 1],
                    ["Impala","58466e845a768.png", 1],
                    ["Bears","58466e90a154f.png", 1],
                    ["Ant","58466e9ca9a5b.png", 1],
                    ["Teddy Bear","58466ea9b938f.png", 1],
                    ["Dog","58466eb94dab1.png", 1],
                    ["Cow","58466ec4d6cf5.png", 1],
                    ["Fox","58466ece31bd9.png", 1],
                    ["Rooster","58466ee0e1969.png", 1],
                    ["Bird","58466eeb747a2.png", 1],
                    ["Polar Bear","58466ef7911df.png", 1],
                    ["Crocodile","58466f0e75698.png", 1],
                    ["Elephant","58466f1c0329a.png", 1],
                    ["Leopards","58466f33abed6.png", 1],
                    ["Moth","58466f3e86973.png", 1],
                    ["Oryx","58466f4ad987f.png", 1],
                    ["Antelope","58466f6051b58.png", 1],
                    ["Alligator","58466f6b8d9de.png", 1],
                    ["Antelope","58466f804c5dc.png", 1],
                    ["Antelope","58466f911c8cf.png", 1],
                    ["Aurochs","58466fb4abb50.png", 1],
                    ["Baboon","58466fd56a39d.png", 1],
                    ["Badger","58466fe216969.png", 1],
                    ["Dog","58466fee542d8.png", 1],
                    ["Bear","58466ff888558.png", 1],
                    ["Bird","5846700648d0b.png", 1],
                    ["Bird","584670107ce35.png", 1],
                    ["Dinosaur - Brontosaurus","5846702ec52ff.png", 1],
                    ["Camel","5846703bae77c.png", 1],
                    ["Bird","584670459f543.png", 1],
                    ["Cheetah","58467050ba0b3.png", 1],
                    ["Chipmunk","5846705cbfa90.png", 1],
                    ["Dragon","5846706a747b8.png", 1],
                    ["Polar Bear","58467077b9764.png", 1],
                    ["Dinosaur","584670824d07b.png", 1],
                    ["Dragon","58467093c0585.png", 1],
                    ["Dragon","5846709f6f5a2.png", 1],
                    ["Dinosaur","584670accf2d9.png", 1],
                    ["Dinosaur","584670b828609.png", 1],
                    ["Animals","584670c262a81.png", 1]
                ];



        foreach($pages as $page){
            DB::table('pages')->insert([
                'name' => $page[0],
                'user_id' => 1,
                'book_id' => $page[2],
                'outline_url' => $page[1],
                'colored_url' => '',
                'created_at' => Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            ]);
        }

        
    }
}





