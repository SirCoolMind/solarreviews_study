<?php

namespace App\Http\Controllers;

use App\Interfaces\LeadRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;

use DB; // DB::enableQueryLog(); // $q = DB::getQueryLog();  // print_r($appointment);//  dd(DB::getQueryLog());

class SolarEndPointsController extends Controller
{
    private LeadRepositoryInterface $leadRepository;

    public function __construct(LeadRepositoryInterface $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    /**
     * Creates a lead and populated relational table address
     * @param Request $request
     * @return json object
     */
    public function createLead(Request $request)
    {
        //Validate messages
        $messages = [
          'phone.numeric' => 'This phone number is not numeric',
          'first_name.required'=>'First Name is required',
          'first_name.number'=>'First Name needs to be between 8 and 255 characters',
          'last_name.required'=>'Last Name is required',
          'last_name.number'=>'Last Name needs to be between 8 and 255 characters',
          'electric_bill.required'=>'Electric Bill is required',
          'electric_bill.numeric'=>'Electric Bill number please',
          'street.required'=>'street is required',
          'street.number'=>'street needs to be between 8 and 255 characters',
          'city.required'=>'city is required',
          'city.number'=>'city needs to be between 8 and 255 characters',
          'zip.required'=>'zip is required',
          'zip.number'=>'zip needs to 5 digits',
        ];

        // This is probably the simplest method to validate data, there are a more abstract ways or possiblely more efficent ways to do this
        $validator = Validator::make(request()->all(), [
          'first_name' => 'required|between:8,255',
          'last_name' => 'required|between:8,255',
          'electric_bill' => 'required',
          'street' => 'required|between:8,255',
          'city' => 'required|between:8,255',
          'state' => 'required|between:2,2',
          'zip' => 'required|numeric|digits:5',
          'phone' => 'required|numeric|digits:10'

        ], $messages);

        if ($validator->fails()) {
            $e = $validator->errors()->all();
            return json_encode(array("error" => json_encode($e)));
        } else {
            $return_lead =$this->leadRepository->createLeadInDatabase($request);
            $response = [
           json_decode($return_lead)
          ];
            return response($response, 201);
        }
    }

    /**
     * deletes leads by id
     * @param int $id
     * @return json object
     */
    public function deleteLead($id)
    {
        $lead_info =$this->leadRepository->deleteLeadInDatabase($id);
        $response = [
         'status'=>"Successful soft delete",
      ];

        return response($response, 201);
    }

    /**
     * retrieve leads by id
     * @param int $id
     * @return json object
     */
    public function readLead($id)
    {
        $lead_info =$this->leadRepository->readLeadInDatabase($id);
        return response($lead_info, 201);
    }

    /**
     * Gets leads based a quality
     * @param string quality null|premium|standard
     * @return json object
     */
    public function getQualityLead($quality=null)
    {
        $quaulity_lead_info =$this->leadRepository->queryDiffrentTypeOfLeads($quality);
        return response($quaulity_lead_info, 201);
    }

    /**
     * Updates a lead
     * @param Request $request
     * @return json object
     */
    public function updateLead(Request $request)
    {
        //Validate messages
        $messages = [
          'id.required'=>'There needs to be an lead Id',
          'id.exists'=>'This Lead Id does not exist in the Database',
          'phone.required'=>'Phone number required',
          'phone.numeric'=>'Numbers only',
          'phone.digits'=>'10 digits only'
          ];

        // This is probably the simplest method to validate data, there are a more abstract ways or possiblely more efficent ways to do this
        $validator = Validator::make(request()->all(), [
          'id' => 'required|exists:leads',
          'phone' => 'required|numeric|digits:10'
      ], $messages);

        if ($validator->fails()) {
            $e = $validator->errors()->all();
            return json_encode(array("error" => json_encode($e)));
        } else {
            $this->leadRepository->updateLeadInDatabase($request->id, array('phone'=>$request->phone));
            $lead_info =$this->leadRepository->getLeadById($request->id);

            $response = [json_decode($lead_info)];

            return response($response, 201);
        }
    }
}
