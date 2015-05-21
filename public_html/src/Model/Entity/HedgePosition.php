<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Network\Http\Client;

/**
 * HedgePosition Entity.
 */
class HedgePosition extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'exchange_id' => true,
        'bias' => true,
        'amount' => true,
        'ssp' => true,
        'leverage' => true,
        'balance' => true,
        'openprice' => true,
        'closeprice' => true,
        'timeopened' => true,
        'recalculation' => true,
        'status' => true,
        'exchange' => true,
    ];
    
    public function getCurrentPrice($exchange) {        
        $http = new Client();
        
        if($exchange == "OKCOIN") {
            $ticker = json_decode($http->get('https://www.okcoin.com/api/v1/ticker.do?symbol=btc_usd')->body);
            return $ticker->ticker->buy;
            
        } else if ($exchange == "796") {
            $ticker = json_decode($http->get('http://api.796.com/v3/futures/ticker.html?type=weekly')->body);
            
            return $ticker->ticker->buy;
            
        } else if ($exchange == "BITVC") {
            return 0;
        }
    } // end getCurrentPrice()
    
    public function update() 
    {
        $this->HedgePositions = TableRegistry::get('HedgePositions');
        
        error_log("Update Script Called on Hedge #" . $this->id);
        $hedgePosition = $this;
        
        $openingPrice = $hedgePosition->lastprice;
        $currentPrice = $hedgePosition->getCurrentPrice($hedgePosition->exchange->name);
               
        $minBound = $hedgePosition->lastprice - ($hedgePosition->lastprice * $hedgePosition->ssp);
        $maxBound = $hedgePosition->lastprice + ($hedgePosition->lastprice * $hedgePosition->ssp);
               
        // Recalculate Stops.
        if($hedgePosition->bias == "LONG") {
            if($currentPrice < $minBound) {            
                // Close Position and Reopen at Current Price.
                $output = "<br /><strong>Closing Long Position at " . $hedgePosition->lastprice . " and re-opening at " . $currentPrice . "</strong>";
            
                $unrealizedPL = (($currentPrice - $hedgePosition->lastprice) * $hedgePosition->amount) / $currentPrice;
                
               
                // Update Old Hedge Position
                $hedgePosition->balance += $unrealizedPL;            
                $hedgePosition->timeopened = date("Y-m-d H:i:s");
                $hedgePosition->status = 0;
                $this->HedgePositions->save($hedgePosition);
                
                $newPosition = $this->HedgePositions->newEntity();
                
                $newPosition->exchange_id = $hedgePosition->exchange_id;
                $newPosition->bias = $hedgePosition->bias;
                $newPosition->amount = $hedgePosition->amount;
                $newPosition->ssp = $hedgePosition->ssp;
                $newPosition->leverage = $hedgePosition->leverage;
                $newPosition->balance = $hedgePosition->amount;
                $newPosition->lastprice = $currentPrice;
                $newPosition->timeopened = date("Y-m-d H:i:s");
                $newPosition->recalculation = $hedgePosition->recalculation;                
                
                if ($this->HedgePositions->save($newPosition)) {
                    error_log("Old Position Closed, New Position Created");
                } else {
                    error_log("Error: The hedge could not be Saved!");
                }
 
            } else {
                // else hold onto position
                error_log("Holding on to Position");
            }
            
        }
        
        if ($hedgePosition->bias == "SHORT") {
            if($currentPrice > $maxBound) {
                // Close Position and Reopen at Current Price.
                    
                $unrealizedPL = (($hedgePosition->lastprice - $currentPrice) * $hedgePosition->amount) / $currentPrice;
                
                $hedgePosition->balance += $unrealizedPL;
                $hedgePosition->timeopened = date("Y-m-d H:i:s");
                $hedgePosition->status = 0;
                $this->HedgePositions->save($hedgePosition);
                
                $newPosition = $this->HedgePositions->newEntity();
                
                $newPosition->exchange_id = $hedgePosition->exchange_id;
                $newPosition->bias = $hedgePosition->bias;
                $newPosition->amount = $hedgePosition->amount;
                $newPosition->ssp = $hedgePosition->ssp;
                $newPosition->leverage = $hedgePosition->leverage;
                $newPosition->balance = $hedgePosition->amount;
                $newPosition->lastprice = $currentPrice;
                $newPosition->timeopened = date("Y-m-d H:i:s");
                $newPosition->recalculation = $hedgePosition->recalculation;   
                
                
                if ($this->HedgePositions->save($newPosition)) {
                    error_log("Old Position Closed, New Position Created");
                } else {
                    error_log("Error: The hedge could not be Saved!");
                }
            } else {
                // else hold onto position.
                error_log("Hoding on to Position");
            }
            
        }
    } // end update
}
