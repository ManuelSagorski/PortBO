<?php
namespace bo\components\types;

class VesselTypes
{
    /**
     * $vesselType
     *
     * Unterschiedliche Schiffs Typen
     * @var array
     */
    public static $vesselTypes = array("Cargo", "Ro-Ro", "Tanker", "Cruise", "River-Cargo", "River-Cruise", "River-Tanker", "Other");
    
    /**
     * $vesselTypeMapper
     *
     * Mapt die unterschiedlichen Schiffstypen von Vesseltracker auf die im System verwendeten Typen
     * @var array
     */
    public static $vesselTypeMapper = array(
        "Container Ship" => "Cargo",
        "Container Ro Ro Cargo Ship" => "Cargo",
        "Passenger Ro Ro Cargo Ship" => "Ro-Ro",
        "General Cargo Ship" => "Cargo",
        "Cement Carrier" => "Cargo",
        "Chemical Tanker" => "Tanker",
        "Bitumen Tanker" => "Tanker",
        "Oil Products Tanker" => "Tanker",
        "Crude Oil Tanker" => "Tanker",
        "Oil And Chemical Tanker" => "Tanker",
        "Chemical Oil Products Tanker" => "Tanker",
        "Asphalt Bitumen Tanker" => "Tanker",
        "Fso Oil" => "Tanker",
        "Bunkering Tanker" => "Tanker",
        "Vegetable Oil Tanker" => "Tanker",
        "Lpg Tanker" => "Tanker",
        "Lng Tanker" => "Tanker",
        "Molasses Tanker" => "Tanker",
        "Other Tanker" => "Tanker",
        "Gas Tanker" => "Tanker",
        "Lng-Lpg Combination Gas Tanker" => "Tanker",
        "Bulk Carrier" => "Cargo",
        "Ore Carrier" => "Cargo",
        "Bulk Dry Storage Ship" => "Cargo",
        "Multi Purpose Carrier" => "Cargo",
        "Refrigerated Cargo Ship" => "Cargo",
        "Heavy Load Carrier" => "Cargo",
        "Ro Ro Cargo Ship" => "Cargo",
        "Passenger Ship" => "Cruise",
        "Cruise Ship" => "Cruise",
        "River Cruise Ship" => "River-Cruise",
        "Oil And Chemical Tank Barge" => "River-Tanker",
        "Vehicles Carrier" => "Ro-Ro",
        "Deck Cargo Ship" => "Cargo",
        "Self Discharging Bulk Carrier" => "Cargo",
        "Tug" => "Sonstiges",
        "Hopper Dredger" => "Other",
        "Service Ship" => "Other",
        "Supply Vessel" => "Other",
        "Offshore Support Vessel" => "Other",
        "Offshore Supply Ship" => "Other",
        "Training Ship" => "Other",
        "Fire Fighting Vessel" => "Other",
        "Dredger" => "Other",
        "Other" => "Other"
    );
}

?>