<?php

namespace App\Http\Controllers;

use App;
use App\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class EmpCtrl extends Controller
{
	/**
	 * Show a list of all of the application's users.
	 *
	 * @return Response
	 */
	public function index(Request $request, $dep=0) {
		$status = $request->session()->get('status', 'n');
		$name = $request->session()->get('name');
		$lang = $request->session()->get('lang', 'en');
		$stt = $request->session()->get('stt', -1);
		App::setLocale($lang);

		return view('emp', ['dep' => $dep, 'status' => $status, 'name' => $name, 'lang' => $lang, 'stt' => $stt]);
	}

	// Delete the employee with given id
	public function delete($id) {
		Employee::destroy($id);
	}

	// Insert a new employee into database
	public function insert() {
		$_POST = json_decode(file_get_contents('php://input'), true);
		
		$emp = new Employee;

		$emp->emp_dob = $_POST['dob'];
		$emp->emp_name = $_POST['name'];
		$emp->emp_job = $_POST['job'];
		$emp->emp_phone = $_POST['phone'];
		$emp->emp_email = $_POST['email'];
		$emp->dep_id = $_POST['dep'];
		$emp->emp_photo = $_POST['photo'];
		
		$emp->save();
	}

	// Update an employee
	public function update() {
		$_POST = json_decode(file_get_contents('php://input'), true);

		$emp = Employee::find($_POST['emp_id']);

		$emp->emp_dob = $_POST['emp_dob'];
		$emp->emp_name = $_POST['emp_name'];
		$emp->emp_job = $_POST['emp_job'];
		$emp->emp_phone = $_POST['emp_phone'];
		$emp->emp_email = $_POST['emp_email'];
		$emp->dep_id = $_POST['dep_id'];
		$emp->emp_photo = $_POST['emp_photo'];

		$emp->save();
	}

	// Select all the employees with given conditions
	public function select($skip, $take, $dep, $name=null) {
		$skip = ($skip - 1) * $take;
		$data = Employee::orderBy('emp_id', 'desc')->skip($skip)->take($take);
		
		if ($dep != 0) {
			$data = $data->where('dep_id', '=', $dep);
		}

		if (isset($name)) {
			$data = $data->where('emp_name', 'like', '%'.$name.'%');
		}

		$data = $data->get();
		foreach ($data as $item) {
			if ($item->department == '') {
				$item->dep_name = '';
			} else {
				$item->dep_name = $item->department->dep_name;
			}
		}

		$result = '{"records":' . $data . '}';
		echo $result;
	}

	// Get the number of employees with given conditions
	public function count($dep, $name=null) {
		$data = Employee::orderBy('emp_id', 'desc');
		
		if ($dep != 0) {
			$data = $data->where('dep_id', '=', $dep);
		}

		if (isset($name)) {
			$data = $data->where('emp_name', 'like', '%'.$name.'%');
		}

		$data = $data->count();
		echo $data;
	}

	// Select a single employee with given id (for select sidenav)
	public function selectSingle($id=null) {
		$data = Employee::find($id);

		// $data->department
		if ($data->department == '') {
			$data->dep_name = '';
		} else {
			$data->dep_name = $data->department->dep_name;
		}

		$result = '{"record":[' . $data . ']}';
		echo $result;
		// echo 'test';
	}	

	// Select the names (and id) of all employees, for <select>
	public function selectNames() {
		$data = Employee::select('emp_id','emp_name')->orderBy('emp_name')->get();

		$result = '{"records":' . $data . '}';

		echo $result;
	}
}