<?php

use VetSync\Services\Attachments;

function mediaHelper($dir = '', $folder = '', $filename = '')
{
    try {
        $fullPath = $dir . '/' . $folder . '/' . $filename;

        $source = urlFileHelper('uploads', $fullPath);
        if (!$source || $source == '') {
            return asset('img/profiles/profile.jpg');
        }

        return $source;
    } catch (Throwable $t) {
        error_log($t->getMessage());
        return null;
    }
}

function media($reference_uuid, $is_all = false)
{
    $attachmentService = new Attachments();

    $attachment = [];
    if ($is_all) {
        $attachment = $attachmentService->all($reference_uuid);
    } else {
        $attachment = $attachmentService->single($reference_uuid);
    }

    if (!$attachment) {
        return asset('img/profiles/profile.jpg');
    }

    if ($is_all) {
        return array_map(function ($item) {
            return mediaHelper($item['reference_model'], $item['folder'], $item['filename']);
        }, $attachment ?? []);
    } else {
        return mediaHelper($attachment['reference_model'], $attachment['folder'], $attachment['filename']);
    }
}