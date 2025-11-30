<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvestmentPlanController extends Controller
{
    public function addInvestmentPlan(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'roi' => 'required|numeric|min:0',
                'min_amount' => 'required|numeric|min:1',
                'max_amount' => 'required|numeric|gt:min_amount',
                'duration' => 'required|integer|min:1',
            ]);

            InvestmentPlan::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Investment plan created successfully',
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating the plan.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    public function showPlansForm()
    {
        return view("dashboard.admin.investment_plan");
    }

    public function showAllPlans()
    {
        $plans = InvestmentPlan::latest()->get();
        return view('dashboard.admin.all_plans', compact('plans'));
    }

    public function deletePlan($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        $plan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Investment plan deleted successfully.'
        ]);
    }

    /**
     * Show edit form for a specific investment plan
     */
    public function editPlan($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        return view('dashboard.admin.edit_inv_plans', compact('plan'));
    }

     /**
     * Update an existing investment plan
     */
    public function updatePlan(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'roi'         => 'required|numeric|min:0',
                'min_amount'  => 'required|numeric|min:1',
                'max_amount'  => 'required|numeric|gt:min_amount',
                'duration'    => 'required|integer|min:1',
            ]);

            $plan = InvestmentPlan::findOrFail($id);
            $plan->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Investment plan updated successfully.',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating the plan.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
