<?php

namespace App\Http\Controllers;

use App\Interfaces\LeadRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Address;
use DB; // DB::enableQueryLog(); // $q = DB::getQueryLog();  // print_r($appointment);//  dd(DB::getQueryLog());

class SolarEndPointsController extends Controller
{
    private LeadRepositoryInterface $leadRepository;

    public function __construct(LeadRepositoryInterface $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }
    /**
     * Updates a lead
     * @param Request $request
     * @return json object
     */
    public function updateLead(Request $request)
    {
        $request->phone = "8185235155";
        $request->id = 1;

        // $validated = $request->validate(
        //           [
        //               'id' => 'required|exists:lead',
        //               'phone' => 'required|numeric|digits:10'
        //           ],
        //           [
        //               'id.required'=>'There needs to be an lead Id',
        //               'id.exists'=>'This Lead Id does not exist in the Database',
        //               'phone.required'=>'Phone number required',
        //               'phone.numeric'=>'Numbers only',
        //               'phone.digits'=>'10 digiupdatets only'
        //         ]);
        $this->leadRepository->updateLeadInDatabase($request->id,  array('phone'=>'18185235155'));
        $lead_info =$this->leadRepository->getLeadById($request->id);

        // return response()->json([
        //   // 'data' => $this->leadRepository->updateLeadInDatabase($request->id,  array('phone'=>'18185235155'))
        //   'data' => $this->leadRepository->updateLeadInDatabase($request->id,  array('phone'=>'18185235155'))
        // ],201);

      return response(json_encode($lead_info), 201);
    }

    /**
     * Creates a lead and populated relational table address
     * @param Request $request
     * @return json object
     */
    public function createLead(Request $request)
    {
        $request->first_name = "Buster";
        $request->last_name = "The Dawg";
        $request->phone = "7206081722";
        $request->electric_bill = "123456";
        $request->street = "123 main st."    ;
        $request->city = "Denver";
        $request->state = "CO";
        $request->zip = "80210";


        //make sure the basics are correct

        // $validated = $request->validate(
        //           [
        //               'first_name' => 'required|between:8,255',
        //               'last_name' => 'required|between:8,255',
        //               'email' => 'required|email:rfc|unique:lead',
        //               'electric_bill' => 'required|9',
        //               'street' => 'required|between:8,255',
        //               'city' => 'required|between:8,255',
        //               'state' => 'required|numeric|between:2,2',
        //               'zip' => 'required|numeric|min:5',
        //           ],
        //           [
        //               'first_name.required'=>'First Name is required',
        //               'first_name.number'=>'First Name needs to be between 8 and 255 characters',
        //               'last_name.required'=>'Last Name is required',
        //               'last_name.number'=>'Last Name needs to be between 8 and 255 characters',
        //
        //               'electric_bill.required'=>'Electric Bill is required',
        //               'electric_bill.numeric'=>'Electric Bill number please',
        //
        //               'street.required'=>'street is required',
        //               'street.number'=>'street needs to be between 8 and 255 characters',
        //               'city.required'=>'city is required',
        //               'city.number'=>'city needs to be between 8 and 255 characters',
        //
        //               'zip.required'=>'zip is required',
        //               'zip.number'=>'zip needs to 5 digits',
        //
        //               'email'=>'The email needs to be unique and real, come on bro... ',
        //
        //
        //                     ]
        //                 );
        //
        // if ($validator->fails()) {
        //   $response = [
        //      'status'=>$validator->fails(),
        //      'barf'=> $validator
        //   ];
        // }



        $address = new Address(['street' => $request->street,'city'=>$request->city ,'state'=>$request->state,'zip'=>$request->zip]);

        $lead =  Lead::create([
      'first_name'=>$request->first_name,
      'last_name'=>$request->last_name,
      'phone'=>$request->phone,
      'electric_bill'=>$request->electric_bill,
      ]);
        $lead->address()->save($address);
        $barf = Lead::with('address')->where('id', $lead->id)->first();

        // $x="hello"; dd(__LINE__,__METHOD__, $barf);
        $response = [
         json_decode($barf)
      ];

        return response($response, 201);
    }

    /**
     * deletes leads by id
     * @param Request $request
     * @return json object
     */
    public function deleteLead(Request $request)
    {
        $request->id = 3;
        // $x="hello"; dd(__LINE__,__METHOD__, $request->id );
        $lead_info =$this->leadRepository->deleteLeadInDatabase($request->id);
        $response = [
         'status'=>"no errors",
      ];

        return response($response, 201);
    }

    /**
     * retrieve leads by id
     * @param Request $request
     * @return json object
     */
    public function readLead(Request $request)
    {
        $request->id = 3;
        $lead_info =$this->leadRepository->readLeadInDatabase($request->id);
        $x="hello"; dd(__LINE__,__METHOD__, $lead_info);
        $response = [
         'status'=>"no errors",
      ];

        return response($lead_info, 201);
    }

    /**
     * Gets leads based a quality
     * @param string quality null|premium|standard
     * @return json object
     */
    public function getQualityLead($quality)
    {
        $quaulity_lead_info =$this->leadRepository->queryDiffrentTypeOfLeads($quality);
        return response($quaulity_lead_info, 201);
    }

  }
