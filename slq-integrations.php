<?php 
    /**
     * class that connects to the SLQ api and returns data from there.
     * functions:
	 *      setApiName - Sets the name of the API called
	 *      setCache - Sets the folder and file of where the cache is/will be stored
     *      setApiLimit - Sets the limits of results from the API call
	 *      setResourceId - Sets the resource ID using the API name
     *      setLanguageUsed - Set the language used for the language API
     *      setupURL - Sets up the URL for the API call
	 *      checkIfCached - Checks to see whether the data is already in cache
	 *      extractLanguageUsed - If cached, extracts the language used for the cached data
     *      getData - If cache then retrieve data from cache else extracts the data from the api url and saves it in a cache
     *      returnRawData - Returns the raw data
     *      returnLanguageUsed - Returns the language used in the API
     *      extractData - Extracts a particular parameter from the record data
     *      extractNumberOfRandomRecords - Extracts a number of random records from the record data
     *      removeBlankRecords - Removes records from the record data that have blanks in particular fields
     *      filterByCondition - Removes the records from the record data that don't satisfy a condition
     *      printRecordsAsHTML - Extract attributes into a HTML table
     */
    class SlqIntegrations 
    {
        private $api_url;
        private $cache;
        private $api_cached;
        private $rawData;
        private $recorddata;
        private $selectRecords;
        private $api_name;
        private $limit;
        private $resourceID;
        private $languageUsed;

        const PORTRAIT_ID = "cf6e12d8-bd8d-4232-9843-7fa3195cee1c";
        const NEWSPAPER_ID = "5bc00f98-2d96-47d6-a0ca-2089ebd1130d";
        const LANGUAGE_ID = array (
            "Barunggam" => "ec6accc5-07d9-44d3-bf6d-0b363a73f3ef",
            // "Brisbane animal" => "3e39dd7d-e777-4f47-9160-95aaca34bff5",         //Removed due to no pronunciation
            //"Butchulla" => "35350512-a668-4bde-a58c-46092a07d1de",                //Removed due to no pronunciation
            //"Dharumbal" => "b86e4743-d65b-4f68-935a-1c57480cde3e",                //Removed due to no pronunciation
            "Duungidjawu" => "81f76d7f-4ada-40c3-976c-6a24b459b1e0",
            //"Gooreng" => "a0ca1aee-e886-4ea1-978a-f0c80d4558be",                  //Removed due to no pronunciation
            //"Gunggari" => "2d2ef02c-1c89-4011-91ba-a4d6e95551df",                 //Removed due to no pronunciation
            //"Kala-kawaw-ya" => "92fde7ae-f2e8-484e-81db-f717cc5d2707",            //Removed due to no pronunciation
            //"Kuku-yalanji" => "ab7ab13e-a66c-4a50-b5d2-034e4a0683bc",             //Removed due to no pronunciation
            //"Nggerikudi" => "91dbac9b-d853-424e-8ce0-29a8bc74399b",               //Removed due to no pronunciation
            //"Nggerrikwidhi" => "c06c3742-d9df-4eb4-9264-21bba62a1a2a",            //Removed due to no pronunciation
            // "Number" => "2483d545-8104-4709-8f11-af53207dbb6e",                 //Removed due to no pronunciation
            // "Say g'day" => "a02450ec-15b2-4185-a973-c8868e70e928",              //Removed due to no pronunciation
            // "Torres strait islander" => "7cd323ec-0c67-4994-a52b-d3f04be0fd74", //Removed due to no pronunciation
            "Turubul" => "da3ac749-840a-479d-9466-09eb8d6e389d",
            "Wakka Wakka" => "dfa3b2cc-0788-456a-b132-2db687b6257a",               
            //"Warrgamay" => "c340e92d-ed7f-478e-ab7e-d79137441327",               //Removed due to no pronunciation
            "Yugambeh" => "4ea75e17-cb1e-473e-9b6b-c0227b1fa787",
            "Yugarabul" => "34b5f663-6c32-4ad9-8f8e-7f63ce5156f2",
            "Yuggera" => "ea4031e6-dc7a-4584-ac38-482f570a9637",
            "Yuwaalaraay" => "ad345bd7-8544-4ff4-9b0e-8225b4050f6f",
            "Yuwibara" => "1a7f0c01-d27f-4865-be23-c2276655a529"
        );


        /**
         * Sets the name of the API called
         *
         * @param [string] $apiName: the name of the api to use
         * @return void
         */
        public function setApiName($apiName)
        {   
            $this->api_name = $apiName;

            //logConsole($this->api_name . ": Name set");
        }


        /**
         * Sets the folder and file of where the cache is/will be stored
         *
         * @return void
         */
        public function setCache()
        {   
            if($this->api_name  == "language"){
                $this->cache = "cache/slq-cache-" . $this->api_name  . "-" . $this->languageUsed .".json";
            } 
            else {
                $this->cache = "cache/slq-cache-" . $this->api_name  . ".json";
            }

            //logConsole($this->api_name . ": Cache set as: " . $this->cache);
        }


        /**
         * Set the language used for the language API
         *
         * @param [string] $language - The language to use
         * @return void
         */
        public function setLanguageUsed($language){
            $this->languageUsed = $language;
            //logConsole($this->api_name . ": language used: ". $this->languageUsed);
            $this->resourceID = self::LANGUAGE_ID[$this->languageUsed]; 
            //logConsole($this->api_name . ": resource id: ". $this->resourceID);

        }


        /**
         * Sets the limits of results from the API call
         *
         * @param [integer] $limit: the amount of results to fetch from the API
         * @return void
         */
        public function setApiLimit($limit)
        {   
            $this->limit = $limit;
            //logConsole($this->api_name . ": Limit set to: " . $limit);
        }


        /**
         * Sets the resource ID using the API name
         *
         * @return void
         */
        public function setResourceId()
        {   
            if ($this->api_name == "language"){
                // Randomise the array and extract a key and value
                $key = array_rand(self::LANGUAGE_ID);
                $value = self::LANGUAGE_ID[$key];
                $this->languageUsed = $key;
                //logConsole($this->api_name . ": language used: ". $this->languageUsed);
                $this->resourceID = $value; 
            }
            else if ($this->api_name == "portrait"){
                $this->resourceID = self::PORTRAIT_ID;
            }
            else if ($this->api_name == "newspaper"){
                $this->resourceID = self::NEWSPAPER_ID;
            }

            //logConsole($this->api_name . ": resource id: ". $this->resourceID);
        }


        /**
         * Sets up the URL for the API call
         *
         * @return void
         */
        private function setupURL()
        {
            $this->api_url = "http://data.gov.au/api/action/datastore_search?resource_id=" . $this->resourceID . "&limit=" .  $this->limit;
            //logConsole($this->api_name . ": URL: " . $this->api_url);
        }

        
        /**
         * Checks to see whether the data is already in cache
         *
         * @return boolean: whether the cache file exists or not
         */
        public function checkIfCached()
        {
            if(file_exists($this->cache)){
                if(file_get_contents($this->cache) == ""){
                    //logConsole($this->api_name . ": cache is empty");
                    return false;
                }
                //logConsole($this->api_name . ": API is cached");
                $this->api_cached = true;
                return true;
            }
            else {
                //logConsole($this->api_name . ": API is not cached");
                $this->api_cached = false;
                return false;
            }
        }


        /**
         * If cached, extracts the language used for the cached data
         *
         * @return void
         */
        public function extractLanguageUsed()
        {
            if($this->api_cached){
                $this->resourceID = $this->rawData["result"]["resource_id"];
                //logConsole($this->api_name . ": resource id: ". $this->resourceID);

                $key = array_search ($this->resourceID, self::LANGUAGE_ID);
                $this->languageUsed = $key;
                //logConsole($this->api_name . ": language used: ". $this->languageUsed);
            } 
        }


        /**
         * If cache then retrieve data from cache else extracts the data from the api url and saves it in a cache
         *
         * @return boolean: whether data extractions was successful
         */
        public function getData()
        {
            // Check if cached and extract data
            if($this->api_cached) {
                $this->rawData = file_get_contents($this->cache);
                //logConsole($this->api_name . ": data collected from cache");
            }
            else {
                $this->setupURL();
                $this->rawData = file_get_contents($this->api_url);
                // create the cached directory
                if (!file_exists("cache")) {
                    mkdir("cache", 0777, true);
                }
                $cacheFile = fopen($this->cache, "w");
                fwrite($cacheFile, $this->rawData);
                //file_put_contents($this->cache, $this->rawData);  // NEED TO GRANT PERMISSIONS 777
                //logConsole($this->api_name . ": data collected from API");
            }
            $this->rawData = json_decode($this->rawData, true);

            // Check whether raw data was successful
            if ($this->rawData["success"] == true){

                // Used for finding the right heading of API. Should delete before live.
                $dataKeys = [];
                foreach(array_keys($this->rawData["result"]["records"][0]) as $key)
                    array_push($dataKeys, $key);
                //logConsole("API keys:");
                //logConsole($dataKeys);

                // Extract record data
                $this->recordData = $this->rawData["result"]["records"];
                return true;
            }
            else {
                return false;
            }
        }


        /**
         * Returns the raw data
         *
         * @return json: the raw record data extracted from the API encoded to JSON
         */
        public function returnRawData()
        {
            return $this->recordData;
        }


        /**
         * Returns the language used in the API
         *
         * @return string: the lanuage used in the cache API
         */
        public function returnLanguageUsed(){
            return $this->languageUsed;
        }


        /**
         * Extracts a particular parameter from the record data
         *
         * @param [string] $attribute: The attribute to be extracted
         * @return array: the attributes for the records in the record data
         */
        public function extractData($attribute)
        {
            $extractedData = [];
            foreach($this->selectRecords as $recordValue) {       
                $recordElement = $recordValue[$attribute];
                if ($recordElement){
                        array_push($extractedData, $recordElement);
                }
            }
            return $extractedData;
        }


        /**
         * Extracts a number of random records from the record data
         *
         * @param [integer] $numberOfResults: The number of random results to update the select records variable
         * @return void
         */
        public function extractNumberOfRandomRecords($numberOfResults)
        {
            $allRecords = $this->recordData;
            $selectRecords = [];

            // Create an array full of random numbers
            $randomNumbersArray = range(0, count($allRecords) - 1);
            shuffle($randomNumbersArray);

            // For the number of results required loops through and extract a random record
            $count = 0;
            while($count < $numberOfResults){
                array_push($selectRecords, $allRecords[$randomNumbersArray[$count]]);
                $count++;
            }

            // Display warning if not enough records were found
            if (count($allRecords) < $numberOfResults){
                //logConsole("WARNING: Count of filtered results is less then number of results requested. Results: " . count($allRecords));
            }
            $this->selectRecords = $selectRecords;
        }


        /**
         * Removes records from the record data that have blanks in particular fields
         *
         * @param [string] $attributes: The attributes that if null will cause the record be removed
         * @return void
         */
        public function removeBlankRecords($attributes)
        {
            $allRecords = $this->recordData;
            $nonBlankRecords = [];

            // Loop through each record
            foreach($allRecords as $recordKey => $recordValue){
                $validRecord = true;

                // Check if records attribute supplied is blank
                foreach($attributes as $attribute){
                    if ($recordValue[$attribute] == false){
                        $validRecord = false;
                   }
                }

                // If valid record then add to new array
                if ($validRecord){
                    array_push($nonBlankRecords, $allRecords[$recordKey]);
                }
            }
            $this->recordData = $nonBlankRecords;
        }
        

        /**
         * Removes the records from the record data that don't satisfy a condition
         *
         * @param [string] $conditionKey: the parameter to apply the condition value to
         * @param [string] $conditionValue: the value of the key to filter the results and update filtered records variable
         * @return void
         */
        public function filterByCondition($conditionKey, $conditionValue)
        {
            $recordlist = $this->recordData;
            $filteredRecords = [];
            foreach($recordlist as $recordKey => $recordValue){

                if (preg_match($conditionValue, $recordValue[$conditionKey])){
                    array_push($filteredRecords, $recordlist[$recordKey]);
                }
            }

            $this->recordData = $filteredRecords;
        }


        /**
         * Prints out the API attributes into a HTML table
         *
         * @param [list] $attributes - List of attributes to print out
         * @return [string] HTML table code
         */
        public function printRecordsAsHTML($attributes){
            $htmlData = "";

            foreach($this->selectRecords as $recordValue) { 
                if ($recordValue){
                    $htmlData = $htmlData . "<tr>";
                    foreach($attributes as $attribute) { 
                        $htmlData = $htmlData. "<td>" . $recordValue[$attribute] . "</td>";
                    }
                    $htmlData = $htmlData . "</tr>"  ; 
                }
            }
            return $htmlData;
        }


    }
?>