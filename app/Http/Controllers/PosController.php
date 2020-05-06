<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ACTIVITY_TYPE;
use App\Exports\PosExport;
use App\Handover;
use App\Pos;
use App\POS_STATUS;
use App\Tool;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PosController extends Controller
{
    /*
     * List pos
     * */
    public function index()
    {
        $pos_list = Pos::paginate(12);
        $damaged = Pos::damaged();
        $tools = Tool::all();
        $title = "All POS";
        return view('pos-list', compact('tools', 'pos_list', 'damaged', 'title'));
    }

    /*
     * Filter pos
     * */
    public function filter($status)
    {
        $pos_list = Pos::filter($status)->paginate(12);
        $damaged = Pos::damaged();
        $tools = Tool::all();
        $title = $status." POS";
        return view('pos-list', compact('tools', 'pos_list', 'damaged', 'title'));
    }

    /*
     * Filter pos
     * */
    public function search()
    {
        $request = Request::capture();
        $search=$request->search;
        //TODO search by posid
//        $pos_list = Pos::where('id','like',"%$search")->orwhere('sno','like',"%$search%")->orwhere('imei','like',"%$search%")->paginate(12);
        $pos_list = Pos::search($search)->paginate(12);
        $damaged = Pos::damaged();
        $tools = Tool::all();
        $title = "Search '$search'";
        return view('pos-list', compact('tools', 'pos_list', 'damaged', 'title'));
    }

    /*
     * Show pos
     * */
    public function show($id)
    {
        $pos = Pos::find($id);
        if ($pos == null) {
            return redirect(route('pos'))->with('msg', 'POS with ID ' . $id . ' not found');
        }
        return view('view-pos', compact('pos'));
    }

    /*
     * Get pos JSON
     * */
    public function getPos($id)
    {
        $pos = Pos::find($id);
        if ($pos == null) {
            return response()->json([
                'message' => 'No pos found',
            ]);
        }
        $result = [
            'imei' => $pos->imei,
            'sno' => $pos->sno,
            'tools' => $pos->latestToolsStatus()
        ];
        return response()->json([
            'message' => 'success',
            'data' => $result
        ]);
    }

    /*
     * Add new Pos
     * */
    public function add(Request $request)
    {
        $data = $request->validate([
            'imei' => 'required|unique:pos',
            'sno' => 'required|unique:pos',
        ]);
        $pos = Pos::create(['imei' => $request->imei, 'sno' => $request->sno, 'user_id' => auth()->user()->id]);
        $tools = [];
        foreach (Tool::all() as $tool) {
            $tools[$tool->name] = $request->exists($tool->name);
        }
        $pos->initialToolsStatus()->create(['tools' => $tools]);
        return redirect(route('pos'))->with('msg', 'New POS added');
    }

    /*
     * Update Pos
     * */
    public function update(Request $request, $id)
    {
//        return $request->all();
        $pos = Pos::find($id);
        if ($pos == null) {
            return redirect(route('pos'))->with('msg', 'POS with ID: ' . $id . ' not found');
        }
        $data = $request->validate([
            'imei' => 'required',
            'sno' => 'required',
        ]);

        //check IMEI redundance
        if (Pos::where('id', '!=', $pos->id)->where('imei', $request->imei)->count() > 0) {
            return redirect(route('pos'))->with('msg', 'The IMEI entered is used by another POS');
        }

        //check SNo redundance
        if (Pos::where('id', '!=', $pos->id)->where('sno', $request->sno)->count() > 0) {
            return redirect(route('pos'))->with('msg', 'The Serial No. entered is used by another POS');
        }

        //create tools json
        $tools = [];
        foreach (Tool::all() as $tool) {
            $tools[$tool->name] = $request->exists($tool->name);
        }

        //update pos
        $pos->imei = $request->imei;
        $pos->sno = $request->sno;
        $pos->latestToolsStatusHolder->tools = $tools;
        $pos->latestToolsStatusHolder->save();
        $pos->save();
        return redirect(route('pos'))->with('msg', 'POS Updated');
    }

    /*
     * Hand over pos
     * */
    public function handover(Request $request, $id)
    {
        $data = $request->validate([
            'fname' => 'required',
            'mname' => 'required',
            'lname' => 'required',
            'posid'=> 'required',
            'user_phone' => 'required',
            'pos_phone' => 'required',
            'kata' => 'required'
        ]);

        $pos = Pos::find($id);
        if ($pos == null) {
            return redirect(route('pos'))->with('msg', 'Pos with ID: ' . $id . ' Not Found');
        }

        if ($this->isPosIdInUseByActivePos($request->posid))
            return redirect(route('view.pos',$pos->id))->withInput($data)->with('error','POS ID '.$request->posid.' is in use by another POS in Active site');

        $data['user_id'] = auth()->id();
        //create handover activity
        $handover = Handover::create($data);
        //record tool status
        $handover->toolsStatus()->create(['tools' => $pos->latestToolsStatus()]);
        //record event
        $handover->event()->create(['pos_id' => $id]);

        return redirect(route('pos'))->with('msg', 'POS Handed To User');
    }

    /*
     * Check if posid is taken by active pos
     * */
    function isPosIdInUseByActivePos($posid){
        return Pos::filter(POS_STATUS::Active_Site)->contains(function (Pos $pos) use ($posid){
            return $pos->lastHandOver()->posid==$posid;
        });
    }

    /*
     * Return pos
     * */
    public function returnPos(Request $request, $id)
    {
//        return $request->all();
//        $data = $request->validate([
//            'description' => 'required',
//        ]);

        $pos = Pos::find($id);
        if ($pos == null) {
            return redirect(route('pos'))->with('msg', 'Pos with ID: ' . $id . ' Not Found');
        }

        //create tools json
        $tools = [];
        foreach (Tool::all() as $tool) {
            $tools[$tool->name] = $request->exists($tool->name);
        }

        $activity_type = $pos->currentstatus == POS_STATUS::Active_Site ? ACTIVITY_TYPE::FromCustomer : ACTIVITY_TYPE::FromTech;
        //create activity
        $activity = Activity::create([
            'type_id' => $activity_type,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        //record tools status
        $activity->toolsStatus()->create(['tools' => $tools]);

        //record event
        $activity->event()->create(['pos_id' => $id]);

        return redirect(route('view.pos', $id))
            ->with('msg', "POS Received from " . $pos->currentstatus == POS_STATUS::Active_Site ? 'Customer' : 'Technician');
    }

    /*
     * Damaged pos to tech
     * */
    public function damaged(Request $request)
    {
//        return $request->all();
        $data = $request->validate([
            'ids' => 'required',
            'description' => 'required'
        ]);

        foreach ($request->ids as $id) {
            $pos = Pos::find($id);

            //create activity
            $activity = Activity::create([
                'type_id' => ACTIVITY_TYPE::ToTech,
                'description' => $request->description,
                'user_id' => auth()->id()
            ]);

            //record tool status
            $activity->toolsStatus()->create(['tools' => $pos->latestToolsStatus()]);

            //record event
            $activity->event()->create(['pos_id' => $id]);
        }

        return redirect(route('pos'))->with('msg', 'POS records submitted successfully');
    }

    /*
     * Show report view
     * */
    public function report(){
//        return Pos::filter(POS_STATUS::Active_Site)->get();
         $pos_list = Pos::paginate(10);
        $title = "All POS";
        return view('pos-report',compact('pos_list','title'));
    }

    public function export(){
//        $pos_list = Pos::paginate(12);
//        return view('pos-list2',compact('pos_list'));

        return new PosExport();
    }
}
