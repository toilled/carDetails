<?php

class Car
{
    /** @var string */
    private string $make;
    /** @var string */
    private string $model;
    /** @var string */
    private string $colour;
    /** @var string */
    private string $expiryDate;
    /** @var int */
    private int $failedMots;

    /**
     * @throws Exception
     */
    function __construct()
    {
        if (!isset($_POST['reg']) || strlen(trim($_POST['reg'])) === 0) {
            throw new Exception("You must provide a registration number to check!");
        }

        // API cannot find result if the registration number is entered with spaces
        $regNo = str_replace(' ', '', $_POST['reg']);
        $jsonResponse = $this->getApiResponse($regNo);

        $responseArray = json_decode($jsonResponse, true);
        if ($responseArray == null) {
            throw new Exception("Could not process response from API!");
        }

        if (isset($responseArray['errorMessage'])) {
            throw new Exception($responseArray['errorMessage']);
        }

        $carArray = $responseArray[0];

        $this->setCarDetails($carArray);
        $this->setMotDetails($carArray);
    }

    /**
     * @return false|string
     */
    private static function getApiResponse(string $regNo)
    {
        $apiKey = ""; // TODO Put your API key here

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HTTPHEADER, ["x-api-key: {$apiKey}"]);

        $url = sprintf("%s?%s", 'https://beta.check-mot.service.gov.uk/trade/vehicles/mot-tests', http_build_query(['registration' => $regNo]));

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * @param array $carArray
     * @return void
     */
    private function setCarDetails(array $carArray)
    {
        $this->make = ucfirst(strtolower($carArray['make']));
        $this->model = ucfirst(strtolower($carArray['model']));
        $this->colour = $carArray['primaryColour'];
        $this->expiryDate = $carArray['motTestExpiryDate'] ?? 'No current MOT';
    }

    /**
     * @param array $carArray
     * @return void
     */
    private function setMotDetails(array $carArray)
    {
        $motTests = $carArray['motTests'];
        $this->failedMots = 0;

        foreach ($motTests as $motTest) {
            if ($motTest['testResult'] == 'FAILED') {
                $this->failedMots++;
            }
        }
    }

    /**
     * @return string
     */
    public function getMake(): string
    {
        return $this->make;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getColour(): string
    {
        return $this->colour;
    }

    /**
     * @return string
     */
    public function getExpiryDate(): string
    {
        return $this->expiryDate;
    }

    /**
     * @return int
     */
    public function getFailedMots(): int
    {
        return $this->failedMots;
    }
}