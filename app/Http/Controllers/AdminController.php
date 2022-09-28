<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendStatusUpdatedJob;
use App\Mail\SendUpdateStatusEmail;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function dashboard(){
        return view('admin.dashboard');
    }

    public function adminComplaints(){
        $data['content_header'] = 'Complaints';
        $data['title'] = 'Dashboard';
        return view('admin.complaints',$data);
    }

    public function comlaintsDataTable(){
    $records = array();
      $records = Complaints::with('user')
        ->orderBy('created_at', 'desc');

        // dd($records);
      return datatables($records)

      ->editColumn('created_at', function ($request) {
        return $request->created_at->format('F j, Y, g:i a'); // human readable format
      })
      ->addColumn('user', function ($records) {
            $rhtml = $records->user->name;
            return $rhtml;
      })

      ->editColumn('status', function ($records) {
        $rhtml = '<select name = "status" class ="form-control statusChange" data-id ='.$records->id.'>';
        $rhtml .= '<option value = "Resolved complaints" '.($records->status == "Resolved complaints" ? "selected": "").'>Resolved complaints</option>';
        $rhtml .= '<option value ="Active Complaints" '.($records->status == "Active Complaints" ? "selected": "").'>Active Complaints</option>';
        $rhtml .= '<option value ="Unassigned Complaints" '.($records->status == "Unassigned Complaints" ? "selected": "").'>Unassigned Complaints</option>';
        $rhtml .= '</select>';
        return $rhtml;
      })

      ->rawColumns(['action','status'])

      ->toJson();
    }


    public function updateStatus(Request $request, $id){
        // dd($id);
        $complaint = Complaints::where('id', $id)->first();
        $complaint->status = $request->status;
        $complaint->save();
        $complaintUser = $complaint->user->email;
        dispatch(new SendStatusUpdatedJob($complaint, $complaintUser, $complaint->user->phone));
        return response()->json([
            'status' => 1,
            'message'=> 'Record has been updated successfully'
        ]);

    }

    public function bulkunassign(Request $request){
        $ids = $request->input('cbx');
        $noOfRecords = Complaints::whereIn('id', $ids)->update(['status' => 'Unassigned Complaints']);
        $complaints = Complaints::whereIn('id', $ids)->get();
        foreach($complaints as $complaint){
            dispatch(new SendStatusUpdatedJob($complaint, $complaint->user->email,$complaint->user->phone));
        }
        return redirect()->route('adminComplaints')->with('success', $noOfRecords.' Records Updated Successfully');
    }

    public function bulkactive(Request $request){
        $ids = $request->input('cbx');
        $noOfRecords = Complaints::whereIn('id', $ids)->update(['status' => 'Active Complaints']);
        $complaints = Complaints::whereIn('id', $ids)->get();
        foreach($complaints as $complaint){
            dispatch(new SendStatusUpdatedJob($complaint, $complaint->user->email,$complaint->user->phone));
        }
        return redirect()->route('adminComplaints')->with('success', $noOfRecords.' Records Updated Successfully');
    }

    public function bulkresolved(Request $request){
        $ids = $request->input('cbx');
        $noOfRecords = Complaints::whereIn('id', $ids)->update(['status' => 'Resolved complaints']);
        $complaints = Complaints::whereIn('id', $ids)->get();
        foreach($complaints as $complaint){
            dispatch(new SendStatusUpdatedJob($complaint, $complaint->user->email,$complaint->user->phone));
        }
        return redirect()->route('adminComplaints')->with('success', $noOfRecords.' Records Updated Successfully');
    }
}
