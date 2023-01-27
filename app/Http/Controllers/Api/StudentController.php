<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function createStudent(Request $request)
    {

        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate([ // Request de los valores necesarios para crear un Student nuevo
                    'name' => 'required',
                    'surname' => 'required',
                    'dni' => 'required',
                    'course' => 'required',
                ]);

                $student = new Student();
                $student->name_student = $request->name;
                $student->surname_student = $request->surname;
                $student->dni_student = $request->dni;
                $student->course_student = $request->course;

                $student->save();
                DB::commit(); // Se guardan los cambios

                return response()->json([
                    "status" => 1,
                    "msg" => "¡El student $student->name_student se ha guardado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack(); // Se anulan los cambios
                return response()->json(['status' => 0, 'msg' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function destroyStudent(Request $request)
    {

        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction();  // Se inicia la transaction
                $request->validate([
                    'id' => 'required'
                ]);

                Student::find($request)->each->delete();
                DB::commit(); // Se guardan los cambios
                return response()->json([
                    "status" => 1,
                    "msg" => "¡El estudiante se ha eliminado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack(); // Se anulan los cambios
                return response()->json(['status' => 0, 'msg' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function updateStudent(Request $request)
    {
        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate(['id' => 'required', 'name' => 'required', 'surname' => 'required', 'dni' => 'required', 'course' => 'required']);

                $id = $request->id;
                $name_student = $request->name;
                $surname_student = $request->surname;
                $dni_student = $request->dni;
                $course_student = $request->course;

                $data = Student::find($id);

                if ($data) {
                    $data->name_student = $name_student;
                    $data->surname_student = $surname_student;
                    $data->dni_student = $dni_student;
                    $data->course_student = $course_student;
                    $data->save();
                }
                DB::commit(); // Se guardan los cambios
                return response()->json([
                    "status" => 1,
                    "msg" => "¡El estudiante $data->name se ha guardado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack(); // Se anulan los cambios
                return response()->json(['status' => 0, 'message' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function readStudent(Request $request)
    {
        $data = Student::all();

        return response()->json($data);
    }
}
