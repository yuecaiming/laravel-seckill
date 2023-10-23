<?php

namespace App\Http\Controllers;

use App\Http\Requests\reservation\AddUserRequest;
use App\Http\Requests\reservation\CancelUserRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\reservation\AddRequest;
use App\Models\ReserveInfo;
use App\Models\ReserveUser;
use App\Services\ReserveService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function create(AddRequest $request)
    {
        // 创建预约活动
        $reserveInfo = ReserveInfo::create($request->all());

        return response()->json(['message' => '预约活动创建成功', 'data' => $reserveInfo]);
    }

    /**
     * 添加预约资格
     * @param AddUserRequest $request
     * @param $reserveInfoId 预约活动id
     * @param ReserveUser $reserveUser
     * @return JsonResponse
     */
    public function addUser(AddUserRequest $request, $reserveInfoId, ReserveUser $reserveUser)
    {
        // 查找对应的预约活动
        $reserveInfo = ReserveInfo::find($reserveInfoId);
        if (empty($reserveInfo)) {
            return response()->json(['message' => '用户预约关系创建失败', 'data' => []]);
        }

        // 创建用户预约关系
        $reserveUser->reserveInfo()->associate($reserveInfo);
        $request->merge(
            [
                'reserve_time' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
        $reserveUser->fill($request->all());
        $reserveUser->save();

        return response()->json(
            [
                'code' => 200,
                'message' => '成功添加预约资格',
                'data' => [
                    'reserve_id' =>  $reserveUser->id,
                ],
            ]
        );
    }

    public function cancelUser($reserveId, ReserveUser $reserveUser)
    {
        // 验证 $reserveId 是否是数字
        if(!is_numeric($reserveId)) {
            return response()->json([
                'code' => 201,
                'message' => '参数有误！',
                'data' => [],
            ]);
        }
        // 删除预约关系
        ReserveUser::destroy($reserveId);

        return response()->json([
            'code' => 200,
            'message' => '成功取消预约资格',
            'data' => [],
        ]);
    }

    public function show(): array
    {
        return $this->userResponse(auth()->getToken()->get());
    }

    public function store(StoreRequest $request): array
    {
        $user = $this->user->create($request->validated()['user']);

        auth()->login($user);

        return $this->userResponse(auth()->refresh());
    }

    public function update(UpdateRequest $request): array
    {
        auth()->user()->update($request->validated()['user']);

        return $this->userResponse(auth()->getToken()->get());
    }

    public function login(LoginRequest $request): array
    {
        if ($token = auth()->attempt($request->validated()['user'])) {
            return $this->userResource($token);
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    protected function userResponse(string $jwtToken): array
    {
        return ['user' => ['token' => $jwtToken] + auth()->user()->toArray()];
    }
}
