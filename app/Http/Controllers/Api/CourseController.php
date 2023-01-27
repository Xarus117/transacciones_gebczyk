<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function createCourse(Request $request)
    {
        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate([
                    'name' => 'required',
                    'prof' => 'required'
                ]);

                $course = new Course();
                $course->name_course = $request->name;
                $course->prof_course = $request->prof;

                $course->save();
                DB::commit(); // Se guardan los cambios

                return response()->json([
                    "status" => 1,
                    "msg" => "¡El curso $course->name_course se ha guardado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack(); // Se anulan los cambios
                return response()->json(['status' => 0, 'msg' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function destroyCourse(Request $request)
    {

        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate([
                    'id' => 'required'
                ]);

                Course::find($request)->each->delete();
                DB::commit(); // Se guardan los cambios
                return response()->json([
                    "status" => 1,
                    "msg" => "¡El curso se ha eliminado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack(); // Se anulan los cambios
                return response()->json(['status' => 0, 'msg' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function updateCourse(Request $request)
    {

        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate(['id' => 'required', 'name' => 'required', 'prof' => 'required']);

                $id = $request->id;
                $name = $request->name;
                $prof = $request->prof;

                $data = Course::find($id);

                if ($data) {
                    $data->name_course = $name;
                    $data->prof_course = $prof;
                    $data->save();
                }
                DB::commit(); // Se guardan los cambios
                return response()->json($data);
            } catch (\Exception $exp) {
                DB::rollBack();  // Se anulan los cambios
                return response()->json(['status' => 0, 'message' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function readCourse(Request $request)
    {
        $data = Course::all();

        return response()->json($data);
    }
}
