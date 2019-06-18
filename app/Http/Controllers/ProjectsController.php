<?php

namespace App\Http\Controllers;

use App\project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(Auth::check()){
           
            $companies = Project::where('user_id',Auth::user()->id)->get();
            return view('companies.index', ['companies'=> $companies]);
        }
       return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(Auth::check()){
            $project = Project::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'user_id' => Auth::user()->id
            ]);
            if($project){
                return redirect()->route('companies.show', ['project'=> $project->id])
                ->with('success' , 'project created successfully');
            }
        }
        
            return back()->withInput()->withErrors('errors', 'Error creating new project');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(project $project)
    {
         //$project = Project::where('id',$project->id)->first();
         $project = Project::find($project->id);
         return view('companies.show',['project'=>$project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(project $project)
    {
        $project = Project::find($project->id);
        return view('companies.edit',['project'=>$project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project $project)
    {
        //save data
         $projectUpdate = Project::where('id', $project->id)
                                    ->update([
                                        'name' => $request->input('name'),
                                        'description' => $request->input('description')
         ]);
        
         if($projectUpdate){
             return redirect()->route('companies.show',['project'=>$project->id])->with('success','Updated successfully');
         }
        //redirect
        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(project $project)
    {
        //dd($project);
        $findproject = Project::find($project->id);
        if($findproject->delete()){
            return redirect()->route('companies.index')
            ->with('success','project deleted successfully');
        }
        return back()->withInput()->with('error','project could not be deleted');
    }
}
