<?php

namespace NexaMerchant\Apis\Docs\V1\Admin\Controllers\System;

class TinyMCEController
{
    /**
     * @OA\Post(
     *      path="/api/v1/admin/system/tinymce/upload",
     *      operationId="uploadTinyMCE",
     *      tags={"System"},
     *      summary="Upload file from TinyMCE",
     *      description="Upload file from TinyMCE",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          description="Upload file from TinyMCE",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="file",
     *                      description="File to upload",
     *                      type="file",
     *                  ),
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="location",
     *                  type="string",
     *                  description="File location",
     *              ),
     *          ),
     *      ),
     * )
     */
    public function upload()
    {
    }
}