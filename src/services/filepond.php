<?php

namespace VetSync\Services;

class FilePond
{
    /**
     * Upload a file to the specified destination.
     *
     * @param array $file The uploaded file array (e.g., from $_FILES)
     * @param array $allowedTypes Allowed MIME types (optional)
     * @return string Returns the folder name on success, empty string on failure
     */
    public function process($file, $allowedTypes = [])
    {
        global $config;

        // Check for upload errors
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            error_log('Upload error: ' . $file['error']);
            return '';
        }

        // Set base destination path
        $basePath = $config['root_path'] . '/src/uploads/tmp';

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

    /**
     * Delete a file/folder from the specified destination.
     * 
     * @param string $folderName The folder name (UUID) to delete
     * @return bool True if successful, false otherwise
     */
    public function revert($folderName)
    {
        global $config;

        // Clean the folder name (in case it contains path separators or is JSON)
        $folderName = trim($folderName, '"\'{}[]');

        // Set base destination path
        $basePath = $config['root_path'] . '/src/uploads/tmp';
        $folderPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;
        error_log($folderPath);

        // Check if folder exists
        if (!is_dir($folderPath)) {
            error_log('Folder not found: ' . $folderPath);
            return false;
        }

        // Remove all files inside the folder, then remove the folder itself
        foreach (glob($folderPath . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Remove the folder
        if (rmdir($folderPath)) {
            return true;
        }

        // error_log('Failed to delete folder: ' . $folderPath);
        return false;

    }

    public function move($folderName, $destination)
    {
        global $config;

        // Clean the folder name
        $folderName = trim($folderName, '"\'{}[]');

        // Set source path (temp folder)
        $tempPath = $config['root_path'] . '/src/uploads/tmp';
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
        $destPath = $config['root_path'] . '/src/uploads/' . $destination;

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

    public function delete($destination, $folderName)
    {
        global $config;

        $basePath = $config['root_path'] . '/src/uploads/' . $destination;
        $folderPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $folderName;

        if (!is_dir($folderPath)) {
            return false;
        }

        // Remove all files inside the folder, then remove the folder itself
        foreach (glob($folderPath . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Remove the folder
        if (rmdir($folderPath)) {
            return true;
        }

        return false;
    }
}