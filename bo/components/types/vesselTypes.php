<?php
namespace bo\components\types;

class vesselTypes
{
    /**
     * $vesselType
     *
     * Unterschiedliche Schiffs Typen
     * @var array
     */
    public static $vesselTypes = array("Cargo", "Ro-Ro", "Tanker", "Cruise", "River-Cargo", "River-Cruise", "River-Tanker", "Sonstiges");
    
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
        "Bunkering Tanker" => "Tanker",
        "Lpg Tanker" => "Tanker",
        "Lng Tanker" => "Tanker",
        "Molasses Tanker" => "Tanker",
        "Bulk Carrier" => "Cargo",
        "Multi Purpose Carrier" => "Cargo",
        "Ro Ro Cargo Ship" => "Cargo",
        "Passenger Ship" => "Cruise",
        "Cruise Ship" => "Cruise",
        "River Cruise Ship" => "River-Cruise",
        "Vehicles Carrier" => "Ro-Ro",
        "Deck Cargo Ship" => "Cargo",
        "Self Discharging Bulk Carrier" => "Cargo",
        "Tug" => "Sonstiges",
        "Hopper Dredger" => "Sonstiges",
        "Service Ship" => "Sonstiges",
        "Offshore Support Vessel" => "Sonstiges",
        "Offshore Supply Ship" => "Sonstiges",
        "Other" => "Sonstiges"
    );
}

?>