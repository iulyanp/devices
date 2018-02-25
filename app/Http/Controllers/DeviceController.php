<?php

namespace App\Http\Controllers;

use App\Device;
use App\DeviceType;
use App\Services\Sender;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::with(['type.values'])->get()->toArray();

        foreach ($devices as &$device) {
            $device = $this->formatDevices($device);
        }

        return response()->json($devices);
    }

    public function show($id)
    {
        $device = Device::with(['type.values'])->where('id', $id)->firstOrFail()->toArray();

        if (!$device) {
            return response()->json(
                [
                    'error' => 'Resource not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $device = $this->formatDevices($device);

        return response()->json($device, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        try {
            $deviceType = $this->findOrCreateDeviceType($request);
            $deviceData = $request->validate(
                [
                    'name' => 'required|string|max:100',
                    'value' => 'required|int|max:100|in:' . $deviceType->values->implode('value', ', '),
                    'unit' => 'required|max:100',
                ]
            );
        } catch (\Exception $e) {
            return $this->jsonErrors($e);
        }

        $deviceData['type_id'] = $deviceType->id;

        $createdDevice = Device::create($deviceData);
        $device = $createdDevice->toArray();
        $device['type'] = $createdDevice->type->toArray();
        $device['type']['values'] = $createdDevice->type->values->toArray();
        $device = $this->formatDevices($device);

        (new Sender())->sendMessage("create-device", $device);

        return response()->json($device, Response::HTTP_CREATED);
    }

    public function update(Request $request, int $id)
    {
        $device = Device::with('type.values')->findOrFail($id);

        try {
            $data = $request->validate(
                [
                    'name' => 'string|max:100',
                    'value' => 'required|int|max:100|in:' . $device->type->values->implode('value', ', '),
                    'unit' => 'max:100',
                ]
            );
        } catch (\Exception $e) {
            return $this->jsonErrors($e);
        }

        $device->update($data);

        $device = $this->formatDevices($device->toArray());

        (new Sender())->sendMessage("update-device", $device);

        return response()->json($device, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);

        (new Sender())->sendMessage("delete-device", $device->toArray());

        $device->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function formatDevices($device)
    {
        foreach ($device['type']['values'] as $value) {
            $device['values'][$value['value']] = $value['label'];
        }

        $device['type'] = $device['type']['name'];
        $device['unit'] = sprintf($device['unit'], $device['values'][$device['value']]);

        return $device;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    private function findOrCreateDeviceType(Request $request)
    {
        $type = $request->validate(
            [
                'type' => 'required|string|max:100',
                'values' => 'required|array',
            ]
        );

        $deviceType = DeviceType::where('name', '=', $type)->first();

        if ($deviceType) {
            return $deviceType;
        }

        $deviceType = DeviceType::create(['name' => $type['type']]);

        $values = [];
        foreach ($type['values'] as $value => $label) {
            $values[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        $deviceType->values()->createMany($values);

        return $deviceType;
    }

    /**
     * @param $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function jsonErrors($e): \Illuminate\Http\JsonResponse
    {
        return response()->json(['data' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}
