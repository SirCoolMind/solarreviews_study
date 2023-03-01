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
        /** "    - All fields except IDs and Timestamps should be set to NULL" **/
        // The above from the assignment, doesn't make complete sense. Ideally, one would want to just do a soft delete, no need to null out the data.
        // But if it was required, its not difficult to null out the data with a update to the db

        $id = Lead::find($leadId);
        $id->delete();

        $address = Address::find($leadId);
        $address->delete();
    }

    public function updateLeadInDatabase($leadId, array $newDetails)
    {
        return Lead::whereId($leadId)->update($newDetails);
    }

    public function queryDiffrentTypeOfLeads($quality)
    {
        if (is_null($quality)) {
            // If no `quality` parameter is submitted, all (non-soft-deleted) Leads and related Addresses should be returned in a JSON response
            return Lead::with('address')->get();
        } elseif ($quality=='premium') {
            // env variable seem to be strings so have to convert to int.
            $BILLING_THRESHOLD =(int)env('BILLING_THRESHOLD');
            // If the `quality` parameter is equal to `premium`, all (non-soft-deleted) Leads and related Addresses equal to or above the configurable threshold should be returned
            return Lead::where('electric_bill', '>', $BILLING_THRESHOLD)->with('address')->get();
        } elseif ($quality=='standard') {
            // env variable seem to be strings so have to convert to int.
            $BILLING_THRESHOLD =(int)env('BILLING_THRESHOLD');
            // - If the `quality` parameter is equal to `standard`, all (non-soft-deleted) Leads and related Addresses below the configurable threshold should be returned
            return Lead::where('electric_bill', '<', $BILLING_THRESHOLD)->with('address')->get();
        } else {
            return "Probably should have a safe guard? :)";
        }
    }

    public function readLeadInDatabase($leadId)
    {
        return Lead::where('id', $leadId)->with('address')->first();
    }

    public function createLeadInDatabase($request)
    {
        $address = new Address(['street' => $request->street,'city'=>$request->city ,'state'=>$request->state,'zip'=>$request->zip]);
        $lead =  Lead::create([
        'first_name'=>$request->first_name,
        'last_name'=>$request->last_name,
        'phone'=>$request->phone,
        'electric_bill'=>$request->electric_bill,
      ]);
        $lead->address()->save($address);
        $return_lead = Lead::with('address')->where('id', $lead->id)->whereNull('deleted_at')->first();
        // TODO: This returns created_at and delete_at, not good. I will have look into this known issue
        return $return_lead;
    }
}
