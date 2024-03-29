<?php
function UploadErrHandler($errCode){
    switch ($errCode) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return "Error: File size exceeds the maximum allowed size.";
            break;
        case UPLOAD_ERR_PARTIAL:
            return "Error: The file was only partially uploaded.";
            break;
        case UPLOAD_ERR_NO_FILE:
            return "Error: No file was uploaded.";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Error: Missing temporary folder.";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            return "Error: Failed to write file to disk.";
            break;
        case UPLOAD_ERR_EXTENSION:
            return "Error: File upload stopped by extension.";
            break;
        default:
            return "Error: Unknown upload error.";
            break;
      }
}

function checkDirectory($directoryPath) {
    if (!is_dir($directoryPath)) {
        if (!mkdir($directoryPath, 0777, true)) {
            return false;
        }
    } elseif (!is_writable($directoryPath)) {
        if (!chmod($directoryPath, 0777)) {
            return false;
        }
    }
    return true;
}
