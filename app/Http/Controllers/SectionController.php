<?php

namespace App\Http\Controllers;

use App\apiResponseTrait;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    //
    use apiResponseTrait;
    public function index(){
        $sections = SectionResource::collection(Section::all());
        return $this->apiResponse($sections, 'sections data' , 200);
    }

    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $section = Section::find($request->id);
        if(!$section){
            return $this->apiResponse([], 'section not found' , 404);
        }
        return $this->apiResponse(new SectionResource($section) , 'section getted successfully' , 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:255|unique:sections',
            'description' => 'required',
            'created_by' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $section = Section::create([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => $request->created_by,
        ]);

        return $this->apiResponse(new SectionResource($section) , 'section created successfully' , 201);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'section_name' => 'required|string|max:255',
            'description' => 'required',
            'created_by' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $section = Section::find($request->id);
        if(!$section){
            return $this->apiResponse([], 'section not found' , 404);
        }
        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => $request->created_by,
        ]);
        return $this->apiResponse(new SectionResource($section) , 'section updated successfully' , 201);
    }

    public function destroy(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $section = Section::find($request->id);
        if(!$section){
            return $this->apiResponse([], 'section not found' , 404);
        }
        $section->delete();
        return $this->apiResponse([] , 'section deleted successfully' , 200);
    }
}
