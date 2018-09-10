<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Helpers\MailHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Employee;
use App\Models\OtherCategory;
use App\Models\Session;
use App\Models\Training;
use App\Models\User;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Validator;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'search_data' => 'min:0|max:191|nullable',
            'finished' => 'boolean|nullable',
            'limit' => 'integer|min:0|nullable',
            'page' => 'integer|min:0|nullable'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $limit = $request->input('limit');
        $page = $request->input('page');
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;
        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $isFinished = $request->input('finished');
        $search_data = $request->input('search_data');
        $now = (new DateTime())->format('Y:m:d H:i:s');
        $paginatedData = null;
        if ($isFinished == true) {
            $paginatedData = Course::with(['sessions' => function ($query) {
                $query->whereNull('deleted_at');
            }, 'sessions.users'])
                ->whereNull('deleted_at')
                ->whereDoesntHave('sessions', function ($query) use ($now) {
                    $query->whereDate('end_datetime', '>', $now);
                });
        }

        if ($isFinished == false) {
            $paginatedData = Course::with(['sessions' => function ($query) {
                $query->whereNull('deleted_at');
            }, 'sessions.users'])
                ->whereNull('deleted_at')
                ->whereDoesntHave('sessions', function ($query) use ($now) {
                    $query->whereDate('end_datetime', '<', $now);
                });
        }

        if ($isFinished == '') {
            $paginatedData = Course::with(['sessions' => function ($query) {
                $query->whereNull('deleted_at');
            }, 'sessions.users'])->whereNull('deleted_at');

        }
        if ($search_data) {
            $paginatedData->whereHas('otherCategory', function ($query) use ($search_data) {
                $query->where('name', 'like', "%$search_data%");
            });
        } else {
            $paginatedData->orderBy('created_at', 'desc')->whereHas('otherCategory');
        }
        $paginatedData = $paginatedData->paginate($limit, ['*'], 'page', $page);
        $courses = array();
        foreach ($paginatedData as $course) {
            $sessions = $course->sessions();
            $start = $sessions->orderBy('start_datetime')->first();
            $start_date = $start != null ? date('d-m-Y H:i', strtotime($start['start_datetime'])) : '';
            $sessions2 = $course->sessions();
            $end = $sessions2->orderBy('end_datetime', 'desc')->first();
            $end_date = $end != null ? date('d-m-Y H:i', strtotime($end['end_datetime'])) : '';
            $sessions3 = $course->sessions();
            $session = $course->sessions()->first();
            $room = $session != null ? $session->room()->first()['name'] : '';
            array_push($courses, [
                'id' => $course->id,
                'course_name' => $course->otherCategory()->first()['name'],
                'room_name' => $room,
                'sessions_number' => count($sessions3->get()),
                'description' => $course->description,
                'current_order' => $course->current_order,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => strtotime($end_date) < strtotime($now) ? true : false,
            ]);
        }
        usort($courses, function ($a, $b) {
            return strtotime($b['end_date']) - strtotime($a['end_date']);
        });
        $data = [
            'courses' => $courses,
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('List Course', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //Start validation
        $validator = Validator::make($request->all(), [
            'course_category_id' => 'required|integer',
            'description' => 'required|min:0|max:191',
            'room_category_id' => 'required|integer',
            'sessions' => 'required|array',
            'sessions.*.start_datetime' => 'required|date',
            'sessions.*.end_datetime' => 'required|date',
            'sessions.*.trainer' => 'required|min:0|max:191',
            'sessions.*.supporter' => 'string|min:0|max:191|nullable',
            'sessions.*.content' => 'string|nullable',
            'sessions.*.user_ids' => 'required|array',
            'sessions.*.user_ids.*' => 'required|integer',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $sessions = $request->input('sessions');
        $listId = array();
        $room = OtherCategory::where('deleted_at', '=', null)
            ->where('id', '=', $request->input('room_category_id'))->first();
        if ($room == null) {
            return ApiHelper::responseClientFail('The room doesn\'t exist');
        }
        foreach ($sessions as $session) {
            $userIds = $session['user_ids'];
            foreach ($userIds as $userId) {
                array_push($listId, $userId);
            }
        }
        $listId = array_unique($listId);
        $query = User::query();

        foreach ($listId as $userId) {
            $query->orWhere('id', '=', $userId);
        }
        if (count($listId) > count($query->get())) {
            return ApiHelper::responseClientFail('There is not exist user');
        }
        // End validation
        try {
            DB::beginTransaction();
            // Save course
            $courseCategoryId = $request->input('course_category_id');
            $courseCategory = OtherCategory::where('deleted_at', '=', null)
                ->where('id', '=', $courseCategoryId)->first();
            if ($courseCategory == null) {
                return ApiHelper::responseClientFail(__('messages.course_exists_0'));
            }
            $course = new Course();
            $course->course_category_id = $courseCategoryId;
            $description = $request->input('description');
            $course->description = $description;

            $currentOrder = Course::where('course_category_id', '=', $courseCategoryId)
                ->groupBy('course_category_id')
                ->count('course_category_id');
            $course->current_order = $currentOrder + 1;
            $course->save();
            $course_id = $course->id;
            $tmpIds = array();
            // Save sessions
            $startDate = null;
            $endDate = null;
            foreach ($sessions as $session) {
                $s = new Session();
                $s->course_id = $course_id;
                $start_datetime = date('Y-m-d H:i:s', strtotime($session['start_datetime']));
                $s->start_datetime = $start_datetime;
                $end_datetime = date('Y-m-d H:i:s', strtotime($session['end_datetime']));
                $s->end_datetime = $end_datetime;
                $s->trainer = $session['trainer'];
                $s->supporter = $session['supporter'];
                $s->content = $session['content'];
                $s->room_category_id = $request->input('room_category_id');
                $s->save();
                $user_ids = $session['user_ids'];
                $s->users()->sync($user_ids, true);
                foreach ($user_ids as $user_id) {
                    array_push($tmpIds, $user_id);
                }
                if ($startDate != null) {
                    if ($startDate < strtotime($session['start_datetime'])) {
                        $startDate = strtotime($session['start_datetime']);
                    }
                } else {
                    $startDate = strtotime($session['start_datetime']);
                }

                if ($endDate != null) {
                    if ($endDate > strtotime($session['end_datetime'])) {
                        $endDate = strtotime($session['end_datetime']);
                    }
                } else {
                    $endDate = strtotime($session['end_datetime']);
                }

            }
            $startDate = date('d-m-Y', $startDate);
            $endDate = date('d-m-Y', $endDate);
            $tmpIds = array_unique($tmpIds);
            foreach ($tmpIds as $user_id) {
                $training = new Training();
                $training->fill(['course_id' => $course_id, 'user_id' => $user_id, 'score' => null]);
                $training->save();
                $user = User::whereHas('employee')->find($user_id);
                $count = 0;
                foreach ($sessions as $session) {
                    foreach ($session['user_ids'] as $id) {
                        if ($id == $user->id) {
                            $count++;
                        }
                    }
                }
                $title = '[TRAINING] Khóa học ' . $courseCategory->name;
                $body = '- Ngày bắt đầu: ' . $startDate . "\n" .
                    '- Ngày kết thúc: ' . $endDate . "\n" .
                    '- Tổng số buổi: ' . count($sessions) . "\n" .
                    '- Số buổi bạn phải tham gia: ' . $count;
                $email = $user->email;
                $action = '';
                NotificationHelper::saveNotification($title, $body, $action, $email, NOTIFICATION_TYPE_COURSE, $course->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Course', ['course' => $course]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $course = Course::with(['sessions' => function ($query) {
            $query->where('deleted_at', '=', null);
        }, 'sessions.users'])
            ->where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $sessions = array();
        $room_id = null;
        foreach ($course->sessions()->get() as $session) {
            $room_id = $session->room_category_id;
            $users = array();
            foreach ($session->users()->get() as $user) {
                array_push($users, $user->id);
            }
            array_push($sessions, [
                'start_datetime' => date('d-m-Y H:i:s', strtotime($session->start_datetime)),
                'end_datetime' => date('d-m-Y H:i:s', strtotime($session->end_datetime)),
                'trainer' => $session->trainer,
                'supporter' => $session->supporter,
                'content' => $session->content,
                'user_ids' => $users,
                'qr_code' => 'course_id:' . $course->id . 'session_id:' . $session->id
            ]);
        }
        $data = [
            'id' => $course->id,
            'course_category_id' => $course->course_category_id,
            'description' => $course->description,
            'room_category_id' => $room_id,
            'sessions' => $sessions
        ];

        return ApiHelper::responseSuccess('Course Infomation', ['course' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
        //Start validation
        $validator = Validator::make($request->all(), [
            'course_category_id' => 'required|integer',
            'description' => 'required|min:0|max:191',
            'room_category_id' => 'required|integer',
            'sessions' => 'required|array',
            'sessions.*.start_datetime' => 'required|date',
            'sessions.*.end_datetime' => 'required|date',
            'sessions.*.trainer' => 'required|min:0|max:191',
            'sessions.*.supporter' => 'string|min:0|max:191|nullable',
            'sessions.*.content' => 'string|nullable',
            'sessions.*.user_ids' => 'required|array',
            'sessions.*.user_ids.*' => 'required|integer',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $room = OtherCategory::where('deleted_at', '=', null)
            ->where('id', '=', $request->input('room_category_id'))->first();
        if ($room == null) {
            return ApiHelper::responseClientFail('The room doesn\'t exist');
        }

        $sessions = $request->input('sessions');
        $listId = array();
        foreach ($sessions as $session) {
            $userIds = $session['user_ids'];
            foreach ($userIds as $userId) {
                array_push($listId, $userId);
            }
        }
        $listId = array_unique($listId);
        $query = User::query();
        foreach ($listId as $userId) {
            $query->orWhere('id', '=', $userId);
        }
        if (count($listId) != count($query->get())) {
            return ApiHelper::responseClientFail('There is not exist user');
        }
        // End validation
        try {
            DB::beginTransaction();
            // Save course
            $courseCategoryId = $request->input('course_category_id');
            $course = Course::whereNull('deleted_at')
                ->find($id);
            if ($course == null) {
                return ApiHelper::responseClientFail(__('messages.course_exists_0'));
            }
            $course->course_category_id = $courseCategoryId;
            $description = $request->input('description');
            $course->description = $description;

            if ($course->save()) {
                // Save sessions
                $tmpSessions = Session::where('deleted_at', '=', null)
                    ->where('course_id', '=', $course->id)->get();
                foreach ($tmpSessions as $tmpSession) {
                    $tmpSession->delete();
                    $tmpSession->users()->sync([], true);
                }
                $tmpIds = array();
                $course_id = $course->id;
                foreach ($sessions as $session) {
                    $s = new Session();
                    $s->course_id = $course->id;
                    $start_datetime = date('Y-m-d H:i:s', strtotime($session['start_datetime']));
                    $s->start_datetime = $start_datetime;
                    $end_datetime = date('Y-m-d H:i:s', strtotime($session['end_datetime']));
                    $s->end_datetime = $end_datetime;
                    $s->trainer = $session['trainer'];
                    $s->supporter = $session['supporter'];
                    $s->content = $session['content'];
                    $s->room_category_id = $request->input('room_category_id');
                    $s->save();
                    $user_ids = $session['user_ids'];
                    $s->users()->sync($user_ids, true);
                    foreach ($user_ids as $user_id) {
                        array_push($tmpIds, $user_id);
                    }
                }
                $training = Training::whereNull('deleted_at')->where('course_id', '=', $course_id)->get();
                foreach ($training as $item) {
                    $item->delete();
                }
                $tmpIds = array_unique($tmpIds);
                foreach ($tmpIds as $user_id) {
                    $training = new Training();
                    $training->fill(['course_id' => $course_id, 'user_id' => $user_id, 'score' => null]);
                    $training->save();
                }
            } else {
                DB::rollBack();
                return ApiHelper::responseFail('Failed saving course');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Course', ['course' => $course]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        try {
            DB::beginTransaction();
            $course = Course::where('deleted_at', '=', null)->where('id', '=', $id)->first();
            if ($course == null) {
                return ApiHelper::responseClientFail(__('messages.course_exists_0'));
            }
            $course->deleted_at = new \DateTime();
            $course->save();
            $sessions = $course->sessions()->get();
            foreach ($sessions as $session) {
                $session->deleted_at = new \DateTime();
                $session->save();
            }
            $training = $course->training()->get();
            foreach ($training as $trainingItem) {
                $trainingItem->deleted_at = new \DateTime();
                $trainingItem->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted course', ['course_id' => $course->id]);
    }

    public function getSearchedUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'integer|min:0|nullable',
            'job_position_id' => 'integer|min:0|nullable',
            'job_status_id' => 'integer|min:0|nullable',
            'search_value' => 'string|min:0|max:191|nullable'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $departmentId = $request->input('department_id');
        $jobPositionId = $request->input('job_position_id');
        $jobStatusId = $request->input('job_status_id');
        $searchValue = $request->input('search_value');
        //get user not in role
        $roleId = $request->input('role_id');

        $users = User::query();

        $users->whereHas('employee', function ($query) use ($searchValue, $jobStatusId, $jobPositionId, $departmentId) {
            $query->whereNull('deleted_at');
            if ($departmentId) {
                $query->whereHas('department', function ($query) use ($departmentId) {
                    $query->whereNull('deleted_at')->where('department_id', '=', $departmentId);
                });
            }
            if ($jobPositionId) {
                $query->whereHas('position', function ($query) use ($jobPositionId) {
                    $query->whereNull('deleted_at')->where('position_id', '=', $jobPositionId);
                });
            }
            if ($jobStatusId) {
                $query->whereHas('jobStatus', function ($query) use ($jobStatusId) {
                    $query->whereNull('deleted_at')->where('job_status_id', '=', $jobStatusId);
                });
            }
        });
        if ($searchValue) {
            $isASCII = mb_detect_encoding($searchValue) == 'ASCII' ? true : false;
            $searchValue = mb_strtolower($searchValue);
            $users->where(function ($query) use ($isASCII, $searchValue) {
                $query->whereHas('employee', function ($query) use ($isASCII, $searchValue) {
                    if ($isASCII) {
                        $query->whereRaw('full_name LIKE ?', ["%$searchValue%"]);
                    } else {
                        $query->whereRaw('LOWER(full_name) LIKE BINARY ?', ["%$searchValue%"]);
                    }
                })->orWhere(function ($query) use ($isASCII, $searchValue) {
                    if ($isASCII) {
                        $query->whereRaw('LOWER(email) LIKE ?', ["%$searchValue%"]);
                    } else {
                        $query->whereRaw('LOWER(email) LIKE BINARY ?', ["%$searchValue%"]);
                    }
                });
            });
            if ($roleId) {
                $users->where(function ($query) use ($roleId, $searchValue) {
                    $query->whereDoesntHave('roles', function ($query) use ($roleId) {
                        $query->where('id', '=', $roleId);
                    });
                });
            }
        }

        if ($roleId) {
            $users->whereDoesntHave('roles', function ($query) use ($roleId) {
                $query->where('id', '=', $roleId);
            });
        }
        $users = $users->distinct()->get();
        $data = array();
        foreach ($users as $user) {
            array_push($data, [
                'id' => $user['id'],
                'name' => $user['employee']['full_name'],
                'email' => $user['email'],
                'department' => $user['employee']['department']['name'],
                'job_status' => $user['employee']['jobStatus']['name'],
                'position' => $user['employee']['position']['name'],
                'is_selected' => false,
            ]);
        }
        return ApiHelper::responseSuccess('Searched List', ['users' => $data]);
    }

    public function getCourseQr($id)
    {
        $course = Course::whereNull('deleted_at')->find($id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $sessions = $course->sessions()->get();
        $data = array();
        foreach ($sessions as $session) {
            array_push($data, [
                'id' => $session->id,
                'qr_code' => 'course_id:' . $course->id . ';' . 'session_id:' . $session->id,
                'start_datetime' => $session->start_datetime,
                'end_datetime' => $session->end_datetime,
            ]);
        }
        return ApiHelper::responseSuccess('List QR', ['sessions' => $data]);
    }

    public function getCourseTraining($id)
    {
        $course = Course::whereNull('deleted_at')->find($id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $training = $course->training()->whereNull('deleted_at')
            ->with('user')->whereHas('user', function ($query) {
                $query->whereHas('employee');
            })->get();
        $data = array();
        $sessions = $course->sessions()->whereNull('deleted_at')
            ->whereHas('course')
            ->whereHas('users')
            ->get();
        foreach ($training as $tr) {
            $user = $tr->user;
            $user_id = $user == null ? null : $user->id;
            $totalSession = 0;
            $presence = 0;
            foreach ($sessions as $session) {
                if ($session != null) {
                    $currentUser = $session->users()->find($user_id);
                    $totalSession = $currentUser == null ? $totalSession : $totalSession + 1;
                    $pivot = $currentUser == null ? null : $currentUser->pivot;
                    $presence = $pivot == null ? $presence : ($pivot->presence == null ? $presence : ($pivot->presence == 0 ? $presence : $presence + 1));
                }
            }
            //
            $userSessions = $user->sessions()->whereHas('course', function ($query) use ($course) {
                $query->where('id', '=', $course->id);
            })->get();
            $userSessionsTmp = array();
            foreach ($userSessions as $userSession) {
                array_push($userSessionsTmp, [
                    'id' => $userSession->id,
                    'start_datetime' => date('m-d-Y H:i', strtotime($userSession->start_datetime)),
                    'end_datetime' => date('m-d-Y H:i', strtotime($userSession->end_datetime)),
                    'presence' => $userSession->pivot == null ? 0 : $userSession->pivot->presence,
                ]);
            }
            //
            array_push($data, [
                'id' => $tr->id,
                'user_id' => $user->id,
                'name' => $user == null ? __('messages.unknown') : $user->name,
                'email' => $user == null ? __('messages.unknown') : $user->email,
                'score' => $tr->score,
                'presence' => $presence . '/' . $totalSession,
                'sessions' => $userSessionsTmp,
            ]);
        }
        return ApiHelper::responseSuccess('List Training', ['training' => $data]);
    }

    public function rollUpSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|min:0|max:191'
        ]);
        if (!$validator->passes()) {
            ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        $qr = $request->input('qr_code');
        $user = \JWTAuth::toUser($request->token);
        if (!str_contains($qr, 'course_id:') || !str_contains($qr, 'session_id:') || !str_contains($qr, ';')) {
            return ApiHelper::responseClientFail(__('messages.qr_invalid'));
        }
        $data = explode(';', $qr);
        $course = $data[0];
        $session = $data[1];
        $course_id = explode(':', $course);
        if (count($course_id) == 2) {
            $course_id = $course_id[1];
        }
        $course = Course::with('otherCategory')->find($course_id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $session_id = explode(':', $session);
        if (count($session_id) == 2) {
            $session_id = $session_id[1];
        }
        $session = Session::whereNull('deleted_at')
            ->where('id', '=', $session_id)
            ->whereHas('course', function ($query) {
                $query->whereNull('deleted_at');
            })->with('course')->first();
        if ($session == null) {
            return ApiHelper::responseClientFail(__('messages.session_exists_0'));
        }
        $tmpUser = $session->users()->find($user->id);
        if ($tmpUser == null) {
            return ApiHelper::responseClientFail(__('messages.user_in_course_0'));
        }
        $presence = $tmpUser->pivot->presence;
        if ($presence == 1) {
            return ApiHelper::responseClientFail(__('messages.user_roll_up_1'));
        }
        try {
            DB::beginTransaction();
            $session->users()->updateExistingPivot($user->id, ['presence' => 1]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail('Roll up failed');
        }
        return ApiHelper::responseSuccess('Rolled Up', ['course' => [
            'id' => $course_id,
            'name' => $course->otherCategory == null ? '' : $course->otherCategory->name,
            'start_datetime' => date('m-d-Y H:i', strtotime($session->start_datetime)),
            'end_datetime' => date('m-d-Y H:i', strtotime($session->end_datetime)),
            'user_name' => $user->name,
        ]]);
    }

    public function manualSessionsRollUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:0',
            'details' => 'required|array',
            'details.*.session_id' => 'required|integer|min:0',
            'details.*.presence' => 'required|integer|min:0',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }

        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $details = $request->input('details');
        try {
            DB::beginTransaction();
            $rolled_up_sessions = array();
            foreach ($details as $detail) {
                $session_id = $detail['session_id'] == null ? null : $detail['session_id'];
                $presence = $detail['presence'] == null ? 0 : $detail['presence'];
                if ($session_id != null) {

                    $user->sessions()->updateExistingPivot($session_id, ['presence' => $presence]);
                    array_push($rolled_up_sessions, $detail);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Rolled Up', ['details' => $rolled_up_sessions]);
    }

    public function getListFile($id)
    {
        $course = Course::whereNull('deleted_at')->find($id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $excelFiles = $course->courseScoreExcelFiles()->orderBy('created_at', 'desc')->get();
        return ApiHelper::responseSuccess('List Excel File', ['course_score_excel_files' => $excelFiles]);
    }

    public function sendMailCourseScore(Request $request, $id)
    {
        $course = Course::with('otherCategory')->whereHas('otherCategory')->whereNull('deleted_at')->find($id);
        if ($course == null) {
            return ApiHelper::responseClientFail(__('messages.course_exists_0'));
        }
        $user = \JWTAuth::toUser($request->token);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $employee = Employee::with('department')
            ->whereNull('deleted_at')
            ->where('user_id', '=', $user->id)
            ->first();
        $training = $course->training()
            ->whereNull('deleted_at')
            ->whereHas('user')
            ->with('user', 'user.employee', 'user.employee.department')
            ->get();
        $receivers = array();
        foreach ($training as $item) {
            $receiver = $item->user->email;
            $receiverEmployee = $item->user->employee;
            $receiverDepartment = $receiverEmployee == null ? null : $receiverEmployee->department;
            $department = $receiverDepartment == null ? __('messages.null') : $receiverDepartment->name;
            $courseName = $course->otherCategory->name;
            if ($item->score !== null && !$item->sent) {
                MailHelper::sendMailScore($employee, $courseName, $item->score, $receiver, $department, $course->id);
                array_push($receivers, $department);
                try {
                    DB::beginTransaction();
                    $item->sent = true;
                    $item->save();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::info($e->getTraceAsString());
                }
            }
        }
        return ApiHelper::responseSuccess('Sent score mails', ['receivers' => $receivers]);
    }
}
