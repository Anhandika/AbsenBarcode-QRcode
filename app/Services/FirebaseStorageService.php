<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseStorageService
{
    public function uploadAvatar(User $user, UploadedFile $file): ?string
    {
        if (! config('firebase_integration.storage_enabled')) {
            return null;
        }

        $path = 'avatars/' . $user->id . '/' . Str::uuid() . '.' . $file->extension();
        $stream = fopen($file->getRealPath(), 'rb');

        if ($stream === false) {
            return null;
        }

        try {
            Firebase::storage()->getBucket()->upload($stream, [
                'name' => $path,
                'metadata' => [
                    'contentType' => $file->getMimeType(),
                    'metadata' => [
                        'userId' => (string) $user->id,
                    ],
                ],
            ]);
        } finally {
            fclose($stream);
        }

        return $path;
    }
}
