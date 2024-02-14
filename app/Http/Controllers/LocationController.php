<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LocationController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:location-read|location-create|location-update|location-delete', ['only' => ['index']]);
        $this->middleware('permission:location-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:location-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:location-delete', ['only' => ['destroy']]);
        $this->middleware('permission:location-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $datum = $this->filter($request)->paginate(10)->withQueryString();
        return view('location.index', compact('datum'));
    }

    private function filter(Request $request)
    {
        $query = Location::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('location_name', 'like', '%' . $request->name . '%');
        if ($request->city)
            $query->Where('city', 'like', '%' . $request->city . '%');

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $countryList = $this->getCountry();
        return view('location.create', compact('company', 'countryList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'location_name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
            'country' => 'required'
        ]);

        DB::beginTransaction();
        try {
            Location::create([
                'location_name' => request('location_name'),
                'company_id' => session('company_id'),
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'country' => $request->country,
                'enabled' => 1
            ]);

            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('location.index')->with('success', trans('Added Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('location.index')->with('error', trans('Not Added Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $countryList = $this->getCountry();
        return view('location.edit', compact('company', 'location', 'countryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $this->validate($request, [
            'location_name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
            'country' => 'required',
            'enabled' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $location->update([
                'location_name' => request('location_name'),
                'company_id' => Session::get('company_id'),
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'country' => $request->country,
                'enabled' => $request->enabled
            ]);

            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('location.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('location.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('location.index')->with('success', trans('Deleted Successfully'));
    }
}
