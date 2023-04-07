<?php

// define permissions
if (!function_exists('permissionLists')) {
  function permissionLists()
  {
    $permissions = [
      'create' => 'Create',
      // 'read' => 'Read',
      'update' => 'Update',
      'delete' => 'Delete',
    ];
    return $permissions;
  }
}
if (!function_exists('downloadFile')) {
  function downloadFile($filename)
  {
    $path = storage_path('app/' . $filename);

    if (!file_exists($path)) {
      return response()->json(['message' => 'File not found.'], 404);
    }

    return response()->download($path);
  }
}
