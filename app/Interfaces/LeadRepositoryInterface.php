<?php

namespace App\Interfaces;

interface LeadRepositoryInterface
{
    public function getLeadById(int $leadId);
    public function deleteLeadInDatabase(int $leadId);
    public function readLeadInDatabase(int $leadId);
    public function updateLeadInDatabase(int $leadId, array $newDetails);
    public function queryDiffrentTypeOfLeads(string $quality);
    public function createLeadInDatabase(array $request);
}
