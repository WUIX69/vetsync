<?php

namespace VetSync\Services;

use VetSync\Models\Attachments;

class FilePond
{
    private $uploadDirectory;
    private $response;
    private $attachments;

    public function __construct()
    {
        global $response;
        $this->response = $response;

        global $config;
        $this->uploadDirectory = $config['root_path'] . '/src/uploads/';

        $this->attachments = new Attachments();
    }

    /**
     * Instant upload a file to the specified destination.
     *
     * @param array $file The uploaded file array (e.g., from $_FILES)
     * @param string $destination The destination folder name
     * @param array $args Additional arguments (optional)
     * @return array Returns the folder name and filename on success, empty array on failure
     */
    public function storeWherePermanent($file, $destination, $args = [])
    {

        // Check for upload errors
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            error_log('Upload error: ' . $file['error']);
            return $this->response;
        }

        // Set base destination path
        $basePath = $this->uploadDirectory . $destination;

        // Ensure base destination directory exists
        if (!is_dir($basePath) && !mkdir($basePath, 0777, true)) {
            error_log('Failed to create base directory: ' . $basePath);
            return $this->response;
        }

        // Create a unique UUID for the folder name
        $folderName = uuid();

        // Create folder inside the destination
        $folderPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;
        if (!mkdir($folderPath, 0777, true)) {
            error_log('Failed to create folder: ' . $folderPath);
            return $this->response;
        }

        // Keep original filename
        $filename = $file['name'];
        $targetPath = $folderPath . DIRECTORY_SEPARATOR . $filename;

        // Move the uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $this->response;
        }

        // Delete Existing file first
        $oldFile = $this->attachments->single($args['reference_uuid']);
        if ($oldFile['success'] && !empty($oldFile['data'])) {
            $this->deleteWherePermanent($destination, $oldFile['data']['folder']);
            $this->attachments->deleteWhereReference($destination, $args['reference_uuid']);
        }

        // Store new file
        return $this->attachments->store([
            'reference_model' => $destination,
            'reference_uuid' => $args['reference_uuid'],
            'folder' => $folderName,
            'filename' => $filename,
        ]);
    }

    /**
     * Upload a file to temporary folder.
     *
     * @param array $file The uploaded file array (e.g., from $_FILES)
     * @param array $args Additional arguments (optional)
     * @return string Returns the folder name on success, empty string on failure
     */
    public function storeWhereTemporary($file, $args = [])
    {

        // Check for upload errors
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            error_log('Upload error: ' . $file['error']);
            return '';
        }

        // Set base destination path
        $basePath = $this->uploadDirectory . 'tmp';

        // Ensure base destination directory exists
        if (!is_dir($basePath) && !mkdir($basePath, 0777, true)) {
            error_log('Failed to create base directory: ' . $basePath);
            return '';
        }

        // Create a unique UUID for the folder name
        $folderName = uuid();

        // Create folder inside the destination
        $folderPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;
        if (!mkdir($folderPath, 0777, true)) {
            error_log('Failed to create folder: ' . $folderPath);
            return '';
        }

        // Keep original filename
        $filename = $file['name'];
        $targetPath = $folderPath . DIRECTORY_SEPARATOR . $filename;

        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $folderName;
        }

        return '';
    }

    public function deleteWherePermanent($destination, $folderName)
    {
        $basePath = $this->uploadDirectory . $destination;
        $folderPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;

        if (!is_dir($folderPath)) {
            error_log("Folder not found on $destination: " . $folderPath);
            return false;
        }

        // Remove all files inside the folder
        foreach (glob($folderPath . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Remove the folder
        return rmdir($folderPath);
    }

    /**
     * Delete a file/folder from temporary folder.
     * 
     * @param string $folderName The folder name (UUID) to delete
     * @return bool True if successful, false otherwise
     */
    public function deleteWhereTemporary($folderName)
    {
        // Clean the folder name (in case it contains path separators or is JSON)
        $folderName = trim($folderName, '"\'{}[]');

        // Set base destination path
        $basePath = $this->uploadDirectory . 'tmp';
        $folderPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;

        // Check if folder exists
        if (!is_dir($folderPath)) {
            error_log("Folder not found on temporary folder: " . $folderPath);
            return false;
        }

        // Remove all files inside the folder, then remove the folder itself
        foreach (glob($folderPath . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Remove the folder
        return rmdir($folderPath);
    }

    public function move($folderName, $destination)
    {

        // Clean the folder name
        $folderName = trim($folderName, '"\'{}[]');

        // Set source path (temp folder)
        $tempPath = $this->uploadDirectory . 'tmp';
        $sourceFolderPath = rtrim($tempPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;

        // Check if source folder exists
        if (!is_dir($sourceFolderPath)) {
            return false;
        }

        // Get the first file in the source folder (assuming there's just one)
        $files = glob($sourceFolderPath . '/*');
        if (empty($files) || !is_file($files[0])) {
            return false;
        }

        $sourceFilePath = $files[0];
        $filename = basename($sourceFilePath);

        // Set destination path
        $destPath = $this->uploadDirectory . $destination;

        // Ensure destination directory exists
        if (!is_dir($destPath) && !mkdir($destPath, 0777, true)) {
            return false;
        }

        // Create destination folder using the same UUID
        $destFolderPath = rtrim($destPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;
        if (!is_dir($destFolderPath) && !mkdir($destFolderPath, 0777, true)) {
            return false;
        }

        // Copy file to destination
        $destFilePath = $destFolderPath . DIRECTORY_SEPARATOR . $filename;
        if (!copy($sourceFilePath, $destFilePath)) {
            return false;
        }

        // Remove the source file and folder
        unlink($sourceFilePath);
        rmdir($sourceFolderPath);

        return [
            'folder' => $folderName,
            'filename' => $filename
        ];
    }

    /**
     * Load a file from the specified destination.
     *
     * @param string $folderName The folder name (UUID) to load
     * @param string $destination The destination folder name
     * @return array|bool File data or false on failure
     */
    public function load($folderName, $destination)
    {
        $basePath = $this->uploadDirectory . $destination;
        $folderPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;

        if (!is_dir($folderPath)) {
            return false;
        }

        // Get the first file in the folder
        $files = glob($folderPath . '/*');
        if (empty($files) || !is_file($files[0])) {
            return false;
        }

        $filePath = $files[0];
        $filename = basename($filePath);
        $fileSize = filesize($filePath);
        $mimeType = mime_content_type($filePath);

        // Return file data
        return [
            'name' => $filename,
            'size' => $fileSize,
            'type' => $mimeType,
            'path' => $filePath,
            'folder' => $folderName
        ];
    }
}