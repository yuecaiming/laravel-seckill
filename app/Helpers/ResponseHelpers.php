<?php


namespace App\Helpers;

use Illuminate\Http\JsonResponse;

trait ResponseHelpers
{

    protected $success_msg = '操作成功';
    protected $success_code = 200;

    protected $error_msg = '操作失败';
    protected $error_code = 401;

    /**
     * 通用响应
     * @param $result
     * @param $data
     * @return JsonResponse
     */
    public function result($result = true, $data = []): JsonResponse
    {
        return $result !== false ? $this->success($data) : $this->error($data);
    }

    /**
     * 成功响应
     * @param array $option
     * @return JsonResponse
     */
    public function success(array $option = []): JsonResponse
    {
        $option['msg'] = $option['msg'] ?? $this->success_msg;
        $option['code'] = $option['code'] ?? $this->success_code;

        return new JsonResponse($option, $option['code']);
    }

    /**
     * 错误响应
     * @param $option
     * @return JsonResponse
     */
    public function error($option = []): JsonResponse
    {
        $option['msg'] = $option['msg'] ?? $this->error_msg;
        $option['code'] = $option['code'] ?? $this->error_code;

        return new JsonResponse($option, $option['code']);
    }

    /**
     * 内容转数组
     * @param string $content
     * @return mixed
     */
    public function contentToArray(string $content)
    {
        return json_decode($content, true);
    }
}
