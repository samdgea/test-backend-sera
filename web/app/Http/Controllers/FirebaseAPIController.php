<?php

namespace App\Http\Controllers;

use Firebase\FirebaseLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseAPIController extends Controller
{
    public $firebase;

    public function __construct() {
        $this->firebase = new FirebaseLib(env('FIREBASE_DB_URL'), env('FIREBASE_DB_TOKEN'));
    }

    public function getTODO($id) {
        $data = json_decode(($this->firebase)->get(auth()->user()->id . '/TODO/' . $id));

        if (!empty($data))
            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to get data with that ID, looks like the data is doesn\'t exist anymore'
        ], 404);
    }

    public function getTODOList(Request $request)
    {
        $data = json_decode(($this->firebase)->get(auth()->user()->id . '/TODO'));

        if (!isset($data->error))
            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        return response()->json([
            'success' => false,
            'message' => $data->error
        ], 400);
    }

    public function postAddTODOList(Request $request) {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $data = [
            'title' => $request->title,
            'desc' => (!empty($request->desc)) ? $request->desc : null,
            'date' => (!empty($request->date)) ? $request->date : now(),
            'is_done' => false
        ];

        $response = json_decode(($this->firebase)->push(env('FIREBASE_URL') . auth()->user()->id . '/TODO', $data));

        $data['id'] = $response->name;

        return response()->json([
            'success' => true,
            'message' => 'Success add todo list',
            'data' => $data
        ],201);
    }

    public function postUpdateTODOList($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'sometimes|required|string|max:255',
            'desc' => 'sometimes|nullable|string',
            'date' => 'sometimes|nullable|date',
            'done' => 'sometimes|boolean'
        ]);

        $data = [];
        if (!empty($request->title)) $data['title'] = $request->title;
        if (!empty($request->desc)) $data['desc'] = $request->desc;
        if (!empty($request->title)) $data['date'] = $request->date;
        if (!empty($request->done)) $data['is_done'] = $request->done;

        $response = ($this->firebase)->update(env('FIREBASE_URL') . auth()->user()->id . '/TODO/' . $id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Success modify entry'
        ]);
    }

    public function deleteTODO($id, Request $request) {
        $data = json_decode(($this->firebase)->get(auth()->user()->id . '/TODO/' . $id));

        if (!empty($data)) {
            $response = json_decode(($this->firebase)->delete(auth()->user()->id . '/TODO/' . $id));

            Log::info($response);
            return response()->json([
                'success' => true,
                'message' => 'Success delete the entry'
            ], 202);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to get data with that ID, looks like the data is doesn\'t exist anymore'
        ], 404);
    }
}
