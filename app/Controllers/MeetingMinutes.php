<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MeetingModel;

class MeetingMinutes extends BaseController
{
    public function index()
    {
        $data = $this->data;
        $data['canonical'] = base_url('meetingminutes');
        $this->webConfig->checkMemberLogin($data['canonical']);

        $model = new MeetingModel();

        // Distinct values for filter dropdowns (separate instance so main builder is untouched)
        $filterModel = new MeetingModel();
        $data['campuses'] = $filterModel->select('campus_name')->distinct()->orderBy('campus_name', 'ASC')->findAll();
        $data['ministries'] = (new MeetingModel())->select('ministry')->distinct()->orderBy('ministry', 'ASC')->findAll();

        $filterCampus = $this->request->getGet('campus');
        $filterMinistry = $this->request->getGet('ministry');
        $searchQuery = trim((string) $this->request->getGet('q'));

        $model->orderBy('created_at', 'DESC');
        if ($filterCampus !== null && $filterCampus !== '') {
            $model->where('campus_name', $filterCampus);
        }
        if ($filterMinistry !== null && $filterMinistry !== '') {
            $model->where('ministry', $filterMinistry);
        }
        if ($searchQuery !== '') {
            $db = \Config\Database::connect();
            $model->where('MATCH(ai_summary, minutes_md) AGAINST(' . $db->escape($searchQuery) . ' IN NATURAL LANGUAGE MODE)');
        }

        // Fetch all (filtered) meetings for client-side pagination via DataTables.
        // Use a reasonable upper bound to avoid excessive memory usage.
        $data['meetings'] = $model->findAll(1000);
        $data['pageTitle'] = 'Meeting Minutes';
        $data['filterCampus'] = $filterCampus;
        $data['filterMinistry'] = $filterMinistry;
        $data['searchQuery'] = $searchQuery;

        $data['themeUrl'] = base_url('assets/theme-sb-admin-2');
        $data['sidebar'] = view('theme-sb-admin-2/sidebar', $data);
        $data['mainContent'] = view('theme-sb-admin-2/meeting_minutes_list', $data);
        echo view('theme-sb-admin-2/layout', $data);
    }

    /**
     * View a single meeting's details and files.
     */
    public function view($id)
    {
        $data = $this->data;
        $data['canonical'] = base_url('meetingminutes/view/' . $id);
        $this->webConfig->checkMemberLogin($data['canonical']);

        $model = new MeetingModel();
        $meeting = $model->find($id);
        if (!$meeting) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['meeting'] = $meeting;
        $data['pageTitle'] = 'Meeting Minutes – View';

        $data['themeUrl'] = base_url('assets/theme-sb-admin-2');
        $data['sidebar'] = view('theme-sb-admin-2/sidebar', $data);
        $data['mainContent'] = view('theme-sb-admin-2/meeting_minutes_view', $data);
        echo view('theme-sb-admin-2/layout', $data);
    }
}
