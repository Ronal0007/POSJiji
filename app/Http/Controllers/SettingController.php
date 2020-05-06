<?php

namespace App\Http\Controllers;

use App\ACTIVITY_TYPE;
use App\Tool;
use App\Type;
use App\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function init()
    {
        //POS tools
        $tools = ['Battery', 'Display', 'Charger',];
        foreach ($tools as $tool) {
            Tool::create(['name' => $tool]);
        }

        //Activity types (enum)
        $types = [
            'FromCustomer' => ACTIVITY_TYPE::FromCustomer,
            'ToTech' => ACTIVITY_TYPE::ToTech,
            'FromTech' => ACTIVITY_TYPE::FromTech
        ];
        foreach ($types as $name => $id) {
            Type::create(['id' => $id, 'title' => $name]);
        }
        $users = [
            [
                'fname' => 'Alice',
                'lname' => 'Bob',
                'email' => 'email@email.com',
                'password' => bcrypt('user'),
            ]
        ];
        foreach ($users as $user) {
            User::create($user);
        }

        return 'initialized';
    }

    /*
     * View tools
     * */
    public function setting()
    {
        $tools = Tool::all();
        return view('setting', compact('tools'));
    }

    /*
     * Add tool
     * */
    public function addTool(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        Tool::create(['name' => $request->name]);
        return redirect(route('setting'))->with('msg', 'Tool Added');
    }

    /*
     * Add tool
     * */
    public function updateTool(Request $request, $id)
    {
        $tool = Tool::find($id);
        if ($tool == null) {
            return redirect(route('setting'))->with('msg', 'Tool Not Found');
        }
        $this->validate($request, [
            'name' => 'required'
        ]);

        $tool->update(['name' => $request->name]);
        return redirect(route('setting'))->with('msg', 'Tool Updated');
    }

    /*
     * Delete Tool
     * */
    public function deleteTool($id){
        $tool = Tool::find($id);
        if ($tool == null) {
            return redirect(route('setting'))->with('msg', 'Tool Not Found');
        }

        $tool->delete();
        return redirect(route('setting'))->with('msg', 'Tool Deleted');
    }
}


