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

	public function save_multiple_guests($data)
	{
		return $this->db->insert_batch('tbl_guestlist',$data);
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
		date_default_timezone_set('Europe/Amsterdam');
        $date = date('Y-m-d H:i:s');
		//$time = date('H:i:s');
		$this->db->trans_start();
		$this->db->select('*');
		$this->db->from('tbl_events');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('concat_ws(" ", StartDate, StartTime)  >=', $date);
		$query = $this->db->get();
		$this->db->trans_complete();
		return $query->result_array();
	} 

	public function get_event_by_id($vendor_id, $eventId)
	{
		date_default_timezone_set('Europe/Amsterdam');
        $date = date('Y-m-d H:i:s');
		//$time = date('H:i:s');
		$this->db->trans_start();
		$this->db->select('*');
		$this->db->from('tbl_events');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('id', $eventId);
		$this->db->where('concat_ws(" ", StartDate, StartTime)  >=', $date);
		$query = $this->db->get();
		$this->db->trans_complete();
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
		foreach ($tickets as $key => $ticket) {
			$tickets[$key]['guestlistCount'] = $this->get_guestlist_count($ticket['ticketId']);
			$groupIds[] = $ticket['groupId'];
		}

		return $this->get_ticket_with_groups($tickets,$groups,$groupIds);
	}


	public function get_event_tickets($vendor_id,$eventId)
	{
		$dt = new DateTime('now', new DateTimeZone('Europe/Amsterdam'));
        $date = $dt->format('Y-m-d H:i:s');

		$this->db->trans_start();
		$this->db->select('*,tbl_event_tickets.id as ticketId, tbl_ticket_groups.id as groupId, concat_ws(" ", tbl_ticket_options.startDate, tbl_ticket_options.startTime) as startTimestamp, concat_ws(" ", tbl_ticket_options.endDate, tbl_ticket_options.endTime) as endTimestamp');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->join('tbl_ticket_groups', 'tbl_ticket_groups.id = tbl_event_tickets.ticketGroupId', 'left');
		$this->db->join('tbl_ticket_options', 'tbl_ticket_options.ticketId = tbl_event_tickets.id', 'left');
		$this->db->where('ticketVisible', 1);
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('tbl_event_tickets.eventId', $eventId);
		$this->db->group_by('tbl_event_tickets.id');
		$query = $this->db->get();
		$this->db->trans_complete();
		$results = $query->result_array();
		if(count($results) == 0){
			return $results;
		}
		$tickets = [];
		$nextFaseTickets = $this->verify_soldout_fase($eventId, $results);

		foreach($results as $result){
			$ticketFee = isset($result['nonSharedTicketFee']) ? number_format($result['nonSharedTicketFee'], 2, '.', '') : '0.00';
			$result['ticketFee'] = $ticketFee;
			$ticketId = $result['ticketId'];
			$tickets_used = $this->get_tickets_used($eventId);
			$ticket_used = isset($tickets_used[$ticketId]) ? $tickets_used[$ticketId] : 0;
			$ticket_available = intval($result['ticketQuantity']) - intval($ticket_used);
			$sold_out = false;

			if($result['ticketExpired'] == 'manually'){
				$startDt = new DateTime($result['startTimestamp'], new DateTimeZone('Europe/Amsterdam'));
				$startTimestamp = $startDt->format('Y-m-d H:i:s');
				$endDt = new DateTime($result['endTimestamp'], new DateTimeZone('Europe/Amsterdam'));
				$endTimestamp = $endDt->format('Y-m-d H:i:s');
				if($date < $startTimestamp){
					$sold_out = true;
					$result['soldOutWhenExpired'] = "<b style='color:#7855c4 !important;'>not for sale yet! The sales will start at ". $endDt->format('d M y - H:i').'</b>';
					//continue;
				}

				if($date > $endTimestamp){
					$sold_out = true;
				}

			}

			if($ticket_available <= 0){
				$sold_out = true;
			}

			if(isset($result['soldoutVisible']) && $result['soldoutVisible'] == 0 && $ticket_available <= 0){
				continue;
			}

			$previousFaseId = $result['previousFaseId'];

			if($previousFaseId != null && isset($nextFaseTickets[$previousFaseId]) && $nextFaseTickets[$previousFaseId]['soldout'] == false){
				continue;
			}

			

			$result['soldOut'] = $sold_out;
			$result['ticketAvailable'] = $ticket_available;
			$tickets[] = $result;
		}

		return $tickets;

	}


	public function get_ticket_by_id($vendor_id,$ticketId)
	{
		$this->db->trans_start();
		$this->db->select('ticketPrice, nonSharedTicketFee as ticketFee, ticketDescription, tbl_events.StartDate, tbl_events.StartTime, tbl_events.EndTime, ticketType');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->join('tbl_ticket_options', 'tbl_ticket_options.ticketId = tbl_event_tickets.id', 'right');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('tbl_event_tickets.id', $ticketId);
		$this->db->group_by('tbl_event_tickets.id');
		$query = $this->db->get();
		$this->db->trans_complete();
		return $query->first_row();
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

	public function get_event_by_ticket($ticketId)
	{
		$this->db->select('eventname, StartTime, EndTime, StartDate, EndDate');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->where('tbl_event_tickets.id', $ticketId);
		$query = $this->db->get();
		return $query->first_row();
	}

	public function get_ticket_info($ticketId)
	{
		$this->db->select('*');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_ticket_options', 'tbl_ticket_options.ticketId = tbl_event_tickets.id', 'left');
		$this->db->where('tbl_event_tickets.id', $ticketId);
		$query = $this->db->get();
		$result = $query->first_row();
		$ticket = $this->get_ticket_used($ticketId);
		$ticket_used = isset($ticket->ticket_used) ? $ticket->ticket_used : 0;
		$ticket_available = intval($result->ticketQuantity) - intval($ticket_used);
		$result->ticketAvailable = $ticket_available;
		$result->ticketFee = $result->nonSharedTicketFee;
		return $result;

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

	function get_active_payment_methods($vendor_id){

		$this->db->select('paymentMethod, percent, amount')
		->from('tbl_shop_payment_methods')
		->where(
			[
				'vendorId' => $vendor_id,
				'productGroup' => 'E-Ticketing',
				'active' => '1'
			]
		);
		$query = $this->db->get();
		$results = $query->result_array();
		$ticketing = [];
		foreach($results as $result){
			$ticketing[] = $result['paymentMethod'];
		}
		return $ticketing;
	}

	public function get_usershorturl($eventId)
	{
		$this->db->select('usershorturl');
		$this->db->from('tbl_events');
		$this->db->join('tbl_user', 'tbl_user.id = tbl_events.vendorId', 'left');
		$this->db->where('tbl_events.id', $eventId);
		$query = $this->db->get();
		if($query->num_rows() > 0 ){
			$result = $query->first_row();
			return $result->usershorturl;
		}
		return false;
	}

	function save_event_reservations($userInfo, $tickets = array(), $customer)
	{
		if(!isset($userInfo['email'])){ return; }

		$reservationIds = [];

		foreach($tickets as $ticket) {

			$quantityId = intval($ticket['quantity']);

			for ($i = 0; $i < $quantityId; $i++) {
				$insert = [
					'customer' => $customer,
					'eventId' => $ticket['id'],
					'eventdate' => date('Y-m-d', strtotime($ticket['startDate'])),
					'bookdatetime' => date('Y-m-d H:i:s'),
					'timefrom' => $ticket['startTime'],
					'timeto' => $ticket['endTime'],
					'price' => $ticket['price'],
					'ticketFee' => ($ticket['ticketFee'] != null) ? $ticket['ticketFee'] : 0,
					'numberofpersons' => '1',
					'name' => $userInfo['name'],
					'email' => $userInfo['email'],
					'age' => $userInfo['age'],
					'gender' => $userInfo['gender'],
					'mobilephone' => $userInfo['mobileNumber'],
					'Address' => $userInfo['address'],
					'zipcode' => $userInfo['zipcode'],
					'city' => $userInfo['city'],
					'country' => $userInfo['country'],
					'ticketDescription' => $ticket['descript'],
					'ticketType' => ($ticket['ticketType'] != null) ? $ticket['ticketType'] : 0,
					'paid' => '0'
				];
				$reservationIds[] = $this->insertTicket($insert);
			}
		}

		return $reservationIds;
	}

	private function insertTicket(array $ticket): string
	{

		$set = '3456789abcdefghjkmnpqrstvwxyABCDEFGHJKLMNPQRSTVWXY';
		$ticket['reservationId'] = 'T-' . substr(str_shuffle($set), 0, 16);

		if (!$this->db->insert('tbl_bookandpay', $ticket)) {
			$this->insertTicket($ticket, true);
		}

		return $this->db->insert_id();
	}

	function save_guest_reservations($data, $ticketQuantity){
		$reservationIds = [];
		for($i = 0; $i < $ticketQuantity; $i++){
			$reservationIds[] = $this->insertTicket($data);
		}
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
		WHERE tbl_bookandpay.paid = '1' AND tbl_bookandpay.ticketDescription <> '' AND tbl_events.vendorId = ".$vendorId." $sql
		ORDER BY reservationtime DESC");
		return $query->result_array();
	}

	public function get_financial_report($vendorId, $sql='')
	{
		$query = $this->db->query("SELECT tbl_bookandpay.id as bookandpay_id, reservationId, reservationtime, price, numberofpersons, ticketFee, name, age, gender, mobilephone, email, tbl_bookandpay.ticketDescription, eventname
		FROM tbl_bookandpay INNER JOIN tbl_event_tickets ON tbl_bookandpay.eventid = tbl_event_tickets.id 
		INNER JOIN tbl_events ON tbl_event_tickets.eventId = tbl_events.id
		WHERE tbl_bookandpay.paid = '1' AND tbl_bookandpay.ticketDescription <> '' AND tbl_bookandpay.customer = ".$vendorId." $sql
		ORDER BY reservationtime DESC");
		return $query->result_array();
	}


	public function get_booking_report_of_days($vendorId, $eventId, $sql='')
	{
		$query = $this->db->query("SELECT DATE(reservationtime) AS day_date,  eventdate, reservationtime, sum(numberofpersons) AS tickets 
		FROM tbl_bookandpay INNER JOIN tbl_event_tickets ON tbl_bookandpay.eventid = tbl_event_tickets.id 
		INNER JOIN tbl_events ON tbl_event_tickets.eventId = tbl_events.id
		WHERE tbl_events.vendorId = ".$vendorId." AND tbl_bookandpay.ticketDescription <> '' AND paid='1' AND tbl_events.Id = ".$eventId." $sql  GROUP BY day_date 
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
		$this->db->trans_start();
		$this->db->select('tbl_event_tickets.id as ticketId, ticketDescription');
		$this->db->from('tbl_event_tickets');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_event_tickets.eventId', 'left');
		$this->db->where('vendorId', $vendor_id);
		$this->db->where('tbl_event_tickets.eventId', $eventId);
		$query = $this->db->get();
		$this->db->trans_complete();
		return $query->result_array();
	}

	public function get_guestlist($eventId, $vendorId)
	{
		$this->db->select('tbl_guestlist.*');
		$this->db->from('tbl_guestlist');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_guestlist.eventId', 'left');
		$this->db->where('tbl_events.id', $eventId);
		$this->db->where('tbl_events.vendorId', $vendorId);
		$query = $this->db->get();
		return $query->result_array();
	}

	private function get_guestlist_count($ticketId)
	{
		$this->db->select('id');
		$this->db->from('tbl_guestlist');
		$this->db->where('ticketId', $ticketId);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_guestlists($vendorId)
	{
		$this->db->select('tbl_guestlist.*');
		$this->db->from('tbl_guestlist');
		$this->db->join('tbl_events', 'tbl_events.id = tbl_guestlist.eventId', 'left');
		$this->db->where('tbl_events.vendorId', $vendorId);
		$query = $this->db->get();
		return $query->result_array();
	}

	private function get_tickets_used($eventId)
	{
		$this->db->trans_start();
		$query = $this->db->query("SELECT tbl_event_tickets.id, SUM(numberofpersons) AS ticket_used
		FROM tbl_bookandpay INNER JOIN tbl_event_tickets ON tbl_bookandpay.eventid = tbl_event_tickets.id 
		INNER JOIN tbl_events ON tbl_event_tickets.eventId = tbl_events.id
		WHERE tbl_events.id = ".$eventId." AND paid = 1
		GROUP BY tbl_event_tickets.id");
		$this->db->trans_complete();
		$results = $query->result_array();
		$tickets = [];
		foreach($results as $result){
			$ticketId = $result['id'];
			$tickets[$ticketId] = $result['ticket_used'];
		}
		return $tickets;
	}

	private function get_ticket_used($eventId)
	{
		$this->db->trans_start();
		$query = $this->db->query("SELECT tbl_event_tickets.id, COUNT(tbl_bookandpay.eventid) AS ticket_used
		FROM tbl_bookandpay INNER JOIN tbl_event_tickets ON tbl_bookandpay.eventid = tbl_event_tickets.id 
		WHERE tbl_event_tickets.id = ".$eventId." AND paid = 1
		GROUP BY tbl_bookandpay.eventid");
		$this->db->trans_complete();
		return $query->first_row();
	}

	public function get_vendor_cost($vendorId)
	{
		$this->db->trans_start();
		$this->db->select('paymentMethod, vendorCost');
		$this->db->from('tbl_shop_payment_methods');
		$this->db->where('vendorId', $vendorId);
		$this->db->where('productGroup', 'E-Ticketing');
		$query = $this->db->get();
		$this->db->trans_complete();
		$results = $query->result_array();
		$vendorCosts = [];
		foreach($results as $result){
			$paymentMethod = $result['paymentMethod'];
			$vendorCosts[$paymentMethod] = $result['vendorCost'];
		}
		return $vendorCosts;
	}

	private function verify_soldout_fase($eventId, $results){
		$dt = new DateTime('now', new DateTimeZone('Europe/Amsterdam'));
        $date = $dt->format('Y-m-d H:i:s');
		$tickets = [];
		foreach($results as $result){
			$ticketId = $result['ticketId'];
			$tickets_used = $this->get_tickets_used($eventId);
			$ticket_used = isset($tickets_used[$ticketId]) ? $tickets_used[$ticketId] : 0;
			$ticket_available = intval($result['ticketQuantity']) - intval($ticket_used);
			$tickets[$ticketId]['soldout'] = false;

			if($result['ticketExpired'] == 'manually'){
				$startDt = new DateTime($result['startTimestamp'], new DateTimeZone('Europe/Amsterdam'));
				$startTimestamp = $startDt->format('Y-m-d H:i:s');
				$endDt = new DateTime($result['endTimestamp'], new DateTimeZone('Europe/Amsterdam'));
				$endTimestamp = $endDt->format('Y-m-d H:i:s');
				if($date < $startTimestamp){
					continue;
				}

				if($date > $endTimestamp){
					$tickets[$ticketId]['soldout'] = true;
				}

			}

			if($ticket_available <= 0){
				$tickets[$ticketId]['soldout'] = true;
			}

			
			
		}

		return $tickets;

	}


}
