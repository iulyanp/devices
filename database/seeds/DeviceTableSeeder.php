<?php

use App\Device;
use App\DeviceType;
use App\DeviceTypeValues;
use Illuminate\Database\Seeder;

class DeviceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DeviceTypeValues::truncate();
        DeviceType::truncate();
        Device::truncate();
        Schema::enableForeignKeyConstraints();

        DeviceType::insert([
            [
                'name' => 'light-switch',
            ],
            [
                'name' => 'dimmable',
            ],
            [
                'name' => 'thermostat',
            ]
        ]);
        $typeIds = DeviceType::all()->pluck('id')->toArray();

        Device::insert([
            [
                'name' => 'Thermostat',
                'value' => 22,
                'unit' => 'Temperature %s°C',
                'type_id' => $typeIds[2]
            ],
            [
                'name' => 'Light Switch',
                'value' => 1,
                'unit' => 'Status %s',
                'type_id' => $typeIds[0]
            ],
            [
                'name' => 'Dimmable light',
                'value' => 75,
                'unit' => 'Level %s%%',
                'type_id' => $typeIds[1]
            ]
        ]);

        for ($i = 0; $i <= 60; $i++) {
            $thermostat[] = [
                'value' => $i,
                'label' => $i,
                'type_id' => $typeIds[2],
            ];
        }

        DeviceTypeValues::insert($thermostat);

        for ($i = 10; $i <= 100; $i+=5) {
            $dimmable[] = [
                'value' => $i,
                'label' => $i,
                'type_id' => $typeIds[1],
            ];
        }
        DeviceTypeValues::insert($dimmable);

        $lightSwitch = [
            [
                'value' => 1,
                'label' => 'On',
                'type_id' => $typeIds[0],
            ],
            [
                'value' => 0,
                'label' => 'Off',
                'type_id' => $typeIds[0],
            ],
        ];
        DeviceTypeValues::insert($lightSwitch);
    }
}
