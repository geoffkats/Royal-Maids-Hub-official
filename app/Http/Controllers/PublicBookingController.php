<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\CRM\BookingToLeadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicBookingController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Step 1
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'country' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'division' => 'required|string|max:50',
            'parish' => 'required|string|max:50',
            'national_id' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:2048',

            // Step 2
            'village' => 'nullable|string|max:50',
            'house_type' => 'required|string|max:50',
            'number_of_rooms' => 'required|integer|min:1',
            'special_requirements' => 'nullable|string|max:1000',

            // Step 3
            'family_size' => 'required|string|max:50',
            'children_count' => 'nullable|integer|min:0',
            'elderly_count' => 'nullable|integer|min:0',
            'pets' => 'nullable|string|max:50',
            'special_needs' => 'nullable|string|max:1000',

            // Step 4
            'package_id' => 'required|exists:packages,id',
            'service_tier' => 'nullable|string|max:50',
            'service_mode' => 'required|string|max:50',
            'work_days' => 'required|array|min:1',
            'work_days.*' => 'string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'additional_requirements' => 'nullable|string|max:1000',
        ]);

        // Derive service_tier from package if missing
        $serviceTier = $validated['service_tier'] ?? null;
        if (!$serviceTier && !empty($validated['package_id'])) {
            $pkg = \App\Models\Package::find($validated['package_id']);
            $serviceTier = $pkg?->name;
        }

        // Prepare booking data
        $bookingData = [
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'division' => $validated['division'],
            'parish' => $validated['parish'],
            'address' => $validated['village'] ?? $validated['city'],
            'package_id' => $validated['package_id'],
            'service_tier' => $serviceTier,
            'start_date' => $validated['start_date'],
            'special_requirements' => $validated['special_requirements'],
            'additional_requirements' => $validated['additional_requirements'],
            'work_days' => $validated['work_days'],
        ];

        // Use BookingToLeadService to find or create lead
        $bookingToLeadService = app(BookingToLeadService::class);
        $lead = $bookingToLeadService->findOrCreateLeadFromBooking($bookingData);

        // Check if user is authenticated and has a client account
        $clientId = null;
        if (auth()->check() && auth()->user()->role === 'client') {
            $clientModel = \App\Models\Client::where('user_id', auth()->id())->first();
            if ($clientModel) {
                $clientId = $clientModel->id;
                // Link lead to existing client if not already linked
                if (!$lead->client_id) {
                    $lead->update(['client_id' => $clientId, 'status' => 'qualified']);
                }
            }
        } else if ($lead->client_id) {
            // Lead is already linked to a client (from duplicate detection)
            $clientId = $lead->client_id;
        }

        $nationalIdPath = null;
        if ($request->hasFile('national_id')) {
            $nationalIdPath = $request->file('national_id')->store('national_ids', 'public');
        }

        // Normalize family size to integer if a range label was chosen
        $normalizedFamilySize = match (trim((string)($validated['family_size'] ?? ''))) {
            '1-2' => 2,
            '3-4' => 4,
            '5-6' => 6,
            '7+', '7 +' => 7,
            default => is_numeric($validated['family_size'] ?? null) ? (int) $validated['family_size'] : null,
        };

        $booking = Booking::create([
            'lead_id' => $lead->id, // Link booking to lead
            'client_id' => $clientId, // May be null initially, set when lead converts to client
            'maid_id' => null,
            'package_id' => $validated['package_id'] ?? null,
            'booking_type' => 'brokerage',
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => 'pending',
            'notes' => $validated['additional_requirements'] ?? null,

            // Contact
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'division' => $validated['division'],
            'parish' => $validated['parish'],
            'national_id_path' => $nationalIdPath,

            // Home & environment
            'home_type' => $validated['house_type'] ?? null,
            'bedrooms' => $validated['number_of_rooms'] ?? 1,
            'bathrooms' => 1,

            // Household
            'family_size' => $normalizedFamilySize,
            'pets' => $validated['pets'] ?? null,

            // Expectations
            'service_tier' => $serviceTier,
            'service_mode' => $validated['service_mode'],
            'work_days' => $validated['work_days'],
            'anything_else' => $validated['additional_requirements'] ?? null,
        ]);

        try {
            $booking->refresh();
            $price = $booking->calculateBookingPrice();
            if ($price !== null) {
                $booking->calculated_price = $price;
                $booking->save();
            }
        } catch (\Throwable $e) {
            Log::warning('Could not calculate booking price: ' . $e->getMessage());
        }

        return back()->with('booking_submitted', true);
    }
}


