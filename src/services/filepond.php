<?php

namespace VetSync\Services;

class FilePond
{
    // File upload service implementation
    /**
     * Upload a file to the specified destination.
     *
     * @param array $file The uploaded file array (e.g., from $_FILES)
     * @param string $destination The destination directory or path
     * @param array $allowedTypes Allowed MIME types (optional)
     * @return bool|string Returns the file path on success, false on failure
     */
    public function upload($file, $destination, $allowedTypes = [])
    {
        // Check for upload errors
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Validate file type if allowedTypes is specified
        if (!empty($allowedTypes)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            if (!in_array($mimeType, $allowedTypes)) {
                return false;
            }
        }

        // Ensure destination directory exists
        if (!is_dir($destination)) {
            if (!mkdir($destination, 0777, true)) {
                return false;
            }
        }

        // Generate a unique file name to avoid overwriting
        $filename = uniqid() . '_' . basename($file['name']);
        $targetPath = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $targetPath;
        }

        return false;
    }
}