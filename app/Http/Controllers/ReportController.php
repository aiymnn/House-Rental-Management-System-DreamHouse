<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ReportController extends Controller
{
    public function index(){
        $reports = Report::where('tenant_id', Auth::guard('tenant')->user()->id)->get();
        return view('tenant.report.TReport', compact('reports'));
    }

    public function create(){
        return view('tenant.report.create');
    }

    public function store(Request $request){

        $contract = Contract::where('tenant_id', Auth::guard('tenant')->user()->id)->where('status', '!=', 3)->first();
        $aid = $contract->agent_id;

        $path = '';
        $filename = '';

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/report/';
            $filename = time().'.'.$extension;
            $file->move($path, $filename);
        }

        Report::create([
            'agent_id' => $aid,
            'tenant_id' => Auth::guard('tenant')->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $path.$filename,
        ]);

        return back()->with('status', 'Complaint Submitted');
    }

    public function edit(int $id){
        $reports = Report::findOrFail($id);
        $title = '';
        $vtitle = $reports->title;
        if ($reports->title == 1) {
            $title = 'Damage to the house';
        } else {
            $title = 'Others';
        }
        $description = $reports->description;
        $image = $reports->image;
        return view('tenant.report.edit', [
            'report' =>$reports,
            'vtitle' => $vtitle,
            'title' => $title,
            'description' => $description,
            'image' => $image,
        ]);
    }

    public function update(Request $request, int $id){
        $path = '';
        $filename = '';

        $reports = Report::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/report/';
            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldimage = $reports->image;
            if(File::exists($oldimage)){
                File::delete($oldimage);
            }

            Report::findOrFail($id)->update([
                'image' => $path.$filename,
            ]);
        }

        Report::findOrFail($id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);


        return back()->with('status','Complaint Updated');
    }

    public function destroy(int $id){
        $reports = Report::findOrFail($id);
        $oldimage = $reports->image;
        if(File::exists($oldimage)){
            File::delete($oldimage);
        }
        Report::findOrFail($id)->delete();

        return back()->with('status','Complaint Deleted');
    }

    public function avReport() {
        $reports = Report::where('agent_id', Auth::guard('staff')->user()->id)
                         ->orderBy('created_at', 'desc') // Order by 'created_at' in descending order
                         ->get();
        return view('agent.report.AReport', compact('reports'));
    }


    public function avView(int $id){
        $reports = Report::findOrFail($id);
        return view('agent.report.reply', compact('reports'));
    }

    public function avReply(Request $request, int $id){
        $reports = Report::findOrFail($id);
        $request->validate([
            'remark' => 'required',
        ]);

        $reports->remark = $request->remark;
        $reports->status = 1;
        $reports->save();

        

        return back()->with('status','Report Remarked');
    }

    public function svReport() {
        $reports = Report::all();
        return view('staff.report.SReport', compact('reports'));
    }

    public function svView(int $id){
        $reports = Report::findOrFail($id);
        return view('staff.report.reply', compact('reports'));
    }

    public function svReply(Request $request, int $id){
        $reports = Report::findOrFail($id);
        $request->validate([
            'remark' => 'required',
        ]);

        $reports->remark = $request->remark;
        $reports->status = 1;
        $reports->save();

        return back()->with('status','Report Remarked');
    }
}
