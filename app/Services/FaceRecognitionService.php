<?php

namespace App\Services;

use App\Models\FaceEncoding;

class FaceRecognitionService
{
    /**
     * Récupère toutes les empreintes faciales
     * des employés actifs.
     */
    public function getAllReferences(): array
    {
        $encodings = FaceEncoding::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        $references = [];

        foreach ($encodings as $encoding) {

            // Le champ encoding est stocké en JSON
            $vector = json_decode($encoding->encoding, true);

            // Vérification de sécurité
            if (!is_array($vector) || count($vector) !== 128) {
                continue;
            }

            $references[] = [
                'user_id' => $encoding->user_id,
                'reference_number' => $encoding->reference_number,
                'encoding' => array_map(
                    'floatval',
                    $vector
                ),
            ];
        }

        return $references;
    }


    /**
 * Récupère uniquement les références faciales
 * d'un employé précis.
 */
public function getReferencesForUser(int $userId): array
{
    $encodings = FaceEncoding::where(
        'user_id',
        $userId
    )->get();

    $references = [];

    foreach ($encodings as $encoding) {

        $vector = json_decode(
            $encoding->encoding,
            true
        );

        if (
            !is_array($vector) ||
            count($vector) !== 128
        ) {
            continue;
        }

        $references[] = [
            'user_id' => $encoding->user_id,
            'reference_number' => $encoding->reference_number,
            'encoding' => array_map(
                'floatval',
                $vector
            ),
        ];
    }

    return $references;
}
}