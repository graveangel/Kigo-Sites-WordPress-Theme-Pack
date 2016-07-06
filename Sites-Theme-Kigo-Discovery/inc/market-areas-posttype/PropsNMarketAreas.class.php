<?php
class MktAPropsNAreas
{
    /**
     * Is meant to hold array tree of the properties inside their locations
     * @example [
     *          'United States' =>
     *          	[
     *          		'New York State' =>
     *          			[
     *          				'New York City' =>
     *          					[
     *          						[
     *          							'Name' => Nolita,
     *          							'id'=>00001 ,
     *          							.
     *          							.
     *          							... etc.
     *          					  	]
     *          				    ]
     *          		      ]
     *          	 ]
     *           ]
     * @var array
     * @deprecated The ma_tree array is not being used currently but it could be of use in the future.
     * If no use is found It can be deleted with the put_in_tree function
     */
    private $ma_tree  = [];
    public $locations = [];
    public $all_props = [];
    public $ma_types  = [
                            'Country',
                            'State',
                            // 'Region',
                            'City',
                            // 'County',
                            'Neighborhood',
                        ];
    private $api_key,
            $base_url;


    public function __construct()
    {
        $this->api_key = get_option("api_key");
        //Solution data
        $solution_data = json_decode(get_option('bapi_solutiondata'));

        //BaseURL
        $this->base_url = $solution_data->BaseURL;

        //Build tree
        $this->get_tree();
    }

    public function get_tree()
    {

        // Get all properties
        $props = $this->get_all_properties();


        foreach($props as $property)
        {

            $prop =
            [
                'id'            => $property->ID,
                'ThumbnailURL'  => $property->PrimaryImage->ThumbnailURL,
                'url'           => $property->ContextData->SEO->DetailURL,
                'Name'          => $property->ContextData->SEO->PageTitle,
                'BookingURL'    => $property->ContextData->SEO->BookingURL,
                'Country'       => $property->Country,
                'State'         => $property->State,
                'Region'        => $property->Region,
                'County'        => $property->County,
                'City'          => $property->City,
                'Neighborhood'  => $property->Neighborhood,
                'Summary'       => htmlentities($property->Summary),
                'Sleeps'        => $propertu->Sleeps,
                'Floor'         => $property->Floor,
                'Bathrooms'     => $propety->Bathrooms,
                'Bedrooms'      => $property->Bedrooms,
                'Amenities'     => addSlashes(json_encode($property->Amenities)),
                'MinRate'       => addSlashes(json_encode((array) $property->MinRate)),
                'MaxRate'       => addSlashes(json_encode((array) $property->MaxRate)),
            ];

            $this->all_props[] = $prop;

            /**
             * @deprecated The ma_tree array is not being used currently but it could be of use in the future.
             */
            $this->put_in_tree($prop);

            $this->all_named_locations($prop);
        }

        return $this->ma_tree;
    }


    /**
     * builds an array tree of the properties in side theis location
     * @param  array $prop property array
     * @return void       This function does not return a value
     * @deprecated The ma_tree array is not being used currently but it could be of use in the future.
     */
    private function put_in_tree($prop)
    {
        for($i=count($this->ma_types); $i>1; $i--)
        {
            $this->ma_tree[$prop['Country']][$prop['State']][$prop['Region']][$prop['City']][$prop['County']][$prop['Neighborhood']] = $prop;
        }
    }

    /**
     * Builds the array of locations
     * @param  array $prop an array that describes a property item
     * @return void       the function does not return a value
     */
    private function all_named_locations($prop)
    {
        foreach($prop as $name => $field)
        {
            if(in_array($name, $this->ma_types) && !empty($field))
            {
                $this->locations[$field . ' :: ' . $name] = $field;
            }
        }
    }


    /**
     * This function gets all properties of this solution.
     * @return array   returns an array;
     */
    function get_all_properties($propsToRequest = null) {

        if (empty($propsToRequest)) {
            $propIdStr = $this->xconnect('/ws/?method=search&entity=property');
            $propId = json_decode($propIdStr);
            $maxnum = 50;

            $props = array();
            $propsToRequest = array_chunk($propId->result, $maxnum); //request max elements.
        }

        foreach ($propsToRequest as $requestIds) {
            $ids = implode(',', $requestIds);
            $propsRequested = json_decode($this->xconnect('/ws/?method=get&entity=property&pagesize='.$maxnum.'&seo=1&ids=' . $ids));
            $props = array_merge($props, $propsRequested->result);
        }

        return $props;
    }

    /**
     * Makes a request to the bapi via webservice.
     * @param $requestString
     * @return string
     */
    private function xconnect($requestString) {
        //Method 1:
        $context =  stream_context_create(array(
			'http' => array(
				'method' => "GET",
				'header' => "User-Agent: InstaSites Agent\r\nReferer: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\r\n"
			)
		));

        $output = file_get_contents($this->base_url . $requestString . '&apikey=' . $this->api_key,false, $context );


        //Method 2:
        if(empty($output))
        {
            $nconnection = curl_init();
            curl_setopt($nconnection, CURLOPT_USERAGENT, 'InstaSites Agent');
            curl_setopt($nconnection, CURLOPT_URL, $this->base_url . $requestString . '&apikey=' . $this->api_key);
            curl_setopt($nconnection, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($nconnection);
            curl_close($nconnection);
        }


        return $output;
    }

    /**
     * builds the location li items
     * @param  array $ma_prop_tree the array node to build a tree branch from
     * @return string               html string
     */
    function get_ma_items($ma_prop_tree=null)
    {
        $items = '';
        if(empty($ma_prop_tree))
        $ma_prop_tree = $this->ma_tree;

        foreach($this->ma_types as $mtype)
        {
            foreach($ma_prop_tree as $name => $branch) // Sorting locations (Country, State, City, Neighborhood)
            {

                //echo a li
                $lname = empty($name) ? '-NNN-' : trim(explode('::',$name)[0]);
                $type = trim(explode('::',$name)[1]);
                if($type === $mtype)
                {
                    $items .= "<li class=\"ma-item\" data-name=\"$lname\"  data-original-name=\"$name\" data-type=\"$type\"><a class=\"button button-marketarea $type\" data-name=\"$lname\" data-type=\"$type\" data-original-name=\"$name\">$lname</a>";
                    $items .= "<ol class=\"ma-list\">";
                    if(is_array($branch) && empty($branch['id']))
                    {

                        $items .= $this->get_ma_items($branch);

                    }
                    $items .= "</ol>";
                    $items .= "</li>";
                }
            }
        }

        return $items;
    }

    /**
     * Builds the li items for properties
     * @return string html string
     */
    function get_prop_items()
    {
        $props = $this->all_props;
        $items = '';
        foreach($props as $prop)
        {
            $json_prop = rawurlencode(json_encode($prop)); //instead of urlencode so the whitespaces are encoded %20 and not +
            $name       = $prop['Name'];
            $propid     = $prop['id'];
            $items  .= "<li class=\"prop-item\" data-prop=\"$json_prop\" data-prop-id=\"$propid\"><input type=\"checkbox\" /><a class=\"button button-property\" data-prop-id=\"$propid\" data-prop=\"$json_prop\">$name <span style=\"display: none;\"> $json_prop</span></a>";
        }

        return $items;
    }



}
