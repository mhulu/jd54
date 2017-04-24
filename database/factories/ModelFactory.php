<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function ( $faker) {
	$faker = Faker\Factory::create('zh_CN');
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'status' => 1,
        'mobile' => $faker->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10)
    ];
});

$factory->define(App\Pop::class, function ( $faker) {
    $faker = Faker\Factory::create('zh_CN');
    return [
        'identify' =>$faker->isbn13(),
        'password' => bcrypt('secret'),
        'name' => $faker->name(),
        'paytype' => $faker->randomElement($array = ['城镇职工基本医疗保险', '城镇居民基本医疗保险', '新型农村合作医疗', '贫困救助', '商业医疗保险', '全公费', '全自费']),
        'sex' => $faker->randomElement($array = [0, 1]),
        'phone' => $faker->phoneNumber,
        'livetype' => $faker->randomElement($array = [0, 1]),
        'nation' => $faker->randomElement($array = ['汉族', '回族', '维吾尔族', '藏族', '鄂伦春族', '满族']),
        'address' => $faker->address,
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});

$factory->define(App\PopArchive::class, function ($faker) {
    $faker = Faker\Factory::create('zh_CN');
    return [
        'check_unit' => $faker->randomElement($array = array ('门诊','科室二','科室三')),
        'doctor' => $faker->name(),
        'data' => [
            'common' => [
                'temprature' => $faker->randomElement($array = [35, 36, 37, 38]), 
                'pulse' => $faker->numberBetween($min = 50, $max =110),
                'breathe' => $faker->numberBetween($min = 50, $max =110),
                'bloodpressure_left' => $faker->numberBetween($min = 50, $max =110). '/' .$faker->numberBetween($min = 50, $max =110),
                'bloodpressure_right' => $faker->numberBetween($min = 50, $max =110). '/' .$faker->numberBetween($min = 50, $max =110),
                'height' => $faker->numberBetween($min = 150, $max =190),
                'weight' => $faker->numberBetween($min = 30, $max =100),
                'waistline' => $faker->numberBetween($min = 30, $max =150),
                'health_status' => $faker->numberBetween($min = 0, $max = 5),
                'care_status' => $faker->numberBetween($min = 0, $max = 4),
                'cognative' => $faker->numberBetween($min = 1, $max = 2),
                'emotion' => $faker->numberBetween($min = 1, $max = 2)
            ],
            'aid' => [
                'blood' => [
                    'hemoglobin' => $faker->numberBetween($min = 50, $max = 200),
                    'leukocyte' => $faker->numberBetween($min = 300, $max = 10000),
                    'platelet' => $faker->numberBetween($min = 10, $max = 500)
                ],
                'urine' => [
                    'protein' => $faker->numberBetween($min = 0, $max = 1),
                    'sugar' => $faker->numberBetween($min = 0, $max = 1),
                    'ketone' => $faker->numberBetween($min = 0, $max = 1),
                    'occult' => $faker->numberBetween($min = 0, $max = 1)
                ],
                'blood_sugar' => $faker->numberBetween($min = 20, $max = 500),
                'ecg' => $faker->numberBetween($min = 0, $max = 1),
                'liver' => [
                    'alt' => $faker->numberBetween($min = 20, $max = 500),
                    'ast' => $faker->numberBetween($min = 20, $max = 500),
                    'alb' => $faker->numberBetween($min = 1, $max = 9),
                    'tbl' => $faker->numberBetween($min = 3, $max = 18),
                    'bl' => $faker->numberBetween($min = 3, $max = 18)
                ],
                'kidney' => [
                    'scr' => $faker->numberBetween($min = 10, $max = 150),
                    'bun' => $faker->numberBetween($min = 1, $max = 18),
                    'ka' => $faker->numberBetween($min = 1, $max = 18),
                    'na' => $faker->numberBetween($min = 10, $max = 180),
                ],
                'lipids' => [
                    'tc' => $faker->numberBetween($min = 1, $max = 10),
                    'trig' => $faker->numberBetween($min = 1, $max = 10),
                    'ldl' => $faker->numberBetween($min = 1, $max = 18),
                    'hdl' => $faker->numberBetween($min = 1, $max = 18)
                ],
                'bscan' => $faker->numberBetween($min = 0, $max = 1)
            ],
            'diagnosis' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
            'guide' => $faker->numberBetween($min = 1, $max = 4),
            'control' => $faker->numberBetween($min = 1, $max = 6)
        ]
    ];
});
