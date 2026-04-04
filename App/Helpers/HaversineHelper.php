<?php

namespace App\Helpers;

class HaversineHelper
{
    // Batas berat gratis — hardcode, ubah di sini kalau perlu
    const BATAS_BERAT_GRATIS_KG = 10;

    /**
     * Hitung jarak dalam KM antara dua koordinat.
     */
    public static function hitungJarak(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Hitung ongkir lengkap.
     *
     * Rumus:
     * ongkir = minimal_ongkir
     *        + (jarak_km × ongkir_per_km)
     *        + biaya_layanan
     *        + max(0, total_berat - 10) × biaya_per_kg
     */
    public static function hitungOngkir(
        float $jarakKm,
        int $ongkirPerKm,
        int $minimalOngkir,
        int $biayaLayanan,
        float $totalBeratKg,
        int $biayaPerKg
    ): int {
        $biayaJarak = (int) ceil($jarakKm) * $ongkirPerKm;
        $beratKena  = max(0, $totalBeratKg - self::BATAS_BERAT_GRATIS_KG);
        $biayaBerat = (int) ceil($beratKena) * $biayaPerKg;

        return $minimalOngkir + $biayaJarak + $biayaLayanan + $biayaBerat;
    }
}