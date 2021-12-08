<?php
namespace components\types;

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
        "Chemical Tanker" => "Tanker",
        "Bitumen Tanker" => "Tanker",
        "Oil Products Tanker" => "Tanker",
        "Lpg Tanker" => "Tanker",
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
        "Other" => "Sonstiges"
    );
}

?>