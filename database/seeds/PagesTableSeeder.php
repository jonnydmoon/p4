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
        // Add all the public pages.
        $pages = [
                    ["Bird", "58466df72a32a.png", 1 ],
                    ["Addax", "58466e06ace30.png", 1 ],
                    ["Ant", "58466e0fc7741.png", 1 ],
                    ["Bird", "58466e25940db.png", 1 ],
                    ["Bird Head", "58466e31f3cca.png", 1 ],
                    ["Deer", "58466e3c8f33d.png", 1 ],
                    ["Dragon", "58466e4768af1.png", 1 ],
                    ["Dog", "58466e510d723.png", 1 ],
                    ["Elephant", "58466e5d29426.png", 1 ],
                    ["Goa", "58466e6e4e54e.png", 1 ],
                    ["Horse", "58466e7943225.png", 1 ],
                    ["Impala", "58466e845a768.png", 1 ],
                    ["Bears", "58466e90a154f.png", 1 ],
                    ["Ant", "58466e9ca9a5b.png", 1 ],
                    ["Teddy Bear", "58466ea9b938f.png", 1 ],
                    ["Dog", "58466eb94dab1.png", 1 ],
                    ["Cow", "58466ec4d6cf5.png", 1 ],
                    ["Fox", "58466ece31bd9.png", 1 ],
                    ["Rooster", "58466ee0e1969.png", 1 ],
                    ["Bird", "58466eeb747a2.png", 1 ],
                    ["Polar Bear", "58466ef7911df.png", 1 ],
                    ["Crocodile", "58466f0e75698.png", 1 ],
                    ["Elephant", "5850b93362f79.png", 1 ],
                    ["Leopards", "58466f33abed6.png", 1 ],
                    ["Moth", "58466f3e86973.png", 1 ],
                    ["Oryx", "58466f4ad987f.png", 1 ],
                    ["Antelope", "58466f6051b58.png", 1 ],
                    ["Alligator", "58466f6b8d9de.png", 1 ],
                    ["Antelope", "58466f804c5dc.png", 1 ],
                    ["Antelope", "58466f911c8cf.png", 1 ],
                    ["Aurochs", "58466fb4abb50.png", 1 ],
                    ["Baboon", "58466fd56a39d.png", 1 ],
                    ["Badger", "58466fe216969.png", 1 ],
                    ["Dog", "58466fee542d8.png", 1 ],
                    ["Bear", "58466ff888558.png", 1 ],
                    ["Bird", "5846700648d0b.png", 1 ],
                    ["Bird", "584670107ce35.png", 1 ],
                    ["Dinosaur - Brontosaurus", "5846702ec52ff.png", 1 ],
                    ["Camel", "5846703bae77c.png", 1 ],
                    ["Bird", "584670459f543.png", 1 ],
                    ["Cheetah", "58467050ba0b3.png", 1 ],
                    ["Chipmunk", "5846705cbfa90.png", 1 ],
                    ["Dragon", "5846706a747b8.png", 1 ],
                    ["Polar Bear", "58467077b9764.png", 1 ],
                    ["Dinosaur", "584670824d07b.png", 1 ],
                    ["Dragon", "58467093c0585.png", 1 ],
                    ["Dragon", "5846709f6f5a2.png", 1 ],
                    ["Animals", "584670c262a81.png", 1 ],
                    ["Boat", "58479845d253f.png", 2 ],
                    ["Boat", "58479850c2179.png", 2 ],
                    ["Boat", "5847985c85b4c.png", 2 ],
                    ["Boat", "58479865bcc1b.png", 2 ],
                    ["Boat", "584798717df7b.png", 2 ],
                    ["Plane", "5847987e8428d.png", 2 ],
                    ["Plane", "58479887bd719.png", 2 ],
                    ["Plane", "584798b5bcef1.png", 2 ],
                    ["Plane", "584798be857ea.png", 2 ],
                    ["Plane", "584798c678d26.png", 2 ],
                    ["Plane", "584798ce81cc5.png", 2 ],
                    ["Plane", "584798d6bea9a.png", 2 ],
                    ["Plane", "584798dee40bc.png", 2 ],
                    ["Plane", "584798e779123.png", 2 ],
                    ["Plane", "584798f0ab443.png", 2 ],
                    ["Plane", "584798fa24c08.png", 2 ],
                    ["Plane", "584799024a1fe.png", 2 ],
                    ["Plane", "5847990b6aa52.png", 2 ],
                    ["Rocket", "58479915c06d8.png", 2 ],
                    ["Rocket", "5847991e82667.png", 2 ],
                    ["Train", "58479926b5aa8.png", 2 ],
                    ["Train", "5847992e6824e.png", 2 ],
                    ["Tractor", "5847993d629fb.png", 2 ],
                    ["Tractor", "5847994a54b28.png", 2 ],
                    ["Tractor", "5847995408294.png", 2 ],
                    ["Tractor", "5847995fe679f.png", 2 ],
                    ["Firetruck", "5847996ad03ff.png", 2 ],
                    ["Car", "584799748aeb5.png", 2 ],
                    ["Car", "58479980b0816.png", 2 ],
                    ["Car", "5847998957273.png", 2 ],
                    ["Car", "5847999265177.png", 2 ],
                    ["Car", "5847999be1a9b.png", 2 ],
                    ["Car", "584799a48a500.png", 2 ],
                    ["Pattern", "58479c0e30695.png", 4 ],
                    ["Pattern", "58479c156a9e6.png", 4 ],
                    ["Pattern", "58479c1d028eb.png", 4 ],
                    ["Pattern", "58479c243434d.png", 4 ],
                    ["Pattern", "58479c2c06354.png", 4 ],
                    ["Pattern", "58479c344fbac.png", 4 ],
                    ["Pattern", "58479c3b5080d.png", 4 ],
                    ["Pattern", "58479c41e9c8a.png", 4 ],
                    ["Pattern", "58479c4895baf.png", 4 ],
                    ["Pattern", "58479c4e9020e.png", 4 ],
                    ["Pattern", "58479c554b716.png", 4 ],
                    ["Pattern", "58479c5c960b3.png", 4 ],
                    ["Pattern", "58479c646bb97.png", 4 ],
                    ["Pattern", "58479c6bf0de5.png", 4 ],
                    ["Pattern", "58479c72b60bc.png", 4 ],
                    ["Pattern", "58479c7983e6f.png", 4 ],
                    ["Pattern", "58479c8783042.png", 4 ],
                    ["Pattern", "58479c8e87336.png", 4 ],
                    ["Pattern", "58479c962f4d5.png", 4 ],
                    ["Pattern", "58479c9cc235b.png", 4 ],
                    ["Pattern", "58479ca4c3574.png", 4 ],
                    ["Pattern", "58479cac404e9.png", 4 ],
                    ["Pattern", "58479cb27a2b7.png", 4 ],
                    ["Pattern", "58479cb9ae20b.png", 4 ],
                    ["Pattern", "58479cc303d1d.png", 4 ],
                    ["Pattern", "58479ccb39f51.png", 4 ],
                    ["Pattern", "58479cd2bd524.png", 4 ],
                    ["Pattern", "58479cdc4991b.png", 4 ],
                    ["Pattern", "58479ce34eb65.png", 4 ],
                    ["Flower", "58479d0aaa468.png", 5 ],
                    ["Cactus", "58479d16032ad.png", 5 ],
                    ["Tree", "58479d1e0f1ac.png", 5 ],
                    ["Tree", "58479d2587e2d.png", 5 ],
                    ["Flower", "58479d2f2e15c.png", 5 ],
                    ["Plant", "58479d36d2e8a.png", 5 ],
                    ["Plant", "58479d4070779.png", 5 ],
                    ["Plant", "58479d4855a4a.png", 5 ],
                    ["Flowers", "58479d527f7db.png", 5 ],
                    ["Flowers", "58479d5a79477.png", 5 ],
                    ["Flowers", "58479d62132f1.png", 5 ],
                    ["Flowers", "58479d6a16e48.png", 5 ],
                    ["Flowers", "58479d72b9b0a.png", 5 ],
                    ["Flowers", "58479d7ad0a62.png", 5 ],
                    ["Flowers", "58479d82b43d2.png", 5 ],
                    ["Flowers", "58479d8a22fdd.png", 5 ],
                    ["Flowers", "58479d9270fda.png", 5 ],
                    ["Flowers", "58479d9a8cafb.png", 5 ],
                    ["Building", "58479dcac6768.png", 3 ],
                    ["City", "58479dd328c43.png", 3 ],
                    ["Cabin", "584815b68c26b.png", 3 ],
                    ["Cabin", "584815bf8e273.png", 3 ],
                    ["Cabin", "584815c86ad83.png", 3 ],
                    ["Viking", "584815d5c3b36.png", 3 ],
                    ["Cabin", "584815e11ba33.png", 3 ],
                    ["Bridge", "584815eaf047f.png", 3 ],
                    ["Pyramid", "584815fa3154c.png", 3 ],
                    ["Pyramid", "5848160192c98.png", 3 ],
                    ["Pyramid", "5848160970adc.png", 3 ],
                    ["Pyramid", "5848161269ba7.png", 3 ],
                    ["City", "5848161ad4619.png", 3 ],
                    ["City", "58481623ef3b7.png", 3 ],
                    ["House", "5848162d19696.png", 3 ],
                    ["House", "584816357c178.png", 3 ],
                    ["Building", "58481640ae2f9.png", 3 ],
                    ["Building", "5848164ca51df.png", 3 ],
                    ["Building", "584816552ba97.png", 3 ],
                    ["Building", "5848165e47af1.png", 3 ],
                    ["House", "5848166820923.png", 3 ],
                    ["Building", "5848167156030.png", 3 ],
                    ["Cabin", "5848167c8f4a2.png", 3 ],
                    ["Cabin", "584816869a15a.png", 3 ],
                    ["Cabin", "58481690e3f57.png", 3 ],
                    ["House", "584816a0d56b8.png", 3 ],
                    ["House", "584816a95a769.png", 3 ],
                    ["House", "584816b10c0e1.png", 3 ],
                    ["House", "584816b92ff39.png", 3 ],
                    ["House", "584816c08a546.png", 3 ],
                    ["House", "584816c959fcf.png", 3 ]
                ];

        foreach($pages as $page){
            DB::table('pages')->insert([
                'name' => $page[0],
                'user_id' => 1,
                'book_id' => $page[2],
                'outline_url' => $page[1],
                'colored_url' => null,
                'created_at' => Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            ]);
        }

        // Add the pages for Jamal and Jill
        $pages = [
            ["Jill's Flower","58479cdc4991b.png","584ff990c0fd8.png",NULL,2],
            ["Horse","58466e7943225.png","585002ac38508.png",NULL,2],
            ["Flower","58479d2f2e15c.png","585018a8eb301.png","6", 2],
            ["Cardinal","584670459f543.png","5850b373c1377.png","6", 2],
            ["House","584816a95a769.png","5850b431bdb3f.png","7", 3],
            ["House","584816c08a546.png","5850b6a61dd5e.png","7", 3],
            ["Car","5847999265177.png","5850b81ad8efd.png",NULL, 3],
            ["Elephant","58466f1c0329a.png","5850b848678a4.png",NULL, 3]
        ];

        foreach($pages as $page){
            DB::table('pages')->insert([
                'name' => $page[0],
                'user_id' => $page[4],
                'book_id' => $page[3],
                'outline_url' => $page[1],
                'colored_url' => $page[2],
                'created_at' => Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
            ]);
        }  
    }
}





