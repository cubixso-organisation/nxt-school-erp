<?php

use yii\base\Component;

class DocumentGenerator extends Component
{

    public function generateMarksheetPdf($finalStructure)
    {



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8080/api/v1/document-generator/generatePdf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $finalStructure,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJ1c2VyX3JvbGUiOiJhZG1pbiIsInVzZXJuYW1lIjoiYWRtaW5AZXN0dWRlbnQudGVjaCIsImlhdCI6MTcyMzg3ODYwOH0.8oefPyVqJwW0gctiHpkohwXeMzqLYB33o8giAewCgH0',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
