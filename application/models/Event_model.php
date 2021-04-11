<?php
class Event_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper('utility_helper');
	}

	public function save_event($data)
	{
		$this->db->insert('tbl_events',$data);
		return $this->db->insert_id();
	}

	public function update_event($eventId, $data)
	{
		$this->db->where("id", $eventId);
		return $this->db->update('tbl_events',$data);
	}

	public function save_ticket($data)
	{
		return $this->db->insert('tbl_event_tickets',$data);
	}

	public function save_guest($data)
	{
		return $this->db->insert('tbl_guestlist',$data);
	}

	public function save_ticket_options($data)
	{
		$ticketId = $data['ticketId'];
		$this->db->where('ticketId ', $ticketId);
		if($this->db->get('tbl_ticket_options')->num_rows() == 0){
			return $this->db->insert('tbl_ticket_options',$data);
		}
		$this->db->where('ticketId ', $ticketId);
		return $this->db->update('tbl_ticket_options',$data);
		
	}

	public function save_ticket_group($groupname,$quantity,$eventId)
	{
		$this->db->insert('tbl_ticket_groups',['groupname' => $groupname, 'groupQuantity' => $quantity, 'eventId' => $eventId]);
		return $this->db->insert_id();
		
	}

	public function get_ticket_options($ticketId)
	{
		$this->db->select('*');
		$this->db->from('tbl_ticket_options');
		$this->db->where('ticketId', $ticketId);
		$query = $this->db->get();
		$results = $query->result_array();
		if(isset($results[0])){
			return $results[0];
		}
		return '';
	}

	public function get_events($vendor_id)
	{
		date_default_timezone_set('Europe/Berlin');
        $date = date('Y-m-d H:m:s');
		//$time = date('H:m:s');
		$this->db->select('*');
		$this->db->from('tbl_events');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('concat_ws(" ", StartDate, StartTime)  >=', $date);
		$query = $this->db->get();
		return $query->result_array();
	} 

	public function get_all_events($vendor_id)
	{
		
		$this->db->where('vendorId', $vendor_id);
		$query = $this->db->get('tbl_events');
		return $query->result_array();
	} 

	public function get_event($vendor_id,$eventId)
	{
		$this->db->where('id', $eventId);
		$this->db->where('vendorId', $vendor_id);
		$query = $this->db->get('tbl_events');
		return $query->first_row();
	}

	public function get_ticket($ticketId)
	{
		$this->db->where('id', $ticketId);
		$query = $this->db->get('tbl_event_tickets');
		return $query->first_row();
	}

	public function get_tickets($vendor_id,$eventId)
	{
		$this->db->select('*,tbl_event_tickets.id as ticketId, tbl_ticket_groups.id as groupId');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->join('tbl_ticket_groups', 'tbl_ticket_groups.id = tbl_event_tickets.ticketGroupId', 'left');
		$this->db->join('tbl_ticket_options', 'tbl_ticket_options.ticketId = tbl_event_tickets.id', 'left');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('tbl_event_tickets.eventId', $eventId);
		$this->db->group_by('tbl_event_tickets.id');
		$this->db->order_by('tbl_ticket_groups.id');
		$query = $this->db->get();
		$tickets = $query->result_array();
		$groups = $this->get_ticket_groups($eventId);
		$groupIds = [];
		foreach ($tickets as $ticket) {
			$groupIds[] = $ticket['groupId'];
		}

		return $this->get_ticket_with_groups($tickets,$groups,$groupIds);
	}


	public function get_event_tickets($vendor_id,$eventId)
	{
		$this->db->select('*,tbl_event_tickets.id as ticketId, tbl_ticket_groups.id as groupId');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->join('tbl_ticket_groups', 'tbl_ticket_groups.id = tbl_event_tickets.ticketGroupId', 'left');
		$this->db->join('tbl_ticket_options', 'tbl_ticket_options.ticketId = tbl_event_tickets.id', 'left');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('tbl_event_tickets.eventId', $eventId);
		$this->db->group_by('tbl_event_tickets.id');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0){
			return $results;
		}
		$tickets = [];
		foreach($results as $result){
			$ticketFee = isset($result['nonSharedTicketFee']) ? number_format($result['nonSharedTicketFee'], 2, '.', '') : '0.00';
			$result['ticketFee'] = $ticketFee;
			$tickets[] = $result;
		}

		return $tickets;

	}

	public function get_ticket_with_groups($tickets, $groups, $groupIds)
	{
		$ticket_groups = [];
		foreach ($groups as $group) {
			if(in_array($group['id'],$groupIds)){continue;}
			$ticket_groups[] = [
				'ticketId' => '0',
				'ticketDescription' => 'null',
				'ticketDesign' => '',
				'ticketQuantity' => 0,
				'ticketPrice' => 0,
				'ticketCurrency' => '',
				'ticketVisible' => 0,
				'emailId' => 1,
				'ticketGroupId' => $group['id'],
				'groupId' => $group['id'],
				'groupname' =>  $group['groupname'],
				'groupQuantity' => $group['groupQuantity']
				
			];
		}
		$results = array_merge($tickets,$ticket_groups);
		return $results;
	}

	public function get_eventname_by_ticket($ticketId)
	{
		$this->db->select('eventname');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->where('tbl_event_tickets.id', $ticketId);
		$query = $this->db->get();
		if($query->num_rows() > 0 ){
			$result = $query->first_row();
			return $result->eventname;
		}
		return ;
	}

	public function get_ticket_type($ticketId)
	{
		$this->db->select('ticketType');
		$this->db->from('tbl_event_tickets');
		$this->db->where('id', $ticketId);
		$query = $this->db->get();
		if($query->num_rows() > 0 ){
			$result = $query->first_row();
			return $result->ticketType;
		}
		return ;
	}


	public function get_ticket_groups($eventId)
	{
		$this->db->select('*');
		$this->db->from('tbl_ticket_groups');
		$this->db->where('eventId', $eventId);
		$query = $this->db->get();
		return $query->result_array();
	}

	function save_design($vendor_id,$design){
		$this->db->set('shopDesign', $design);
		$this->db->where('vendorId', $vendor_id);
		return $this->db->update('tbl_events');
	}

	function update_email_template($id, $emailId){
		$this->db->set('emailId', $emailId);
		$this->db->where('id', $id);
		return $this->db->update('tbl_event_tickets');
	}

	function update_group($id, $param, $value){
		$this->db->set($param, $value);
		$this->db->where('id', $id);
		return $this->db->update('tbl_ticket_groups');
	}

	function update_ticket_group($tickets){
		foreach($tickets as $key => $ticket){
			$groupId = $key;
			$this->db->set('ticketGroupId', $groupId);
			$ids = explode(',', $ticket);
			$this->db->where_in('id', $ids);
			$this->db->update('tbl_event_tickets');
		}
		return ;
	}

	function update_ticket($id, $param, $value){
		$this->db->set($param, $value);
		if($param == 'ticketCurrency'){
			$this->db->where('ticketId', $id);
			return $this->db->update('tbl_ticket_options');
		}
		$this->db->where('id', $id);
		return $this->db->update('tbl_event_tickets');
	}

	function delete_ticket($ticketId){
		$this->db->where('id', $ticketId);
		$this->db->delete('tbl_event_tickets');
		$this->db->where('ticketId', $ticketId);
		return $this->db->delete('tbl_ticket_options');
	}

	function delete_group($groupId){
		$this->db->where('id', $groupId);
		$this->db->delete('tbl_ticket_groups');
		$this->db->set('ticketGroupId', '0');
		$this->db->where('ticketGroupId', $groupId);
		return $this->db->update('tbl_event_tickets');
	}

	function get_design($vendor_id){

		$this->db->select('shopDesign')
		->from('tbl_events')
		->where('vendorId',$vendor_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_payment_methods($vendor_id){

		$this->db->select('paymentMethod, percent, amount')
		->from('tbl_shop_payment_methods')
		->where('vendorId',$vendor_id)
		->where('productGroup','E-Ticketing');
		$query = $this->db->get();
		$results = $query->result_array();
		$ticketing = [];
		foreach($results as $result){
			$ticketing[$result['paymentMethod']] = [
				'percent' => $result['percent'],
				'amount' => $result['amount']
			];
		}
		return $ticketing;
	}

	function save_event_reservations($userInfo, $tickets = array(), $customer){
		$data = [];
		if(!isset($userInfo['email'])){ return ;}
		$reservationIds = [];
		foreach($tickets as $ticket){
			$set = '3456789abcdefghjkmnpqrstvwxyABCDEFGHJKLMNPQRSTVWXY';
			$reservationId = 'T-' . substr(str_shuffle($set), 0, 16);
			$reservationIds[] = $reservationId;
			$savedatetime = new DateTime( 'now');
			$bookdatetime = $savedatetime->format('Y-m-d H:i:s');
			$data[] = [
				'reservationId' => $reservationId,
				'customer' => $customer,
				'eventId' => $ticket['id'],
				'eventdate' => date('Y-m-d', strtotime($ticket['startDate'])),
				'bookdatetime' => $bookdatetime,
				'timefrom' => $ticket['startTime'],
				'timeto' => $ticket['endTime'],
				'price' => $ticket['price'],
				'ticketFee' => $ticket['ticketFee'],
				'numberofpersons' => $ticket['quantity'],
				'name' => $userInfo['name'],
				'email' => $userInfo['email'],
				'age' => $userInfo['age'],
				'gender' => $userInfo['gender'],
				'mobilephone' => $userInfo['mobileNumber'],
				'Address' => $userInfo['address'],
				'ticketDescription' => $ticket['descript'],
				'ticketType' => $ticket['ticketType']

				//SQL
				/*
				ALTER TABLE `tbl_bookandpay` ADD `gender` VARCHAR(255) NULL AFTER `email`, ADD `age` DATE NULL AFTER `gender`; 
				
				*/
			];
		}
		$this->db->insert_batch('tbl_bookandpay',$data);
		 return $reservationIds;
	}

	function update_reservation_amount($reservationId, $amount){
		$this->db->where('reservationId', $reservationId);
		$this->db->update('tbl_bookandpay',['amount' => $amount]);
		return true;
	}

	public function get_ticket_report($vendorId, $eventId, $sql='')
	{
		$query = $this->db->query("SELECT reservationId, reservationtime, price,numberofpersons,(price*numberofpersons) as amount, name, age, gender, mobilephone, email, tbl_bookandpay.ticketDescription, ticketQuantity
		FROM tbl_bookandpay INNER JOIN tbl_event_tickets ON tbl_bookandpay.eventid = tbl_event_tickets.id 
		INNER JOIN tbl_events ON tbl_event_tickets.eventId = tbl_events.id
		WHERE tbl_events.vendorId = ".$vendorId." AND tbl_events.Id = ".$eventId." $sql
		ORDER BY reservationtime DESC");
		return $query->result_array();
	}

	public function get_tickets_report($vendorId, $sql='')
	{
		$query = $this->db->query("SELECT reservationId, reservationtime, price,numberofpersons,(price*numberofpersons) as amount, name, age, gender, mobilephone, email, tbl_bookandpay.ticketDescription, eventname
		FROM tbl_bookandpay INNER JOIN tbl_event_tickets ON tbl_bookandpay.eventid = tbl_event_tickets.id 
		INNER JOIN tbl_events ON tbl_event_tickets.eventId = tbl_events.id
		WHERE tbl_events.vendorId = ".$vendorId." $sql
		ORDER BY reservationtime DESC");
		return $query->result_array();
	}

	public function get_booking_report_of_days($vendorId, $eventId, $sql='')
	{
		$query = $this->db->query("SELECT DATE(reservationtime) AS day_date,  eventdate, reservationtime, sum(numberofpersons) AS tickets 
		FROM tbl_bookandpay INNER JOIN tbl_event_tickets ON tbl_bookandpay.eventid = tbl_event_tickets.id 
		INNER JOIN tbl_events ON tbl_event_tickets.eventId = tbl_events.id
		WHERE tbl_events.vendorId = ".$vendorId." AND tbl_events.Id = ".$eventId." $sql AND paid=1  GROUP BY day_date 
		ORDER BY day_date ASC");
		return $query->result_array();
	}

	function get_days_report($vendor_id, $eventId, $sql=''){
		$results = $this->get_booking_report_of_days($vendor_id, $eventId, $sql);
		$tickets = [];
		$newData = [];
		$maxDays = 0;
		foreach($results as $key => $result){
			$reservationDate = $result['day_date'];
			$eventDate = $result['eventdate'];
			$dStart = new DateTime($reservationDate);
			$dEnd  = new DateTime($eventDate);
			$dDiff = $dStart->diff($dEnd);
			$days = abs($dDiff->format('%r%a'));
			if($days > $maxDays){
				$maxDays = $days;
			}
			$tickets[$days] = $result['tickets'];
		}
	
		for($i = $maxDays; $i > 0; $i--){
			
			$newData[] = [
				"days" => ($i == 1) ? $i.' day' : $i.' days',
				"tickets" => isset($tickets[$i]) ? (int) $tickets[$i] : 0,
			];
		}
		return $newData;
	}


	public function get_all_event_tickets($vendor_id,$eventId)
	{
		$this->db->select('tbl_event_tickets.id as ticketId, ticketDescription');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('tbl_event_tickets.eventId', $eventId);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_guestlist($eventId, $vendorId)
	{
		$this->db->select('tbl_guestlist.*');
		$this->db->from('tbl_guestlist');
		$this->db->join('tbl_event_tickets', 'tbl_event_tickets.id = tbl_guestlist.ticketId', 'left');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->where('tbl_events.id', $eventId);
		$this->db->where('tbl_events.vendorId', $vendorId);
		$query = $this->db->get();
		return $query->result_array();
	}


}
