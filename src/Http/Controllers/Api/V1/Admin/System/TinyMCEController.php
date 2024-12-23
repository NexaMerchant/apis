<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\System;

use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\AdminController;
use Illuminate\Support\Facades\Storage;

class TinyMCEController extends AdminController
{
    /**
     * Storage folder path.
     *
     * @var string
     */
    private $storagePath = 'tinymce';

    /**
     * Upload file from tinymce.
     *
     * @return void
     */
    public function upload()
    {
        $media = $this->storeMedia();

        if (! empty($media)) {
            return response()->json([
                'location' => $media['file_url']
            ]);
        }

        return response()->json([]);
    }

    /**
     * Store media.
     *
     * @return array
     */
    public function storeMedia()
    {
        if (! request()->hasFile('file')) {
            return [];
        }

        return [
            'file'      => $path = request()->file('file')->store($this->storagePath),
            'file_name' => request()->file('file')->getClientOriginalName(),
            'file_url'  => Storage::url($path),
        ];
    }
}
