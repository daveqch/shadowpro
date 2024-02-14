<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IrrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        echo "rakib";
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('irr.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->amounts);

        $investment = -$request->intinal_investment;

        $cash[] = $investment;
        foreach ($request->amounts as $amounts) {
            $cash[] = intval($amounts);
        }

        $irr = $this->calculateIRR($cash);

        return view('irr.create', compact('irr', 'request'));
    }

    function calculateIRR($cashflows, $guess = 0.1, $maxIterations = 100, $tolerance = 1.0e-6)
    {
        $irr = $guess;
        $iteration = 0;

        while ($iteration < $maxIterations) {
            $npv = 0;
            $derivative = 0;

            for ($i = 0; $i < count($cashflows); $i++) {
                $npv += $cashflows[$i] / pow(1 + $irr, $i);
                $derivative -= $i * $cashflows[$i] / pow(1 + $irr, $i + 1);
            }

            $irr = $irr - $npv / $derivative;

            if (abs($npv) < $tolerance) {
                return round($irr * 100, 2); // Return IRR as a percentage
            }

            $iteration++;
        }

        return null; // If IRR doesn't converge
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
