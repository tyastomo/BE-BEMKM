<?php

namespace App\Services;

use App\Models\NamaLogo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GrahamCampbell\Flysystem\Facades\Flysystem;

class NamaLogoService
{

    public function cek($id) {
        try {
            $result = NamaLogo::where('id', $id)->first();
            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getOne() {
        try {
            $result = NamaLogo::with('author')->first();
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
            $result = NamaLogo::where('id', $id)->first();

            $pathfoto = $result->picture;

            if ($request->image) {
                $foto     = base64_decode($request->image);
                $pathfoto = "NamaLogo/images/". date('YmdHis') . '.png';
                $upload   = Flysystem::connection('awss3')->put($pathfoto, $foto);
            }

            $result->update([
                'title' => $request->title,
                'picture' => $pathfoto
            ]);

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }
}
