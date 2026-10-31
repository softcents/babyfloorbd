<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use Illuminate\Support\Facades\Cache;
use Toastr;

class FeatureController extends Controller
{
    protected function clearFeatureCache()
    {
        Cache::forget('homepage_features');
    }

    public function index()
    {
        $show_data = Feature::orderBy('sort_order')->orderBy('id')->get();
        return view('backEnd.feature.index', compact('show_data'));
    }

    public function create()
    {
        return view('backEnd.feature.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        $input = $request->all();
        $input['status'] = $request->has('status') && $request->status == '1' ? 1 : 0;
        $input['sort_order'] = (int) ($request->sort_order ?? 0);

        Feature::create($input);
        $this->clearFeatureCache();

        Toastr::success('Success', 'Feature added successfully');
        return redirect()->route('features.index');
    }

    public function edit($id)
    {
        $edit_data = Feature::findOrFail($id);
        return view('backEnd.feature.edit', compact('edit_data'));
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'icon' => 'required',
                'title' => 'required',
                'description' => 'nullable',
                'sort_order' => 'nullable|integer',
            ]);

            $update_data = Feature::findOrFail($request->id);

            $input = $request->all();
            $input['status'] = $request->has('status') && $request->status == '1' ? 1 : 0;
            $input['sort_order'] = (int) ($request->sort_order ?? 0);

            $update_data->update($input);
            $this->clearFeatureCache();

            Toastr::success('Success', 'Feature updated successfully');
        } catch (\Exception $e) {
            Toastr::error('Error', 'Failed to update feature. Please try again.');
        }

        return redirect()->route('features.index');
    }

    public function inactive(Request $request)
    {
        try {
            $request->validate(['hidden_id' => 'required|exists:features,id']);
            $item = Feature::findOrFail($request->hidden_id);
            $item->status = 0;
            $item->save();
            $this->clearFeatureCache();
            Toastr::success('Success', 'Feature inactive successfully');
        } catch (\Exception $e) {
            Toastr::error('Error', 'Failed to deactivate feature.');
        }
        return redirect()->back();
    }

    public function active(Request $request)
    {
        try {
            $request->validate(['hidden_id' => 'required|exists:features,id']);
            $item = Feature::findOrFail($request->hidden_id);
            $item->status = 1;
            $item->save();
            $this->clearFeatureCache();
            Toastr::success('Success', 'Feature active successfully');
        } catch (\Exception $e) {
            Toastr::error('Error', 'Failed to activate feature.');
        }
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate(['hidden_id' => 'required|exists:features,id']);
            $item = Feature::findOrFail($request->hidden_id);
            $item->delete();
            $this->clearFeatureCache();
            Toastr::success('Success', 'Feature deleted successfully');
        } catch (\Exception $e) {
            Toastr::error('Error', 'Failed to delete feature.');
        }
        return redirect()->back();
    }
}
