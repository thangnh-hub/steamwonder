<?php

namespace App\Http\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService
{
    protected $client;
    protected $drive;
    protected $googleService;

    public function __construct()
    {
        $this->googleService = new GoogleAPIService();
        $this->client = new Client();
        $this->client->setAuthConfig($this->googleService->getCredentialsPath());
        $this->client->addScope(Drive::DRIVE);
        $this->drive = new Drive($this->client);
    }

    // Tạo folder nếu chưa có
    public function createOrGetFolder($name, $parentId = null)
    {
        $query = "name = '$name' and mimeType = 'application/vnd.google-apps.folder'";
        if ($parentId) {
            $query .= " and '$parentId' in parents";
        }

        $results = $this->drive->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)',
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        $folderMetadata = new DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => $parentId ? [$parentId] : []
        ]);

        $folder = $this->drive->files->create($folderMetadata, [
            'fields' => 'id'
        ]);

        return $folder->id;
    }

    public function upload($filePath, $fileName, $mimeType, $folderId)
    {
        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => [$folderId]
        ]);

        $content = file_get_contents($filePath);

        $uploadedFile = $this->drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id,webViewLink'
        ]);

        return $uploadedFile;
    }

    public function deleteFile($fileId)
    {
        try {
            $this->drive->files->delete($fileId);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteFolderWithContents($folderId)
    {
        try {
            // 1. Lấy danh sách file con trong folder
            $files = $this->drive->files->listFiles([
                'q' => "'$folderId' in parents",
                'fields' => 'files(id, name)'
            ])->getFiles();

            // 2. Xóa từng file
            foreach ($files as $file) {
                $this->drive->files->delete($file->getId());
            }

            // 3. Xóa chính thư mục
            $this->drive->files->delete($folderId);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
