<?php

namespace App\Http\Controllers\API\Announcement;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Http\Resources\AnnouncementResource;

class AnnouncementController extends BaseController
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailAnnouncement($id)
    {
        $announcement = [];
        try {
            $announcement = Announcement::where('id', $id)
                ->where('status', '!=', 'unpublish')
                ->with('creator:id,email,first_name,last_name,phone')
                ->first();
            if (empty($announcement)) {
                return $this->sendError('Announcement not found.');
            }
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            new AnnouncementResource($announcement),
            'success'
        );
    }
}
