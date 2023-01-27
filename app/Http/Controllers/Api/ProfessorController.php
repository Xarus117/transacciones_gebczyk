<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Professor;
use Illuminate\Support\Facades\DB;

class ProfessorController extends Controller
{
    public function createProfessor(Request $request)
    {
        $result = null;

        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate([ // Request de los valores necesarios para crear un Student nuevo
                    'name' => 'required',
                    'surname' => 'required',
                    'dni' => 'required'
                ]);

                $prof = new Professor();
                $prof->name_prof = $request->name;
                $prof->surname_prof = $request->surname;
                $prof->dni_prof = $request->dni;

                $prof->save();
                DB::commit(); // Se guardan los cambios

                return response()->json([
                    "status" => 1,
                    "msg" => "¡El profesor $prof->name_prof se ha guardado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack(); // Se anulan los cambios
                return response()->json(['status' => 0, 'msg' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function destroyProfessor(Request $request)
    {

        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate([
                    'id' => 'required'
                ]);

                Professor::find($request)->each->delete();
                DB::commit(); // Se guardan los cambios
                return response()->json([
                    "status" => 1,
                    "msg" => "¡El profesor se ha eliminado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack(); // Se anulan los cambios
                return response()->json(['status' => 0, 'msg' => $exp->getMessage()]);
            }
        });

        return response()->json($result);
    }

    public function updateProfessor(Request $request)
    {
        $result = null;
        $result = DB::transaction(function () use ($request) {
            try {
                DB::beginTransaction(); // Se inicia la transaction
                $request->validate(['id' => 'required', 'name' => 'required', 'surname' => 'required', 'dni' => 'required']);

                $id = $request->id;
                $name_prof = $request->name;
                $surname_prof = $request->surname;
                $dni_prof = $request->dni;

                $data = Professor::find($id);

                if ($data) {
                    $data->name_prof = $name_prof;
                    $data->surname_prof = $surname_prof;
                    $data->dni_prof = $dni_prof;
                    $data->save();
                }
                DB::commit(); // Se guardan los cambios
                return response()->json([
                    "status" => 1,
                    "msg" => "¡El profesor $data->name se ha guardado correctamente!",
                ]);
            } catch (\Exception $exp) {
                DB::rollBack();  // Se anulan los cambios
                return response()->json(['status' => 0, 'message' => $exp->getMessage()]);
            }
        });
        return response()->json($result);
    }

    public function readProfessor(Request $request)
    {
        $data = Professor::all();

        return response()->json($data);
    }
}
