<?php

namespace App\Services;

use App\Models\CarierCenter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CarierCenterService
{
    public function store($request) {
        try {
            $decodeToken = parseJwt($request->header('Authorization'));

            DB::beginTransaction();

            $uuid = generateUuid();

            $pic = '';

            if ($request->picture) {
                $fotoName = "CarierCenter/images/";
                // $fotoPath = $fotoName.$request->foto;
                uploadFile($request->picture, $fotoName, "$uuid.png");
                $pic = $fotoName."$uuid.png";
            }

            $result = CarierCenter::create([
                'uuid' => $uuid,
                'title' => $request->title,
                'content' => $request->content,
                'picture' => $pic,
                'user_id' => $decodeToken->user->uuid
            ]);

            DB::commit();

	   		return true;

        } catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getAll($request) {
        try {
            $result = CarierCenter::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getNew($request) {
        try {
            $result = CarierCenter::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getOne($id) {
        try {
            $result = CarierCenter::where('uuid', $id)->with('author')->first();
            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function show($id) {
        try {
            $result = CarierCenter::where('uuid', $id)->with('author')->first();
            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function update($request, $id) {
        try {
            $result = CarierCenter::where('uuid', $id)->first();

            $pic = $result->picture;

            if ($request->image) {
                $fotoName = "CarierCenter/images/";
                // $fotoPath = $fotoName.$request->foto;
                uploadFile($request->image, $fotoName, "$uuid.png");
                $pic = $fotoName."$uuid.png";
            }

            $result->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'content' => $request->content,
                'status' => $request->status,
                'picture' => $pic
            ]);

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $result = CarierCenter::where('uuid', $id)->first();
            $result->delete();
            return true;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }
}
