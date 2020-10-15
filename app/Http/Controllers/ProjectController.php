<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Response;

class ProjectController extends Controller
{
    public function addProject(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        try {
            $project = Project::create([
                'name' => $request->get('name'),
                'user_id' => $request->get('user_id'),
            ]);
//            $user = User::find($request->get('user'));
//            $user->projects()->save($project);
            return Response::json(['success' => 'Project is Successfully created', 'project'=>$project], 200);
        } catch (JWTException $e) {
            return Response::json(['error' => 'This Project is already exist'], 500);
        }
    }

    public function updateProject(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }
        try {
            $project = Project::find($request->get('id'));
            $project->update([
                'name' => $request->get('name'),
                'user_id' => $request->get('user_id'),
            ]);

            return Response::json(['success' => 'Project is Successfully updated', 'project'=>$project], 200);
        } catch (JWTException $e) {
            return Response::json(['error' => 'This Project is already exist'], 500);
        }
    }

    public function deleteProject($id) {
        $project = Project::find($id);
        try {
            $project->delete();
            return response()->json(['success'=>'Project Successfully Removed']);
        } catch(JWTException $e) {
            return response()->json(['error'=>$e], 500);
        }
    }
    public function getProjects() {
//        $page = Input::get('pageNo') != "null" ? Input::get('pageNo') : 1;
//        $limit = Input::get('numPerPage') != "null" ? Input::get('numPerPage') : 10;
        $totalCount = count(Project::all());
        $projects = Project::all();
        if ($totalCount == 0) {
            return response()->json(['totalCount'=>$totalCount, 'projects'=>[]], 200);
        } else {
            return response()->json(['totalCount'=>$totalCount, 'projects'=>$projects], 200);
        }
    }

    public function getProjectById(Request $request) {

        try {
            $project = Project::find($request->get('id'));
            return response()->json(['result'=>'success', 'data'=>$project], 200);
        } catch (JWTException $e) {
            return response()->json(['error'=>'Project with this id is not exist'], 404);
        }
    }
}
