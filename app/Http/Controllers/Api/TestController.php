<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Training;
use Illuminate\Http\Request;
use Requests;
use Storage;
use Validator;
use Barryvdh\DomPDF\Facade as PDF;

class TestController extends Controller
{
    //
    public function getWordFromHtml(Request $request)
    {
        $html = $request->getContent();
//        return $html;
//        $pdf = \App::make('dompdf.wrapper');
//        $pdf->loadHTML($html);

    }

    public function uploadImage(Request $request)
    {
//        $file = $request->file('file');
//        $validation = Validator::make($request->all(), [
//            'file' => 'required|file'
//        ]);
//        if (!$validation->passes()) {
//            return ApiHelper::responseClientFail('Failed');
//        }
//        $fileName = $file->getClientOriginalName();
//
//        $file->storeAs('', $fileName);
//        $url = Storage::url('' . $fileName);
//        return ApiHelper::responseSuccess('success', ['url' => $url]);
        $arr = [
            1 => ['name' => 'Nguyen Quoc Viet'],
            2 => ['name' => 'Nguyen Quoc Viet'],
        ];
        echo $arr[1]['name'];
    }

    public function time($course_id)
    {
        $course = Course::with('sessions', 'otherCategory')->whereHas('otherCategory')->find($course_id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $training = Training::with('user', 'user.employee.department', 'user.employee.position')
            ->where('course_id', '=', $course_id)
            ->whereHas('user', function ($query) {
                $query->whereHas('employee', function ($query) {
//                    $query->whereHas('department');
//                    $query->whereHas('position');
                });
            })->get();
//        return $training;
        $trainingData = array();
        $order = 0;
        foreach ($training as $item) {
            $order++;
            $employee = $item->user->employee;
            $name = $employee->full_name == null ? __('messages.null') : $employee->full_name;
            $department = $employee->department == null ? __('messages.null') : $employee->department->name;
            $position = $employee->position == null ? __('messages.null') : $employee->position->name;
            array_push($trainingData, [
                'order' => $order,
                'name' => $name,
                'position' => $position,
                'department' => $department,
                'score' => $item->score,
            ]);
        }
        $trainers = array();
        $sessions = $course->sessions;

        foreach ($sessions as $session) {
            array_push($trainers, ApiHelper::getNameFromEmail($session->trainer));
        }
        $trainers = array_unique($trainers);
        $data = [
            'training_date' => '23/10/1996',
            'course_name' => $course->otherCategory->name,
            'trainers' => $trainers,
            'training' => $trainingData,
        ];
        $pdf = PDF::loadView('report.report_course_training', $data);
        return $pdf->stream();
    }

    public function rangeCompution(Request $request)
    {

        return $request->getBaseUrl();
    }

    public function request()
    {
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . FCM_SERVER_KEY
        ];
        $data = [
            'to' => 'ccACg2yhnkk:APA91bGfJhiGUSetsc7twRnxTg_tP8Yg706StK61SXbnRLuKsrxykJfj6mzLeIxs1-LxPW7RkXdC283sELJ4AIDectxdrDjwIe0vHN7bdW8mlVkRPjK1hdPtF7j9zxfxgWsZwvpKd9Rx',
            'notification' => [
                'body' => 'Em bị đau dạ dày',
                'title' => '"Yêu cầu nghỉ phép - Nguyễn Văn A"',
            ]
        ];
        \Log::info('data raw: ' . json_encode($data));
        $response1 = Requests::post(FCM_URL, $header, json_encode($data));
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . FCM_SERVER_KEY
        ];

        $data = [
            'to' => 'dGI1SPcUZu8:APA91bGWupH3yPwLtRPANGjyhs6K7dvLzgfpBFBw6ste97_A8-XRFy_8lMOXRQGnHKxptuq99dhnE9RS79PNWkK1gBwnNZZqpZzR-7bBVCkMfrlh5KhpvcQU0PXGRrY8noA5-RXlJp24',
            'notification' => [
                'body' => 'Em bị đau dạ dày',
                'title' => '"Yêu cầu nghỉ phép - Nguyễn Văn A"',
            ]
        ];
        \Log::info('data raw: ' . json_encode($data));
        $response2 = Requests::post(FCM_URL, $header, json_encode($data));
        return ApiHelper::responseSuccess('Request', ['content' => $response2->body]);
    }
}
