<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrackingService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('tracking.api_key');
        $this->baseUrl = config('tracking.base_url');
    }

    /**
     * Register a new shipment with Tracking.my
     *
     * @param string $trackingNumber
     * @param string $courier
     * @param string $orderId
     * @return array|null
     */
    public function registerShipment($trackingNumber, $courier, $orderId)
    {
        if (empty($this->apiKey)) {
            Log::warning('Tracking.my API key is not configured.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Tracking-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/shipments", [
                        'tracking_number' => $trackingNumber,
                        'courier' => $courier,
                        'order_number' => $orderId,
                    ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Tracking.my API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Tracking.my Connection Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get tracking status for a shipment
     *
     * @param string $trackingNumber
     * @return array|null
     */
    public function getTrackingStatus($trackingNumber)
    {
        if (empty($this->apiKey)) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Tracking-Api-Key' => $this->apiKey,
            ])->get("{$this->baseUrl}/track", [
                        'tracking_number' => $trackingNumber,
                    ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Tracking.my fetch error: ' . $e->getMessage());
            return null;
        }
    }
}
