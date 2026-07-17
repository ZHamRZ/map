<?php

namespace App\Http\Requests;

use App\Services\MediaService;
use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'max_image_size' => MediaService::MAX_IMAGE_SIZE,
            'max_video_size' => MediaService::MAX_VIDEO_SIZE,
        ]);
    }

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $imgMimes = implode(',', MediaService::IMAGE_EXTENSIONS);
        $vidMimes = implode(',', MediaService::VIDEO_EXTENSIONS);
        $allMimes = implode(',', array_merge(MediaService::IMAGE_EXTENSIONS, MediaService::VIDEO_EXTENSIONS));
        $maxImg = MediaService::MAX_IMAGE_SIZE;
        $maxVid = MediaService::MAX_VIDEO_SIZE;

        return [
            'name'                  => 'required|string|max:255',
            'category'              => 'required|string|max:100',
            'latitude'              => 'required|numeric|between:-90,90',
            'longitude'             => 'required|numeric|between:-180,180',
            'description'           => 'nullable|string',
            'history'               => 'nullable|string|max:5000',
            'cultural_significance' => 'nullable|string|max:5000',
            'video_url'             => 'nullable|string|max:500',
            'audio_url'             => 'nullable|string|max:500',
            'images'                => 'nullable|array',
            'images.*'              => "nullable|file|mimes:{$allMimes}|max:{$maxVid}",
            'videos'                => 'nullable|array',
            'videos.*'              => "nullable|file|mimes:{$vidMimes}|max:{$maxVid}",
        ];
    }

    public function messages(): array
    {
        $imgMaxMB = MediaService::MAX_IMAGE_SIZE / 1024;
        $vidMaxMB = MediaService::MAX_VIDEO_SIZE / 1024;

        return [
            'images.*.max'           => "Setiap file maksimal {$vidMaxMB}MB.",
            'images.*.mimes'         => 'Format file tidak didukung.',
            'videos.*.max'           => "Setiap video maksimal {$vidMaxMB}MB.",
            'videos.*.mimes'         => 'Format video tidak didukung.',
        ];
    }
}
