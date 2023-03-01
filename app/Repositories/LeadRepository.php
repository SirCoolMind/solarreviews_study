<?php

namespace App\Repositories;

use App\Interfaces\LeadRepositoryInterface;
use App\Models\Lead;
use App\Models\Address;
use DB;

class LeadRepository implements LeadRepositoryInterface
{

    public function getLeadById($leadId)
    {
        return Lead::findOrFail($leadId);
    }

    public function deleteLeadInDatabase($leadId)
    {
        $id = Lead::find($leadId);
        $id->delete();

        $address = Address::find($leadId);
        $address->delete();
    }

    public function updateLeadInDatabase($leadId, array $newDetails)
    {
        return Lead::whereId($leadId)->update($newDetails);
    }

    public function queryDiffrentTypeOfLeads(string $quality)
    {
      if (is_null($quality)){
        return Lead::with('address')->get();
      }
      elseif ($quality=='premium') {
        // env variable seem to be strings so have to convert to int.
        $BILLING_THRESHOLD =(int)env('BILLING_THRESHOLD');
        return Lead::where('electric_bill', '>', $BILLING_THRESHOLD)->with('address')->get();
      }
      elseif ($quality=='standard') {
        // env variable seem to be strings so have to convert to int.
        $BILLING_THRESHOLD =(int)env('BILLING_THRESHOLD');
        return Lead::where('electric_bill' ,'<', $BILLING_THRESHOLD)->with('address')->get();
      }
      else {
        return "Probably should have a safe guard? :)";
      }
    }

    public function readLeadInDatabase($leadId)
    {
        return Lead::where('id',$leadId)->with('address')->first();
    }
}
